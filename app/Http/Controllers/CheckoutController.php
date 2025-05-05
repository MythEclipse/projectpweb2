<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\ProductSizeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException; // Pastikan ini di-import
use Illuminate\Routing\Controller;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman checkout.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(): View | RedirectResponse
    {
        $cartItems = Auth::user()->cartItems()->with(['product', 'size', 'color'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        // Lakukan cek stok final sebelum menampilkan halaman checkout
        try {
             $this->checkStockForCheckout($cartItems);
        } catch (ValidationException $e) {
             // Jika stok tidak mencukupi, kembalikan user ke keranjang dengan error
             return redirect()->route('cart.index')
                 ->with('error', 'Beberapa item di keranjang memiliki stok tidak mencukupi. Silakan periksa kembali.');
        }


        $cartTotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        // Ambil alamat user jika ada, atau siapkan field kosong
        $userAddress = Auth::user()->address; // Asumsikan user punya relasi hasOne/hasMany ke Address Model

        // Anda mungkin perlu melewatkan opsi metode pembayaran/pengiriman di sini

        return view('checkout.index', compact('cartItems', 'cartTotal', 'userAddress'));
    }

    /**
     * Memproses data checkout dan membuat transaksi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with(['product'])->get(); // Load product to get price

        if ($cartItems->isEmpty()) {
             return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi data checkout (alamat pengiriman, metode pembayaran, dll.)
        $validated = $request->validate([
            // Validasi field alamat (sesuaikan dengan struktur Address Model Anda)
            'shipping_address' => 'required|string', // Atau field alamat detail lainnya
            // 'payment_method' => 'required|string', // Jika Anda punya pilihan metode pembayaran di form
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // --- Final Stock Check (Locked) ---
            $this->checkStockForCheckout($cartItems, true); // true for locking

            // --- Proses Pengurangan Stok dan Pembuatan Transaksi ---
            foreach ($cartItems as $item) {
                 // Temukan ProductSizeColor dengan lock for update
                 $stock = ProductSizeColor::where('product_id', $item->product_id)
                     ->where('size_id', $item->size_id)
                     ->where('color_id', $item->color_id)
                     ->lockForUpdate() // Lock baris ini saat transaksi berjalan
                     ->first();

                 // Ini seharusnya sudah dicheck di checkStockForCheckout, tapi double check
                 if (!$stock || $stock->stock < $item->quantity) {
                      // Rollback jika ada ketidaksesuaian stok terakhir
                      DB::rollBack();
                      throw ValidationException::withMessages(['cart' => 'Stok tidak mencukupi untuk item: ' . $item->product->name . ' (' . ($item->size->name ?? '') . '/' . ($item->color->name ?? '') . '). Stok tersedia ' . ($stock ? $stock->stock : 0)]);
                 }

                // Kurangi stok
                $stock->decrement('stock', $item->quantity);

                // Hapus ProductSizeColor jika stok habis
                if ($stock->stock <= 0) {
                    $stock->delete();
                }

                // Buat record transaksi untuk item ini
                Transaction::create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'size_id' => $item->size_id,
                    'color_id' => $item->color_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price, // Ambil harga dari produk saat ini
                    'total' => $item->quantity * $item->product->price,
                    'status' => 'pending', // Status awal
                    'payment_method' => $validated['payment_method'] ?? null, // Ambil dari validasi atau default
                    'payment_status' => 'unpaid', // Status awal pembayaran
                    'shipping_address' => $validated['shipping_address'], // Ambil dari validasi
                    'tracking_number' => null,
                    'shipping_status' => 'not_shipped', // Status awal pengiriman
                    'notes' => $validated['notes'] ?? null,
                ]);
            }

            // Setelah semua transaksi berhasil dibuat, hapus item dari keranjang
            $user->cartItems()->delete();

            DB::commit();

            // Redirect ke halaman sukses atau order summary
            return redirect()->route('order.success')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Checkout Stock Validation Failed:', [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            return redirect()->route('checkout.index')
                             ->withInput($request->except('password'))
                             ->withErrors($e->errors())
                             ->with('error', 'Checkout gagal: ' . $e->getMessage()); // Menampilkan pesan error spesifik stok
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout Processing Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            return redirect()->route('checkout.index')
                             ->withInput($request->except('password'))
                             ->with('error', 'Terjadi kesalahan internal saat memproses checkout. Silakan coba lagi nanti.');
        }
    }

    /**
     * Helper method to check stock availability for items in the cart.
     * Can optionally apply a pessimistic lock.
     *
     * @param  \Illuminate\Support\Collection $cartItems
     * @param  bool $lock Whether to apply lockForUpdate
     * @return void
     * @throws \Illuminate\Validation\ValidationException If stock is insufficient
     */
    private function checkStockForCheckout($cartItems, bool $lock = false): void
    {
        $errors = [];
        foreach ($cartItems as $item) {
            $query = ProductSizeColor::where('product_id', $item->product_id)
                ->where('size_id', $item->size_id)
                ->where('color_id', $item->color_id);

            if ($lock) {
                $query->lockForUpdate(); // Apply lock during the final check within the transaction
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
            // Jika ada error, throw ValidationException untuk memicu rollback atau redirect with errors
            throw ValidationException::withMessages($errors);
        }
    }

    // Anda mungkin perlu method lain untuk menampilkan halaman sukses order, dll.
}
