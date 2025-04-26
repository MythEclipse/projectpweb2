<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductSizeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0|max:9999999999.99',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes'       => 'required|array',
            'sizes.*'     => 'exists:sizes,id',
            'stocks'      => 'required|array',
            'stocks.*'    => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $productData = Arr::only($validated, ['name', 'description', 'price', 'image']);
        $product = Product::create($productData);

        // Save only stocks > 0
        foreach ($request->stocks as $key => $qty) {
            if ((int) $qty <= 0) {
                continue;
            }
            list($sizeId, $colorId) = explode('-', $key);
            ProductSizeColor::create([
                'product_id' => $product->id,
                'size_id'    => $sizeId,
                'color_id'   => $colorId,
                'stock'      => $qty,
            ]);
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

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0|max:9999999999.99',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes'       => 'required|array',
            'sizes.*'     => 'exists:sizes,id',
            'stocks'      => 'required|array',
            'stocks.*'    => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $productData = Arr::only($validated, ['name', 'description', 'price', 'image']);
        $product->update($productData);

        // Clear existing
        $product->stockCombinations()->delete();

        // Save only stocks > 0
        foreach ($request->stocks as $key => $qty) {
            if ((int) $qty <= 0) {
                continue;
            }
            list($sizeId, $colorId) = explode('-', $key);
            ProductSizeColor::create([
                'product_id' => $product->id,
                'size_id'    => $sizeId,
                'color_id'   => $colorId,
                'stock'      => $qty,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->stockCombinations()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
