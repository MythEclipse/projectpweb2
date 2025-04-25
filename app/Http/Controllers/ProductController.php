<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.home', compact('products'));
    }

    public function create()
    {
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.create', compact('sizes', 'colors'));
    }

    // Store method
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0|max:9999999999.99',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sizes' => 'required|array',
        'sizes.*' => 'exists:sizes,id',
        'stocks' => 'required|array',
        'stocks.*' => 'integer|min:0',
        'colors' => 'required|array',
        'colors.*' => 'exists:colors,id',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    // Buat produk
    $product = Product::create($validated);

    // Menyimpan stok untuk kombinasi size dan color
    $syncData = [];
    foreach ($request->sizes as $sizeId) {
        foreach ($request->colors as $colorId) {
            $stock = $request->stocks["$sizeId-$colorId"] ?? 0; // Mengambil stok per kombinasi ukuran dan warna
            $syncData[$sizeId][$colorId] = ['stock' => $stock];
        }
    }

    // Sinkronisasi stok ke pivot table product_size_color
    foreach ($syncData as $sizeId => $colors) {
        foreach ($colors as $colorId => $data) {
            $product->sizes()->attach($sizeId, ['color_id' => $colorId, 'stock' => $data['stock']]);
        }
    }

    return redirect()->route('products.index')->with('success', 'Product created.');
}


    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.edit', compact('product', 'sizes', 'colors'));
    }

   // Update method
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'required|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0|max:9999999999.99',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'sizes' => 'required|array',
        'sizes.*' => 'exists:sizes,id',
        'stocks' => 'required|array',
        'stocks.*' => 'integer|min:0',
        'colors' => 'required|array',
        'colors.*' => 'exists:colors,id',
    ]);

    if ($request->hasFile('image')) {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    // Update produk
    $product->update($validated);

    // Menyinkronkan stok untuk setiap kombinasi size dan color
    $syncData = [];
    foreach ($request->sizes as $sizeId) {
        foreach ($request->colors as $colorId) {
            $stock = $request->stocks["$sizeId-$colorId"] ?? 0;
            $syncData[$sizeId][$colorId] = ['stock' => $stock];
        }
    }

    // Sinkronisasi stok ke pivot table
    foreach ($syncData as $sizeId => $colors) {
        foreach ($colors as $colorId => $data) {
            $product->sizes()->updateExistingPivot($sizeId, ['color_id' => $colorId, 'stock' => $data['stock']]);
        }
    }

    return redirect()->route('products.index')->with('success', 'Product updated.');
}


    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
