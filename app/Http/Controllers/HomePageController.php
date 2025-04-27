<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSizeColor;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with('stockCombinations.size', 'stockCombinations.color')
            ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // if ($request->header('Turbo-Frame') === 'products_frame') {
        //     return view('homepage._list', compact('products'));
        // }

        return view('homepage', compact('products'));
    }
    public function purchase(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Cari kombinasi stok
        $stock = ProductSizeColor::where([
            'product_id' => $product->id,
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
        ])->firstOrFail();

        // Validasi stok
        if ($stock->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Kurangi stok
        $stock->decrement('stock', $validated['quantity']);

        return back()->with('success', 'Pembelian berhasil!');
    }
    public function options(Product $product)
{
    $sizes = $product->stockCombinations->pluck('size')->unique('id')->values()->map(function($size) {
        return [
            'id' => $size->id,
            'name' => $size->name,
        ];
    });

    $colors = $product->stockCombinations->pluck('color')->unique('id')->values()->map(function($color) {
        return [
            'id' => $color->id,
            'name' => $color->name,
        ];
    });

    $maxStock = $product->stockCombinations->max('stock') ?? 0;

    return response()->json([
        'sizes' => $sizes,
        'colors' => $colors,
        'max_stock' => $maxStock,
    ]);
}
}
