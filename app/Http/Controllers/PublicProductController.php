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
        // Eager load relasi yang dibutuhkan
        $product->load(['stockCombinations.size', 'stockCombinations.color']);

        // Ambil size yang tersedia (yang memiliki stockCombination)
        $availableSizes = $product->stockCombinations
            ->map(fn($sc) => $sc->size) // Ambil objek Size
            ->filter()                  // Hapus null jika ada relasi yang rusak (opsional)
            ->unique('id')              // Pastikan unik berdasarkan ID
            ->sortBy('name')            // Urutkan berdasarkan nama
            ->values();                 // Reset keys array

        // Ambil color yang tersedia (yang memiliki stockCombination)
        $availableColors = $product->stockCombinations
            ->map(fn($sc) => $sc->color) // Ambil objek Color
            ->filter()                   // Hapus null jika ada relasi yang rusak (opsional)
            ->unique('id')               // Pastikan unik berdasarkan ID
            ->sortBy('name')             // Urutkan berdasarkan nama
            ->values();                  // Reset keys array

        return view('products.show', compact('product', 'availableSizes', 'availableColors'));
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
