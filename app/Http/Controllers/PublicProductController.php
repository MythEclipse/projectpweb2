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

class PublicProductController extends Controller // <<< NAMA CONTROLLER BARU
{
    /**
     * Display the specified public product details page.
     */
    public function show(Product $product): View // Route model binding by ID
    {
        // Eager load necessary relations
        $product->load(['stockCombinations.size', 'stockCombinations.color']);

        // Prepare data for dropdowns
        $availableSizes = $product->stockCombinations
            ->map(fn($sc) => $sc->size)->filter()->unique('id')->sortBy('name')->values();

        $availableColors = $product->stockCombinations
            ->map(fn($sc) => $sc->color)->filter()->unique('id')->sortBy('name')->values();

        // Return the PUBLIC view
        return view('products.show', compact('product', 'availableSizes', 'availableColors'));
    }

    /**
     * Process the public product purchase request submitted from the detail page.
     */
    public function processPurchase(Request $request, Product $product): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pembelian.');
        }

        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $stock = ProductSizeColor::where([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ])->lockForUpdate()->first();

            if (!$stock) {
                DB::rollBack();
                return redirect()->route('products.show', $product)->with('error', 'Kombinasi ukuran dan warna tidak tersedia.');
            }

            if ($stock->stock < $validated['quantity']) {
                DB::rollBack();
                return redirect()->route('products.show', $product)
                                 ->withInput($request->only(['size_id', 'color_id', 'quantity']))
                                 ->with('error', "Stok tidak mencukupi. Tersedia: {$stock->stock} pcs.");
            }

            $price = $product->price;
            $total = $price * $validated['quantity'];

            $stock->decrement('stock', $validated['quantity']);

            Transaction::create([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('products.show', $product)->with('success', 'Produk berhasil ditambahkan!'); // Adjust msg

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Public Purchase Processing Error: ' . $e->getMessage(), [ /* context */ ]);
            return redirect()->route('products.show', $product)
                             ->withInput($request->only(['size_id', 'color_id', 'quantity']))
                             ->with('error', 'Terjadi kesalahan saat memproses pembelian.');
        }
    }
}
