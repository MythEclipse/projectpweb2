<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSizeColor;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Import Str facade for slug generation

class HomePageController extends Controller
{
    /**
     * Display the product listing page.
     * Handles search functionality.
     * Provides necessary data for the view, including stock combinations and slugs.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = 24; // Atau ambil dari config/request jika perlu dinamis

        // Query products with necessary relations and search filter
        $productsQuery = Product::with([
                // Eager load combinations and their related size/color
                'stockCombinations' => function ($query) {
                    $query->with(['size', 'color'])->where('stock', '>', 0); // Optimasi: Hanya load kombinasi yang ada stok? (opsional)
                }
            ])
            ->when($search, function ($query, $search) {
                // Filter based on search term (name or maybe description)
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('description', 'like', '%' . $search . '%'); // Cari di deskripsi juga?
            })
            ->orderBy('created_at', 'desc'); // Urutkan berdasarkan terbaru

        // Paginate the results
        $products = $productsQuery->paginate($perPage)->withQueryString();

        // Add a 'slug' attribute to each product item within the paginator
        // This is needed for the form action URL in the view
        $products->through(function ($product) {
            $product->slug = Str::slug($product->name); // Generate slug from name
            // Pastikan stock_combinations, size, dan color tidak null sebelum diakses (jika ada potensi null)
             $product->stockCombinations->each(function ($combination) {
                $combination->size_name = $combination->size ? $combination->size->name : null;
                $combination->color_name = $combination->color ? $combination->color->name : null;
                $combination->color_code = $combination->color ? $combination->color->code : null;
                // Anda bisa menghapus relasi objek jika hanya butuh nama/kode untuk mengurangi ukuran data JSON
                // unset($combination->size);
                // unset($combination->color);
            });
            return $product;
        });


        // Return the view with the paginated products
        return view('homepage', compact('products', 'search')); // Kirim 'search' juga agar bisa ditampilkan di input
    }

    /**
     * Process the product purchase request.
     * Validates input, checks stock, performs DB transaction, and redirects.
     * Assumes route binding resolves Product by ID.
     */
    public function purchase(Request $request, Product $product) // Route binding by ID
    {
        // Pastikan user sudah login (jika belum ditangani oleh middleware)
        if (!Auth::check()) {
             return back()->with('error', 'Anda harus login untuk melakukan pembelian.');
             // Atau redirect ke halaman login:
             // return redirect()->route('login')->with('error', 'Anda harus login untuk melakukan pembelian.');
        }

        // Validasi input
        $validated = $request->validate([
            // product_id tidak perlu divalidasi karena sudah didapat dari route model binding ($product)
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id', // Mungkin ada produk tanpa warna? Sesuaikan jika perlu 'nullable|exists:...'
            'quantity' => 'required|integer|min:1', // Hapus max:1000, validasi max dilakukan terhadap stok
        ]);

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();

        try {
            // Cari kombinasi stok yang spesifik DAN lock row untuk mencegah race condition
            $stock = ProductSizeColor::where([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
            ])
            ->lockForUpdate() // Lock row ini selama transaksi
            ->first();

            // Periksa apakah kombinasi ditemukan
            if (!$stock) {
                DB::rollBack(); // Batalkan transaksi jika kombinasi tidak ada
                return back()->with('error', 'Kombinasi ukuran dan warna tidak tersedia untuk produk ini.');
            }

            // Validasi stok tersedia (dibandingkan dengan stok terbaru dari DB karena lockForUpdate)
            if ($stock->stock < $validated['quantity']) {
                DB::rollBack(); // Batalkan transaksi jika stok kurang
                return back()->with('error', "Stok tidak mencukupi. Tersedia: {$stock->stock} pcs.");
            }

            // Hitung harga total
            $price = $product->price; // Ambil harga dari produk utama
            $total = $price * $validated['quantity'];

            // Kurangi stok
            // decrement() aman digunakan dalam transaksi
            $stock->decrement('stock', $validated['quantity']);

            // Simpan data transaksi
            $transaction = Transaction::create([
                'product_id' => $product->id,
                'size_id' => $validated['size_id'],
                'color_id' => $validated['color_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'total' => $total,
                'user_id' => Auth::id(), // Ambil ID user yang sedang login
                // Tambahkan field lain jika perlu (status, payment_id, dll.)
            ]);

            // Jika semua operasi berhasil, commit transaksi
            DB::commit();

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            // Turbo akan menangkap redirect ini dan me-refresh frame yang sesuai
            return back()->with('success', 'Pembelian berhasil ditambahkan!');

        } catch (\Throwable $e) { // Tangkap semua jenis error/exception
            // Jika terjadi error, batalkan semua perubahan database
            DB::rollBack();

            // Log error untuk debugging
            Log::error('Purchase Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            // Redirect kembali dengan pesan error umum
            return back()->with('error', 'Terjadi kesalahan saat memproses pembelian. Silakan coba lagi nanti.');
        }
    }

    // Metode options() dihapus karena tidak lagi diperlukan
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
