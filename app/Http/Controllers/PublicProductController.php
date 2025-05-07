<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSizeColor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException; // Import untuk error detail
use Illuminate\Http\JsonResponse; // Import untuk JsonResponse

class PublicProductController extends Controller
{
    /**
     * Display the specified public product details page.
     *
     * @param Product $product The product instance resolved by route model binding.
     * @return View The view for the product detail page.
     */
    public function show(Product $product)
    {
        // Eager load relasi yang dibutuhkan
        $product->load(['stockCombinations.size', 'stockCombinations.color']);

        // Ambil ukuran unik yang ada di stockCombinations untuk produk ini
        $distinctSizes = $product->stockCombinations
            ->map(fn($sc) => $sc->size) // Ambil objek Size dari setiap kombinasi
            ->filter() // Hapus null jika ada kombinasi tanpa ukuran (misalnya produk hanya variasi warna)
            ->unique('id') // Ambil yang unik berdasarkan ID
            ->sortBy('name') // Urutkan (opsional, bisa juga berdasarkan order_column jika ada)
            ->values(); // Reset keys collection

        // Ambil warna unik yang ada di stockCombinations untuk produk ini
        $distinctColors = $product->stockCombinations
            ->map(fn($sc) => $sc->color) // Ambil objek Color dari setiap kombinasi
            ->filter() // Hapus null jika ada kombinasi tanpa warna
            ->unique('id') // Ambil yang unik berdasarkan ID
            ->sortBy('name') // Urutkan
            ->values(); // Reset keys collection

        // Tentukan apakah produk ini secara keseluruhan punya variasi (ukuran atau warna)
        // Ini untuk @if($hasVariations) di Blade yang membungkus bagian pilihan variasi
        $hasOverallVariations = $distinctSizes->isNotEmpty() || $distinctColors->isNotEmpty();

        return view('products.show', [
            'product' => $product,
            'hasVariations' => $hasOverallVariations, // Untuk blok @if utama variasi
            'availableSizes' => $distinctSizes,     // Untuk dropdown & data Alpine
            'availableColors' => $distinctColors,   // Untuk dropdown & data Alpine
        ]);
    }
    public function getStockCombinations(Product $product): JsonResponse
    {
        // Load only the necessary fields from stockCombinations
        $stockCombinations = $product->stockCombinations()
                                     ->select('size_id', 'color_id', 'stock') // Select hanya kolom yang dibutuhkan
                                     ->get();

        // Return as JSON
        return response()->json($stockCombinations);
    }

}
