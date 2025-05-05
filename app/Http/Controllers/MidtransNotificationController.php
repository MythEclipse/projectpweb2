<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSizeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

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
     * Handle Midtrans notification (webhook).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // Log received notification for debugging
        Log::info('Midtrans Notification Received', $request->all());

        $notification = new Notification();

        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $transactionTime = $notification->transaction_time;
        $fraudStatus = $notification->fraud_status;

        // Find the order in your database
        $order = Order::where('midtrans_order_id', $orderId)->first();

        // If order not found, return 404
        if (!$order) {
            Log::warning('Midtrans Notification: Order not found', ['order_id' => $orderId]);
            return response('Order not found', 404);
        }

         // Avoid double processing if order status is already final (paid, cancelled, etc.)
        if ($order->payment_status === 'settlement' || $order->payment_status === 'expire' || $order->payment_status === 'cancel') {
             Log::info('Midtrans Notification: Order already processed', ['order_id' => $orderId, 'current_status' => $order->payment_status]);
             return response('Order already processed', 200); // Return OK so Midtrans stops retrying
        }


        DB::beginTransaction();
        try {
            // Update order based on transaction status from Midtrans
            $order->midtrans_transaction_id = $notification->transaction_id;
            $order->midtrans_gross_amount = $grossAmount;
            $order->midtrans_payment_type = $paymentType;
            $order->midtrans_expiry_time = $notification->expiry_time ?? null; // May not be present for all types

            // Example: get VA number for bank transfer
            if ($paymentType === 'bank_transfer') {
                $bank = $notification->va_numbers[0]->bank ?? null;
                $vaNumber = $notification->va_numbers[0]->va_number ?? null;
                $order->midtrans_va_number = $bank . ': ' . $vaNumber;
            }
            // Add logic for other payment types like Qris etc.

            switch ($transactionStatus) {
                case 'capture': // For credit card transactions, status `capture` means funds have been successfully captured
                case 'settlement': // For non-credit card transactions, status `settlement` means funds have been successfully received by merchant
                    if ($fraudStatus == 'challenge') {
                        // TODO: Set order status to 'challenge'
                        $order->status = 'challenge';
                        $order->payment_status = 'challenge';
                    } else if ($fraudStatus == 'accept') {
                        // TODO: Set order status to 'paid'
                        $order->status = 'paid';
                        $order->payment_status = 'settlement'; // Or 'paid'
                        // --- DECREMENT STOCK AND DELETE CART (ONLY ON SUCCESS) ---
                        $this->decrementStock($order);
                         // No need to delete cart here, it's already deleted in CheckoutController@process
                    }
                    break;

                case 'pending': // Transaction is pending, e.g., waiting for payment
                    // TODO: Set order status to 'pending_payment'
                     $order->status = 'pending_payment';
                     $order->payment_status = 'pending';
                    break;

                case 'deny': // Transaction rejected by bank or Midtrans fraud detection
                    // TODO: Set order status to 'failed'
                     $order->status = 'failed';
                     $order->payment_status = 'deny';
                    break;

                case 'expire': // Transaction expired
                    // TODO: Set order status to 'expired'
                     $order->status = 'failed'; // Or 'expired'
                     $order->payment_status = 'expire';
                     // Revert stock? Depends on your business logic. If stock was already locked, unlock it.
                    break;

                case 'cancel': // Transaction cancelled by user or merchant
                    // TODO: Set order status to 'cancelled'
                     $order->status = 'cancelled';
                     $order->payment_status = 'cancel';
                    // Revert stock? Depends on your business logic.
                    break;

                case 'refund': // Refund process initiated
                case 'partial_refund': // Partial refund process initiated
                    // TODO: Handle refunds - update order status, log refund details
                     $order->status = 'partially_refunded'; // Or 'refunded'
                     $order->payment_status = 'refunded';
                    break;

                // Handle other statuses like 'authorize' (for pre-auth credit cards) if needed
            }

            $order->save();

            DB::commit();

            Log::info('Midtrans Notification: Order status updated successfully', [
                'order_id' => $orderId,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);

            // Return 200 OK to Midtrans to indicate successful processing
            return response('OK', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Notification Processing Error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'exception' => $e
            ]);
            // Return 500 Internal Server Error so Midtrans retries
            return response('Error processing notification', 500);
        }
    }

    /**
     * Decrement stock for each item in the order after successful payment.
     * This MUST be called within a database transaction and use locking.
     *
     * @param Order $order
     * @return void
     * @throws \Exception If stock is insufficient (shouldn't happen if initial check was done)
     */
    private function decrementStock(Order $order): void
    {
        foreach ($order->items as $item) {
            // Find ProductSizeColor with lock for update
            // Use findOrFail or similar if you want it to fail hard if item isn't found
            $stock = ProductSizeColor::where('product_id', $item->product_id)
                ->where('size_id', $item->size_id)
                ->where('color_id', $item->color_id)
                ->lockForUpdate() // !!! Critical: Lock during stock update
                ->first();

            // Double check stock level before decrementing (should pass if initial check was done)
            if (!$stock || $stock->stock < $item->quantity) {
                // Log a critical error - stock was available at checkout but not now
                Log::critical('Midtrans Notification: Stock insufficient during final decrement', [
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'product_size_color_id' => $stock->id ?? 'N/A',
                    'requested_qty' => $item->quantity,
                    'available_stock' => $stock ? $stock->stock : 0
                ]);
                // Depending on policy, you might throw an exception here to trigger rollback,
                // or fulfill partially, or flag for manual review. Throwing is safer to prevent
                // overselling in a race condition, but might cancel a paid order.
                throw new \Exception("Stock insufficient for item {$item->id} (Product ID: {$item->product_id}) during final decrement.");
            }

            // Kurangi stok
            $stock->decrement('stock', $item->quantity);

            // Hapus ProductSizeColor jika stok habis (opsional)
            if ($stock->stock <= 0) {
                $stock->delete();
            }
        }
    }
}
