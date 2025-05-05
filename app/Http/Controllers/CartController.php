<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\ProductSizeColor; // Import model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Illuminate\Routing\Controller;

class CartController extends Controller
{
    // Middleware untuk memastikan hanya user terautentikasi yang bisa akses
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan isi keranjang pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $cartItems = Auth::user()->cartItems()->with(['product', 'size', 'color'])->get();

        // Anda mungkin perlu melakukan perhitungan total di sini atau di view
        $cartTotal = $cartItems->sum(function($item) {
            // Ambil harga produk. Jika ada diskon di masa depan, hitung di sini.
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Menambahkan produk (dengan variasi) ke keranjang.
     * Endpoint yang dipanggil dari form di halaman detail produk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'size_id' => 'nullable|exists:sizes,id', // Allow nullable if product has no size options
            'color_id' => 'nullable|exists:colors,id', // Allow nullable if product has no color options
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $quantity = $validated['quantity'];
        $sizeId = $validated['size_id'] ?? null;
        $colorId = $validated['color_id'] ?? null;

        // Cek ketersediaan stok untuk kombinasi yang dipilih
        $stockCombination = ProductSizeColor::where('product_id', $product->id)
            ->where('size_id', $sizeId)
            ->where('color_id', $colorId)
            ->first();

        // Jika kombinasi tidak ditemukan atau stok habis
        if (!$stockCombination || $stockCombination->stock <= 0) {
            // Jika kombinasi ada tapi stok 0
            if ($stockCombination && $stockCombination->stock <= 0) {
                 // Redirect ke halaman produk dengan error spesifik stok habis
                 return redirect()->route('products.show', $product)
                         ->withInput($request->except('password'))
                         ->with('error', 'Stok untuk kombinasi ukuran dan warna ini habis.');
            } else {
                 // Redirect ke halaman produk dengan error kombinasi tidak tersedia
                 return redirect()->route('products.show', $product)
                         ->withInput($request->except('password'))
                         ->withErrors(['size_id' => 'Kombinasi ukuran dan warna ini tidak tersedia.', 'color_id' => ' ']);
            }
        }

        // Cek apakah item sudah ada di keranjang pengguna
        $cartItem = $user->cartItems()->where([
            'product_id' => $product->id,
            'size_id' => $sizeId,
            'color_id' => $colorId,
        ])->first();

        if ($cartItem) {
            // Item sudah ada, tambahkan kuantitas (setelah cek stok gabungan)
            $newQuantity = $cartItem->quantity + $quantity;

            if ($stockCombination->stock < $newQuantity) {
                 // Redirect ke halaman produk dengan error karena melebihi stok
                 return redirect()->route('products.show', $product)
                         ->withInput($request->except('password'))
                         ->with('error', "Gagal menambahkan ke keranjang. Total kuantitas ({$newQuantity}) melebihi stok yang tersedia ({$stockCombination->stock}).");
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            $message = 'Kuantitas item di keranjang berhasil diperbarui.';

        } else {
            // Item belum ada, buat item baru (setelah cek stok awal)
             if ($stockCombination->stock < $quantity) {
                  // Ini seharusnya sudah ditangani di cek pertama, tapi jaga-jaga
                   return redirect()->route('products.show', $product)
                         ->withInput($request->except('password'))
                         ->with('error', "Gagal menambahkan ke keranjang. Kuantitas yang diminta ({$quantity}) melebihi stok yang tersedia ({$stockCombination->stock}).");
             }
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'size_id' => $sizeId,
                'color_id' => $colorId,
                'quantity' => $quantity,
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang.';
        }

        // Redirect, bisa ke halaman keranjang atau kembali ke halaman produk
        return redirect()->route('cart.index')->with('success', $message);
        // return redirect()->route('products.show', $product)->with('success', $message);
    }

    /**
     * Memperbarui kuantitas item di keranjang.
     * Ini akan dipanggil dari halaman keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Pastikan user yang login adalah pemilik item keranjang
        if ($cartItem->user_id !== Auth::id()) {
            abort(403); // Unauthorized
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $newQuantity = $validated['quantity'];

         // Cari kombinasi stok yang relevan untuk cek stok
         $stockCombination = ProductSizeColor::where('product_id', $cartItem->product_id)
             ->where('size_id', $cartItem->size_id)
             ->where('color_id', $cartItem->color_id)
             ->first();

         // Cek stok sebelum update
         if (!$stockCombination || $stockCombination->stock < $newQuantity) {
             $availableStock = $stockCombination ? $stockCombination->stock : 0;
              return redirect()->route('cart.index')
                        ->with('error', "Gagal memperbarui kuantitas. Stok tersedia hanya {$availableStock} pcs untuk item ini.");
         }

        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Kuantitas item di keranjang diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     * Ini akan dipanggil dari halaman keranjang.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        // Pastikan user yang login adalah pemilik item keranjang
        if ($cartItem->user_id !== Auth::id()) {
            abort(403); // Unauthorized
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
