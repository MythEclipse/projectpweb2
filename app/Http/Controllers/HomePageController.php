<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSizeColor;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        return view('homepage', compact('products'));
    }

    public function purchase(Request $request, Product $product)
    {
        // Validasi input
        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1|max:1000',
        ]);

        // Cari kombinasi stok
        $stock = ProductSizeColor::where([
            'product_id' => $product->id,
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
        ])->first();

        if (!$stock) {
            return back()->with('error', 'Kombinasi ukuran dan warna tidak ditemukan.');
        }

        // Validasi stok tersedia
        if ($stock->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        // Hitung harga total
        $price = $product->price;
        $total = $price * $validated['quantity'];

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Kurangi stok
            $stock->decrement('stock', $validated['quantity']);

            // Simpan transaksi
            $transaction = Transaction::create([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'total' => $total,
                'user_id' => Auth::id(),
            ]);

            // Commit database
            DB::commit();

            return back()->with('success', 'Pembelian berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan pembelian. Coba lagi.');
        }
    }


    public function options(Product $product)
    {
        $sizes = $product->stockCombinations->pluck('size')->unique('id')->values()->map(function ($size) {
            return [
                'id' => $size->id,
                'name' => $size->name,
            ];
        });

        $colors = $product->stockCombinations->pluck('color')->unique('id')->values()->map(function ($color) {
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
