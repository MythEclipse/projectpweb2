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
        $product->load(['stockCombinations.size', 'stockCombinations.color']);

        $availableSizes = $product->stockCombinations
            ->map(fn($sc) => $sc->size)->filter()->unique('id')->sortBy('name')->values();

        $availableColors = $product->stockCombinations
            ->map(fn($sc) => $sc->color)->filter()->unique('id')->sortBy('name')->values();

        return view('products.show', compact('product', 'availableSizes', 'availableColors'));
    }

    /**
     * Process the public product purchase request submitted from the detail page.
     * Handles validation, stock checking within a transaction, and redirection.
     *
     * @param Request $request The incoming request.
     * @param Product $product The product instance resolved by route model binding.
     * @return RedirectResponse Redirects back to the product page with status message.
     */
    public function processPurchase(Request $request, Product $product): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pembelian.');
        }

        // Server-side validation remains crucial
        $validated = $request->validate([
            'size_id' => 'required|integer|exists:sizes,id',
            'color_id' => 'required|integer|exists:colors,id',
            'quantity' => 'required|integer|min:1', // Validasi dasar tetap diperlukan
        ]);

        DB::beginTransaction();
        try {
            $stock = ProductSizeColor::where([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ])
            ->lockForUpdate()
            ->first();

            // Check if combination exists
            if (!$stock) {
                DB::rollBack();
                throw ValidationException::withMessages([
                     'size_id' => 'Kombinasi ukuran dan warna ini tidak tersedia.',
                     'color_id' => ' '
                ]);
            }

            // Check stock availability against validated quantity
            if ($stock->stock < $validated['quantity']) {
                DB::rollBack();
                 throw ValidationException::withMessages([
                    'quantity' => "Stok tidak mencukupi. Tersedia hanya {$stock->stock} pcs."
                 ]);
            }

            // Calculate price and total
            $price = $product->price;
            $total = $price * $validated['quantity'];

            // Decrement stock
            $stock->decrement('stock', $validated['quantity']);

            // Create transaction
            Transaction::create([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            // Commit transaction
            DB::commit();

            // Redirect with success
            return redirect()->route('products.show', $product)
                             ->with('success', 'Produk berhasil ditambahkan ke transaksi Anda!');

        } catch (ValidationException $e) {
            // Handle validation errors from stock check or missing combo
             DB::rollBack();
             Log::warning('Purchase Validation Failed:', ['product_id' => $product->id, 'user_id' => Auth::id(), 'errors' => $e->errors()]);
             return redirect()->route('products.show', $product)
                              ->withInput()
                              ->withErrors($e->errors());

        } catch (\Throwable $e) {
            // Handle other errors
            DB::rollBack();
            Log::error('Public Purchase Processing Error: ' . $e->getMessage(), [/* context */]);
            return redirect()->route('products.show', $product)
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan internal saat memproses pembelian. Silakan coba lagi.');
        }
    }
}
