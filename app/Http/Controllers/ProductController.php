<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
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
        return view('admin.products.create', compact('sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'color' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'stocks' => 'required|array',
            'stocks.*' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Buat produk
        $product = Product::create($validated);

        // Hubungkan ukuran dan stok
        foreach ($request->sizes as $sizeId) {
            $stock = $request->stocks[$sizeId] ?? 0;
            $product->sizes()->attach($sizeId, ['stock' => $stock]);
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
        return view('admin.products.edit', compact('product', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:9999999999.99',
            'color' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'stocks' => 'required|array',
            'stocks.*' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update data produk
        $product->update($validated);

        // Sinkronisasi ukuran dan stok
        $syncData = [];
        foreach ($request->sizes as $sizeId) {
            $stock = $request->stocks[$sizeId] ?? 0;
            $syncData[$sizeId] = ['stock' => $stock];
        }
        $product->sizes()->sync($syncData);

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
