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
    public function show(Product $product): View
    {
        // Eager load relasi yang dibutuhkan: stockCombinations, dan relasi size/color di dalamnya
        $product->load(['stockCombinations.size', 'stockCombinations.color']);

        // Ambil size yang tersedia (yang memiliki stockCombination).
        // Gunakan map untuk mendapatkan objek size, filter untuk buang null, unique, dan sort.
        $availableSizes = $product->stockCombinations
            ->map(fn($sc) => $sc->size) // Ambil objek Size dari relasi 'size' di stock combination
            ->filter()                  // Filter/buang entri yang null (kombinasi tanpa size)
            ->unique('id')              // Ambil objek Size yang unik berdasarkan ID
            ->sortBy('name')            // Urutkan berdasarkan nama
            ->values();                 // Reset keys array agar menjadi list sederhana (0, 1, 2, ...)

        // Ambil color yang tersedia (yang memiliki stockCombination) dengan cara yang sama
        $availableColors = $product->stockCombinations
            ->map(fn($sc) => $sc->color) // Ambil objek Color dari relasi 'color' di stock combination
            ->filter()                   // Filter/buang entri yang null (kombinasi tanpa color)
            ->unique('id')               // Ambil objek Color yang unik berdasarkan ID
            ->sortBy('name')             // Urutkan berdasarkan nama
            ->values();                  // Reset keys array

        // Tentukan apakah produk ini punya variasi (ukuran atau warna)
        // Produk punya variasi jika ADA setidaknya satu kombinasi stok di mana size_id BUKAN null ATAU color_id BUKAN null.
        $hasVariations = $product->stockCombinations->some(fn($c) => $c->size_id !== null || $c->color_id !== null);


        // Lewatkan product, availableSizes, availableColors, dan hasVariations ke view
        return view('products.show', compact('product', 'availableSizes', 'availableColors', 'hasVariations'));
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
