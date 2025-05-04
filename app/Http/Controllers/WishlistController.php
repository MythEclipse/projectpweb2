<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Product; // Pastikan model Product di-import
class WishlistController extends Controller
{
      /**
     * Menampilkan halaman wishlist user.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $wishlistItems = $user->wishlistProducts()
                             // Penting: Eager load relasi yang dibutuhkan modal Beli
                             ->with(['stockCombinations.size', 'stockCombinations.color'])
                             ->latest('product_wishlist.created_at')
                             ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Menambah atau menghapus produk dari wishlist (toggle).
     */
    public function toggle(Product $product): RedirectResponse // Gunakan Route Model Binding
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah sudah ada di wishlist
        if ($user->hasInWishlist($product)) {
            // Jika ada, hapus (detach)
            $user->wishlistProducts()->detach($product->id);
            $message = __('Product removed from wishlist.');
        } else {
            // Jika tidak ada, tambahkan (attach)
            $user->wishlistProducts()->attach($product->id);
            $message = __('Product added to wishlist.');
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', $message);
    }
}
