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
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with('stockCombinations.size', 'stockCombinations.color')
            ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        if ($request->header('Turbo-Frame') === 'products_frame') {
            return view('admin.products._list', compact('products'));
        }

        return view('admin.products.home', compact('products'));
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
            'stocks.*'    => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create(Arr::only($validated, ['name', 'description', 'price', 'image']));

        foreach ($request->stocks as $key => $qty) {
            if ((int) $qty > 0) {
                [$sizeId, $colorId] = explode('-', $key);
                ProductSizeColor::create([
                    'product_id' => $product->id,
                    'size_id'    => $sizeId,
                    'color_id'   => $colorId,
                    'stock'      => $qty,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        $product->load('stockCombinations.size', 'stockCombinations.color');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('stockCombinations.size', 'stockCombinations.color');
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
            'stocks.*'    => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update(Arr::only($validated, ['name', 'description', 'price', 'image']));

        // Clear old stock combinations
        $product->stockCombinations()->delete();

        // Save new stock combinations
        foreach ($request->stocks as $key => $qty) {
            if ((int) $qty > 0) {
                [$sizeId, $colorId] = explode('-', $key);
                ProductSizeColor::create([
                    'product_id' => $product->id,
                    'size_id'    => $sizeId,
                    'color_id'   => $colorId,
                    'stock'      => $qty,
                ]);
            }
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
        public function apiGetProduct(Product $product)
    {
        $product->load('stockCombinations.size', 'stockCombinations.color');

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock_combinations' => $product->stockCombinations->map(function ($combination) {
                return [
                    'id' => $combination->id,
                    'size' => [
                        'id' => $combination->size->id,
                        'name' => $combination->size->name,
                    ],
                    'color' => [
                        'id' => $combination->color->id,
                        'name' => $combination->color->name,
                    ],
                    'stock' => $combination->stock,
                ];
            }),
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ]);
    }
}
