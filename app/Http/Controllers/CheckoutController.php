<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\ProductSizeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse; // Import JsonResponse
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Menampilkan halaman checkout.
     * ... (method index tetap sama) ...
     */
    public function index(): View | RedirectResponse
    {
        $cartItems = Auth::user()->cartItems()->with(['product', 'size', 'color'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        try {
            $this->checkStockForCheckout($cartItems);
        } catch (ValidationException $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Beberapa item di keranjang memiliki stok tidak mencukupi. Silakan periksa kembali.');
        }

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $userAddress = Auth::user()->address;

        return view('checkout.index', compact('cartItems', 'cartTotal', 'userAddress'));
    }

    /**
     * Memproses data checkout, membuat order, dan menginisiasi transaksi Midtrans (Snap Token).
     * Mengembalikan JSON response berisi snap_token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(Request $request): JsonResponse | RedirectResponse // Return type bisa JSON atau Redirect (jika ada error redirect)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with(['product', 'size', 'color'])->get();

        if ($cartItems->isEmpty()) {
            // Ini bisa jadi redirect, bukan JSON, karena ini error pra-form
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi data checkout
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // --- Final Stock Check (No Lock here, Lock happens on notification) ---
            $this->checkStockForCheckout($cartItems, false);

            // --- Hitung Total ---
            $totalAmount = $cartItems->sum(function ($item) {
                if (!$item->product) {
                    throw new \Exception("Product not found for cart item {$item->id}");
                }
                return $item->quantity * $item->product->price;
            });

            // Pastikan totalAmount adalah integer untuk Midtrans IDR
            $totalAmountInteger = (int) round($totalAmount); // Gunakan round() untuk pembulatan jika ada nilai koma kecil

            // --- Buat Order di Database ---
            $orderId = 'ORDER-' . date('YmdHi') . '-' . $user->id . '-' . uniqid();

            $order = Order::create([
                'user_id' => $user->id,
                'midtrans_order_id' => $orderId,
                'total_amount' => $totalAmountInteger, // Simpan nilai integer
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_method' => $validated['payment_method'] ?? 'Midtrans Snap',
                'shipping_address' => $validated['shipping_address'],
                'shipping_status' => 'not_shipped',
                'notes' => $validated['notes'] ?? null,
            ]);

            // --- Pindahkan Cart Items ke Order Items (Tabel transactions) ---
            foreach ($cartItems as $item) {
                Transaction::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'size_id' => $item->size_id,
                    'color_id' => $item->color_id,
                    'quantity' => $item->quantity,
                    'price' => (int) round($item->product->price), // Pastikan harga item juga integer
                ]);
            }
            $notificationUrl = route('midtrans.notification');
            Log::info('Generated Midtrans Notification URL', ['url' => $notificationUrl]);
            // --- Buat Transaksi di Midtrans (Dapatkan Snap Token) ---
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalAmountInteger, // Pastikan ini integer
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    // Tambahkan field lain seperti last_name, phone, dll. jika ada
                    'shipping_address' => [ // Midtrans expects nested structure for address
                        'first_name' => $user->name, // Atau nama penerima jika berbeda
                        'address' => $validated['shipping_address'],
                        'city' => 'N/A', // Ganti dengan field kota jika ada
                        'postal_code' => 'N/A', // Ganti dengan field kode pos jika ada
                        'phone' => $user->phone ?? 'N/A', // Ganti dengan field telepon jika ada
                        'country_code' => 'IDN', // Ganti jika mendukung negara lain
                    ],
                    // 'billing_address' => [ ... ] // Jika ada alamat billing yang berbeda
                ],
                'item_details' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->product->id,
                        'price' => (int) round($item->product->price), // Pastikan ini integer
                        'quantity' => $item->quantity,
                        'name' => $item->product->name . ($item->size || $item->color ? ' (' . ($item->size->name ?? '') . ($item->size && $item->color ? '/' : '') . ($item->color->name ?? '') . ')' : ''),
                        'category' => $item->product->category->name ?? 'Lainnya',
                        'merchant_name' => config('app.name'),
                    ];
                })->toArray(),
                // Optional: Add callbacks for Snap.js (frontend JS)
                'callbacks' => [
                    'notification' => $notificationUrl, // Use the route name defined below
                    // Optional: Add 'finish', 'unfinish', 'error' for frontend redirects
                    // 'finish' => route('orders.index'),
                    // 'unfinish' => route('checkout.index'),
                    // 'error' => route('checkout.index'),
                ]
            ];

            // Panggil Snap API
            $snap = Snap::createTransaction($params);

            // Simpan Snap Token dan Redirect URL ke database Order
            $order->midtrans_snap_token = $snap->token;
            $order->midtrans_redirect_url = $snap->redirect_url; // Tetap simpan, bisa berguna
            $order->save();

            // --- Hapus item dari keranjang ---
            $user->cartItems()->delete();

            DB::commit();

            // --- Kembalikan Snap Token dalam bentuk JSON ---
            return response()->json(['snap_token' => $snap->token]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Checkout Validation Failed:', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            // Mengembalikan error validasi dalam bentuk JSON agar bisa ditangkap di frontend
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422); // Status code 422 Unprocessable Entity untuk validasi
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout Processing Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            // Mengembalikan error umum dalam bentuk JSON
            return response()->json([
                'message' => 'Terjadi kesalahan internal saat memproses checkout. Silakan coba lagi nanti.',
                'error' => $e->getMessage(), // Opsional: untuk debugging
            ], 500);
        }
    }

    /**
     * Helper method to check stock availability for items in the cart.
     * ... (method checkStockForCheckout tetap sama) ...
     */
    private function checkStockForCheckout($cartItems, bool $lock = false): void
    {
        $errors = [];
        foreach ($cartItems as $item) {
            $query = ProductSizeColor::where('product_id', $item->product_id)
                ->where('size_id', $item->size_id)
                ->where('color_id', $item->color_id);

            if ($lock) {
                // Warning: Locking here is generally NOT recommended for modal flow.
                // Stock check before payment is advisory, the real check + decrement
                // happens in the notification handler with lock.
                $query->lockForUpdate();
            }

            $stock = $query->first();

            if (!$stock || $stock->stock < $item->quantity) {
                $productName = $item->product->name;
                $variation = ($item->size->name ?? '') . ($item->size && $item->color ? '/' : '') . ($item->color->name ?? '');
                $availableStock = $stock ? $stock->stock : 0;
                $errors["item_{$item->id}"] = "Stok tidak mencukupi untuk {$productName} ({$variation}). Tersedia hanya {$availableStock} pcs.";
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
