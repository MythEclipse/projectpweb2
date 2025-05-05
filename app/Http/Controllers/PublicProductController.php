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

    /**
     * Process the public product purchase request submitted from the detail page.
     * Handles validation, stock checking within a transaction, potential deletion on depletion, and redirection.
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
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Cari kombinasi stok dan kunci untuk update (mencegah race condition)
            $stock = ProductSizeColor::where([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ])
            ->lockForUpdate() // Kunci baris ini selama transaksi
            ->first();

            // 1. Periksa apakah kombinasi ukuran/warna ada
            if (!$stock) {
                // Rollback tidak diperlukan karena belum ada perubahan DB
                // DB::rollBack(); <--- Tidak perlu di sini
                throw ValidationException::withMessages([
                     'size_id' => 'Kombinasi ukuran dan warna ini tidak tersedia.',
                     'color_id' => ' ' // Pesan error dummy untuk field color jika diperlukan
                ]);
            }

            // 2. Periksa ketersediaan stok
            if ($stock->stock < $validated['quantity']) {
                // Rollback tidak diperlukan karena belum ada perubahan DB
                // DB::rollBack(); <--- Tidak perlu di sini
                 throw ValidationException::withMessages([
                    'quantity' => "Stok tidak mencukupi. Tersedia hanya {$stock->stock} pcs."
                 ]);
            }

            // Hitung harga dan total
            $price = $product->price; // Asumsi harga ada di model Product
            $total = $price * $validated['quantity'];

            // 3. Kurangi stok
            // Metode decrement langsung mengupdate DB dan objek $stock di memori
            $stock->decrement('stock', $validated['quantity']);

            // 4. **BARU: Periksa apakah stok habis setelah dikurangi**
            if ($stock->stock <= 0) {
                // Jika stok habis, hapus record ProductSizeColor ini
                Log::info('Stock depleted for ProductSizeColor. Deleting record.', [
                    'product_size_color_id' => $stock->id,
                    'product_id' => $product->id,
                    'size_id' => $validated['size_id'],
                    'color_id' => $validated['color_id'],
                ]);
                $stock->delete();
            }

            // 5. Buat record transaksi
            Transaction::create([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity'],
                'price' => $price, // Simpan harga satuan saat transaksi
                'total' => $total, // Simpan total saat transaksi
                'user_id' => Auth::id(),
                // 'status' => 'pending', // Mungkin perlu status transaksi?
            ]);

            // 6. Jika semua berhasil, commit transaksi
            DB::commit();

            // 7. Redirect dengan pesan sukses
            return redirect()->route('products.show', $product)
                             ->with('success', 'Produk berhasil ditambahkan ke transaksi Anda!');

        } catch (ValidationException $e) {
            // Tangani error validasi (dari stok check atau kombinasi tidak ada)
             DB::rollBack(); // Rollback jika terjadi error validasi SETELAH lockForUpdate
             Log::warning('Purchase Validation Failed:', [
                 'product_id' => $product->id,
                 'user_id' => Auth::id(),
                 'errors' => $e->errors()
                ]);
             // Kembalikan ke halaman produk dengan input lama dan pesan error
             return redirect()->route('products.show', $product)
                              ->withInput($request->except('password')) // Jangan kirim ulang password
                              ->withErrors($e->errors());

        } catch (\Throwable $e) {
            // Tangani error tak terduga lainnya
            DB::rollBack(); // Pastikan rollback jika ada error apapun dalam try block
            Log::error('Public Purchase Processing Error: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'exception' => $e // Log seluruh exception untuk detail
            ]);
            // Kembalikan ke halaman produk dengan input lama dan pesan error umum
            return redirect()->route('products.show', $product)
                             ->withInput($request->except('password'))
                             ->with('error', 'Terjadi kesalahan internal saat memproses pembelian. Silakan coba lagi nanti.');
        }
    }
}
