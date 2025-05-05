<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order; // Import your Order model
use Illuminate\Http\JsonResponse; // Import JsonResponse class
use App\Models\ProductSizeColor; // Import your stock model
use Illuminate\Support\Facades\DB; // Import DB facade for transactions and locking
use Midtrans\Config;
use Midtrans\Notification; // Import Midtrans Notification

class MidtransNotificationController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Handle incoming Midtrans notifications.
     * This method does NOT require 'auth' or 'VerifyCsrfToken' middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        // Log the raw notification data for debugging
        Log::info('Midtrans Notification Received', ['payload' => $request->all()]);

        // Create Midtrans Notification object
        $notification = new Notification();

        // Access notification properties
        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status;
        $signatureKey = $notification->signature_key;

        // --- 1. Verify Notification Signature ---
        // Important security step! Check if the notification is valid and from Midtrans.
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . Config::$serverKey);
        if ($signatureKey !== $calculatedSignature) {
            Log::warning('Midtrans Notification: Invalid Signature Key', [
                'order_id' => $orderId,
                'received_signature' => $signatureKey,
                'calculated_signature' => hash('sha512', $orderId . $statusCode . $grossAmount . Config::$serverKey)
            ]);
            return response()->json(['message' => 'Invalid signature key'], 401); // Unauthorized
        }

        // --- 2. Find the Order in Your Database ---
        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::error('Midtrans Notification: Order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404); // Not Found
        }

        // --- 3. Use Database Transaction and Lock for Critical Updates (Stock/Status) ---
        DB::beginTransaction();
        try {
            // Re-fetch the order with a pessimistic lock to prevent race conditions
            // This is crucial if multiple notifications could potentially arrive for the same order around the same time
             $order = Order::where('midtrans_order_id', $orderId)->lockForUpdate()->first();

             if (!$order) { // Should not happen if found outside transaction, but good practice
                throw new \Exception("Order {$orderId} disappeared during lock.");
             }

            // --- 4. Handle different Transaction Statuses ---
            $updateData = [];
            $processStockDecrement = false; // Flag to decide whether to decrement stock

            switch ($transactionStatus) {
                case 'capture': // Card payment was successful (for non-3DS)
                case 'settlement': // Payment successful (including card 3DS, VA, etc.)
                    $updateData['payment_status'] = 'settlement'; // Use Midtrans status for precision
                    $updateData['status'] = 'processing'; // Or 'paid' - status indicating payment received
                    $updateData['midtrans_transaction_id'] = $notification->transaction_id;
                    $updateData['midtrans_gross_amount'] = $notification->gross_amount;
                    $updateData['midtrans_payment_type'] = $paymentType;
                    // Add other Midtrans details if available (va_number, etc.)
                     if (isset($notification->va_numbers[0]['va_number'])) {
                         $updateData['midtrans_va_number'] = $notification->va_numbers[0]['va_number'];
                     }
                     if (isset($notification->expiry_time)) {
                         $updateData['midtrans_expiry_time'] = $notification->expiry_time;
                     }

                    // Only process stock decrement if the order was previously unpaid
                    // This prevents double decrement in case of duplicate notifications
                    if ($order->payment_status === 'unpaid' || $order->payment_status === 'pending') {
                        $processStockDecrement = true;
                    }
                    break;

                case 'pending': // Waiting for payment (e.g., VA, bank transfer)
                    $updateData['payment_status'] = 'pending'; // Use Midtrans status
                    $updateData['status'] = 'pending'; // Keep main status pending
                     if (isset($notification->va_numbers[0]['va_number'])) {
                         $updateData['midtrans_va_number'] = $notification->va_numbers[0]['va_number'];
                     }
                     if (isset($notification->expiry_time)) {
                         $updateData['midtrans_expiry_time'] = $notification->expiry_time;
                     }
                    break;

                case 'expire': // Transaction expired
                case 'cancel': // Transaction cancelled by user or system
                    $updateData['payment_status'] = $transactionStatus; // Use Midtrans status
                    $updateData['status'] = 'cancelled'; // Or 'failed'
                    // You might want to release held stock here if you implemented holding
                    break;

                case 'deny': // Card was denied
                case 'failure': // General failure
                    $updateData['payment_status'] = $transactionStatus; // Use Midtrans status
                    $updateData['status'] = 'failed';
                     // Release held stock if applicable
                    break;

                // Add other statuses if needed, e.g., 'authorize', 'refund' etc.
                default:
                    Log::warning('Midtrans Notification: Unhandled transaction status', [
                        'order_id' => $orderId,
                        'transaction_status' => $transactionStatus,
                        'payload' => $request->all(),
                    ]);
                    // Acknowledge receipt even for unhandled statuses
                    DB::commit(); // Commit the transaction even if no status change was made
                    return response()->json(['message' => 'Unhandled transaction status'], 200);
            }

            // --- 5. Update Order Status in DB ---
            // Only update if the new status is different or more "final" than the current one
            // This prevents status being reverted by delayed notifications
             if ($this->shouldUpdateOrderStatus($order->payment_status, $updateData['payment_status'])) {
                 $order->update($updateData);
                 Log::info('Midtrans Notification: Order status updated', [
                     'order_id' => $orderId,
                     'old_payment_status' => $order->getOriginal('payment_status'),
                     'new_payment_status' => $order->payment_status,
                     'old_status' => $order->getOriginal('status'),
                     'new_status' => $order->status,
                 ]);
             } else {
                  Log::info('Midtrans Notification: Order status update skipped (less final status)', [
                      'order_id' => $orderId,
                      'current_payment_status' => $order->payment_status,
                      'new_payment_status' => $updateData['payment_status'],
                  ]);
             }


            // --- 6. Decrement Stock for Successful Payments ---
            if ($processStockDecrement) {
                 Log::info('Midtrans Notification: Processing stock decrement for order', ['order_id' => $orderId]);
                $order->load('transactionItems.productSizeColor'); // Eager load items and their stock variants

                foreach ($order->transactionItems as $item) {
                    $stockVariant = $item->productSizeColor; // This should be the ProductSizeColor model

                    // Check if stock variant exists and has enough stock *at this moment*
                    // The lockForUpdate() on the Order model should help prevent race conditions across orders,
                    // but for race conditions on the same stock item from *different* orders,
                    // it's best practice to lock the ProductSizeColor row as well within the same transaction.
                    // However, directly locking PSC here might conflict if Order lock is too broad.
                    // A cleaner way is to rely on the database lock and handle potential insufficient stock errors on decrement.

                    // Let's directly decrement and check the result
                    if ($stockVariant) {
                        // Use where clause with primary key inside update for safety and potential row lock
                         $affectedRows = ProductSizeColor::where('id', $stockVariant->id)
                            ->where('stock', '>=', $item->quantity) // Ensure enough stock BEFORE decrement
                            ->lockForUpdate() // Apply lock specifically to this stock row
                            ->decrement('stock', $item->quantity);

                        if ($affectedRows === 0) {
                            // This is a critical scenario: Payment successful, but stock is now insufficient.
                            // You need a strategy:
                            // 1. Log the error and manually handle it (e.g., refund, contact customer).
                            // 2. Throw an exception to rollback the order status update (might revert to unpaid/pending after payment!).
                            // Option 1 is generally better: Log and manual intervention, as payment IS successful.
                            Log::error("Midtrans Notification: Insufficient stock for item {$item->id} in order {$orderId}. Needed {$item->quantity}, Available was less. Affected rows: 0.");
                            // Maybe update the order status to 'partially_stocked' or add a flag?
                            // $order->update(['notes' => ($order->notes ?? '') . "\n[STOCK ERROR] Insufficient stock for item {$item->id} ({$item->quantity} pcs)"]);
                            // Do NOT throw here if you want the payment status to be 'settlement'.

                            // If you MUST rollback everything (payment status included), uncomment the line below:
                            // throw new \Exception("Insufficient stock for item {$item->id} after payment settlement.");

                            // For this example, we log and continue, requiring manual admin intervention.
                        } else {
                            Log::info("Midtrans Notification: Stock decremented for item {$item->id} in order {$orderId}. Decremented by {$item->quantity}.");
                        }
                    } else {
                         Log::error("Midtrans Notification: ProductSizeColor variant not found for item {$item->id} in order {$orderId}. Stock cannot be decremented.");
                         // Handle missing stock variant (e.g., log, flag the order)
                    }
                }
            }

            // --- 7. Commit the Database Transaction ---
            DB::commit();
            Log::info('Midtrans Notification: Transaction committed successfully', ['order_id' => $orderId]);

            // --- 8. Return Success Response to Midtrans ---
            return response()->json(['message' => 'Notification processed successfully'], 200);

        } catch (\Throwable $e) {
            DB::rollBack(); // Rollback the transaction if any error occurs
            Log::error('Midtrans Notification Processing Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e
            ]);

            // Return an error response to Midtrans (they might retry)
            return response()->json(['message' => 'Error processing notification'], 500); // Internal Server Error
        }
    }

    /**
     * Helper to determine if the order status should be updated based on the new payment status.
     * Prevents updating a settled/cancelled order back to pending or unpaid if a duplicate
     * or delayed notification arrives.
     */
     private function shouldUpdateOrderStatus(string $currentPaymentStatus, string $newPaymentStatus): bool
     {
         $statusHierarchy = [
             'unpaid' => 0,
             'pending' => 1,
             'authorize' => 2, // Card status before capture
             'capture' => 3,   // Card status after capture (successful)
             'settlement' => 3, // Non-card successful payment
             'refunded' => 4,
             'deny' => 5,
             'expire' => 6,
             'cancel' => 7,
             'failure' => 8, // General failure
         ];

         $currentLevel = $statusHierarchy[$currentPaymentStatus] ?? -1;
         $newLevel = $statusHierarchy[$newPaymentStatus] ?? -1;

         // Update if:
         // 1. The new status is more final than the current status ($newLevel > $currentLevel)
         // 2. The new status is the same level as a successful status (capture/settlement) and the current is less final
         // 3. The new status is a final failure/cancel status ($newLevel >= 5) regardless of current less final status
         // Avoid updating if new status is less final (e.g., updating 'settlement' back to 'pending')

         // More simply: If the new status is *more* final, or if it's a final state (cancelled, expired, failed, denied, refunded), or if it's a settlement/capture and the current is not a final state.
         // Let's simplify: Only update if the *level* is higher, OR if the new status is a final state (cancelled, expired, denied, failure, refunded).
         // We also *always* want to update to 'settlement' or 'capture' if the current is 'unpaid' or 'pending'.

         if ($newPaymentStatus === $currentPaymentStatus) {
             return false; // No change needed
         }

         // Always allow update to a successful status from pending/unpaid
         if (in_array($newPaymentStatus, ['settlement', 'capture']) && in_array($currentPaymentStatus, ['unpaid', 'pending'])) {
             return true;
         }

         // Always allow update to a final failure/cancel status (deny, expire, cancel, refunded, failure)
          if (in_array($newPaymentStatus, ['deny', 'expire', 'cancel', 'refunded', 'failure'])) {
              // If current is already a final state, maybe don't override? Depends on desired behavior.
              // For simplicity, let's allow updating to *any* final state from non-final states or less severe final states.
              if (!in_array($currentPaymentStatus, ['deny', 'expire', 'cancel', 'refunded', 'failure', 'settlement', 'capture'])) {
                 return true; // Update from non-final state
              }
               // Allow updating between failure states if logic requires (e.g., cancel -> expire)
              if (in_array($currentPaymentStatus, ['deny', 'expire', 'cancel', 'refunded', 'failure'])) {
                  // You might need more complex logic here based on specific transitions allowed
                  // For now, let's say a more final failure status can override a less final one based on hierarchy
                  if ($newLevel > $currentLevel) return true;
              }
          }

         // Prevent updating a successful or final state back to pending/unpaid/authorize
         if (in_array($currentPaymentStatus, ['settlement', 'capture', 'deny', 'expire', 'cancel', 'refunded', 'failure'])) {
             // Current status is already final or successful, prevent update to less final status
             if (!in_array($newPaymentStatus, ['deny', 'expire', 'cancel', 'refunded', 'failure'])) { // If new status is NOT a final state
                 return false;
             }
         }


         // Default to false for any other transitions not explicitly allowed above
         Log::warning('Midtrans Notification: Skipping less significant status update', [
             'current_status' => $currentPaymentStatus,
             'new_status' => $newPaymentStatus,
         ]);

         return false; // Don't update by default if not explicitly handled
     }
}
