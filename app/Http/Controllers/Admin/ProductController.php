<?php


namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductSizeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage; // Diperlukan untuk fallback dan delete lokal
use Illuminate\Support\Facades\Http;      // Diperlukan untuk request API
use Illuminate\Http\Client\ConnectionException; // Untuk menangani error koneksi API
use Illuminate\Support\Facades\Log;      // Untuk logging error
use Illuminate\Support\Str;              // Diperlukan untuk cek http/https
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; // Import base Controller     // Untuk query database

class ProductController extends Controller
{
    // Definisikan URL API uploader
    private string $apiUploaderUrl = 'https://asepharyana.cloud/api/uploader';

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $productsQuery = Product::query()
            // Eager load relationships FOR DISPLAY after the query runs.
            // This `with` does NOT affect the sorting query itself.
            ->with([
                'stockCombinations' => function ($query) {
                    // Optionally order combinations within the eager load if needed for display
                    $query->orderBy('size_id')->orderBy('color_id');
                },
                'stockCombinations.size',
                'stockCombinations.color'
            ]);

        // Subquery to calculate total stock per product_id
        $stockAggregationSubQuery = ProductSizeColor::select(
            'product_id',
            DB::raw('SUM(stock) as aggregated_stock')
        )
            ->groupBy('product_id');

        // Perform a LEFT JOIN from products to the aggregated stock subquery
        $productsQuery->leftJoinSub(
            $stockAggregationSubQuery,
            'stock_summary', // Alias for the subquery result table
            function ($join) {
                $join->on('products.id', '=', 'stock_summary.product_id');
            }
        );

        // Select all product columns AND the calculated total stock.
        // Use COALESCE to ensure products with no stock records get 0, not NULL.
        $productsQuery->select(
            'products.*',
            DB::raw('COALESCE(stock_summary.aggregated_stock, 0) as total_stock')
        );

        // Apply search filter if present (searches columns in the 'products' table)
        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('products.name', 'like', '%' . $search . '%')
                    ->orWhere('products.description', 'like', '%' . $search . '%');
            });
        }

        // Apply Sorting:
        // 1. Primary Sort: Use a CASE statement. Assign 0 to items with stock, 1 to items without. Sort ASC.
        // 2. Secondary Sort: Within each group, sort by creation date descending.
        $productsQuery
            ->orderByRaw('CASE WHEN COALESCE(stock_summary.aggregated_stock, 0) > 0 THEN 0 ELSE 1 END ASC')
            ->orderBy('products.created_at', 'desc'); // Newest first within the stock groups

        // Paginate the results
        $products = $productsQuery->paginate(24) // Adjust pagination count as needed
            ->withQueryString(); // Keep search query in pagination links

        // --- Logging ---
        // Log the raw SQL query generated.
        Log::debug('Generated SQL Query:', ['query' => $productsQuery->toSql(), 'bindings' => $productsQuery->getBindings()]);

        // Log the calculated total_stock for the products on the current page.
        Log::debug('Products with total_stock:', $products->map(fn($p) => ['name' => $p->name, 'total_stock' => $p->total_stock])->all());
        // -------------

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.products.create', compact('sizes', 'colors'));
    }

    /**
     * Store a newly created product.
     * Tries API upload first (stores URL), falls back to local storage (stores path).
     */
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

        $imageValue = null; // Akan berisi URL dari API atau Path dari Storage

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadSuccess = false;

            // --- 1. Coba Upload ke API ---
            try {
                Log::info('Attempting API image upload for new product...');
                $response = Http::timeout(15)->attach( // Tambahkan timeout
                    'file', // Nama field di API
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post($this->apiUploaderUrl);

                if ($response->successful() && isset($response->json()['url']) && Str::startsWith($response->json()['url'], ['http://', 'https://'])) {
                    $imageValue = $response->json()['url']; // Simpan URL lengkap
                    $uploadSuccess = true;
                    Log::info('API image upload successful.', ['url' => $imageValue]);
                } else {
                    // API merespons, tapi tidak sukses atau format tidak sesuai
                    Log::error('API Upload Failed (Invalid Response or Format):', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'url_received' => $response->json()['url'] ?? 'N/A'
                    ]);
                }
            } catch (ConnectionException $e) {
                Log::error('API Connection Failed:', ['error' => $e->getMessage()]);
                // Biarkan uploadSuccess tetap false, akan fallback
            } catch (\Exception $e) {
                // Tangkap error lain (misal: file_get_contents gagal, timeout Http, dll)
                Log::error('Generic API Upload Error or HTTP Client Error:', ['error' => $e->getMessage()]);
                // Biarkan uploadSuccess tetap false, akan fallback
            }

            // --- 2. Fallback ke Storage Lokal jika API gagal ---
            if (!$uploadSuccess) {
                try {
                    Log::warning('API upload failed, falling back to local storage...');
                    // Simpan ke disk 'public' dalam folder 'products'
                    $imageValue = $file->store('products', 'public'); // Simpan path relatif
                    if ($imageValue) {
                        $uploadSuccess = true; // Tandai sukses (di lokal)
                        Log::info('Local storage upload successful.', ['path' => $imageValue]);
                    } else {
                        Log::error('Local storage upload failed to return a path.');
                        // uploadSuccess tetap false
                    }
                } catch (\Exception $e) {
                    Log::error('Local Storage Upload Failed Exception:', ['error' => $e->getMessage()]);
                    // Jika lokal juga gagal, kembalikan error ke user
                    return back()->withErrors(['image' => 'Gagal menyimpan gambar baik ke API maupun penyimpanan lokal. Silakan coba lagi.'])->withInput();
                }
            }

            // Jika setelah fallback pun masih gagal (misalnya karena permission folder storage)
            if (!$uploadSuccess) {
                return back()->withErrors(['image' => 'Tidak dapat menyimpan gambar. Pastikan API atau penyimpanan lokal berfungsi.'])->withInput();
            }
        }

        // Siapkan data produk
        $productData = Arr::only($validated, ['name', 'description', 'price']);
        $productData['image'] = $imageValue;   // Simpan URL atau Path

        $product = Product::create($productData);

        // Simpan stock combinations
        foreach ($request->stocks as $key => $qty) {
            // Pastikan qty adalah integer > 0
            if (is_numeric($qty) && (int) $qty > 0) {
                // Pastikan key valid (mengandung '-')
                if (strpos($key, '-') !== false) {
                    [$sizeId, $colorId] = explode('-', $key);
                    // Validasi tambahan jika perlu (cek apakah sizeId dan colorId ada)
                    if (Size::find($sizeId) && Color::find($colorId)) {
                        ProductSizeColor::create([
                            'product_id' => $product->id,
                            'size_id'    => $sizeId,
                            'color_id'   => $colorId,
                            'stock'      => (int) $qty,
                        ]);
                    } else {
                        Log::warning('Invalid size_id or color_id skipped during stock creation.', ['key' => $key]);
                    }
                } else {
                    Log::warning('Invalid stock key format skipped.', ['key' => $key]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('stockCombinations.size', 'stockCombinations.color');

        // Ambil daftar unik ukuran dan warna dari stockCombinations yang ada
        // Filter() penting untuk menghapus entri null jika size_id atau color_id nya null
        $availableSizes = $product->stockCombinations
            ->pluck('size') // Ambil objek Size dari relasi
            ->filter()     // Filter yang null
            ->unique('id') // Ambil yang unik berdasarkan ID
            ->sortBy('name'); // Urutkan sesuai nama

        $availableColors = $product->stockCombinations
            ->pluck('color') // Ambil objek Color dari relasi
            ->filter()       // Filter yang null
            ->unique('id')   // Ambil yang unik berdasarkan ID
            ->sortBy('name'); // Urutkan sesuai nama

        // Tentukan di sini apakah produk ini punya variasi (ukuran atau warna)
        // Produk punya variasi jika ada lebih dari satu kombinasi, ATAU jika ada hanya satu kombinasi tapi size/color ID nya tidak null
        $hasVariations = $product->stockCombinations->count() > 1 ||
            ($product->stockCombinations->count() === 1 && ($product->stockCombinations->first()->size_id !== null || $product->stockCombinations->first()->color_id !== null));


        // Lewatkan product, availableSizes, availableColors, dan hasVariations ke view
        return view('admin.products.show', compact('product', 'availableSizes', 'availableColors', 'hasVariations'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load('stockCombinations.size', 'stockCombinations.color');
        $sizes = Size::all();
        $colors = Color::all();
        // Gunakan accessor $product->image_url di view 'admin.products.edit'
        return view('admin.products.edit', compact('product', 'sizes', 'colors'));
    }

    /**
     * Update the specified product.
     * Handles new image upload (API/local) and deletion of old image based on its type (URL/path).
     */
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

        $newImageValue = $product->image;      // Default ke gambar lama (URL atau Path)
        $oldImageValue = $product->image;      // Simpan nilai lama untuk penghapusan

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadSuccess = false;
            $attemptedUpload = true; // Tandai bahwa kita mencoba upload baru

            // --- 1. Coba Upload ke API (Gambar Baru) ---
            try {
                Log::info('Attempting API image upload for product update...', ['product_id' => $product->id]);
                $response = Http::timeout(15)->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post($this->apiUploaderUrl);

                if ($response->successful() && isset($response->json()['url']) && Str::startsWith($response->json()['url'], ['http://', 'https://'])) {
                    $newImageValue = $response->json()['url']; // URL baru
                    $uploadSuccess = true;
                    Log::info('API image upload successful (Update).', ['url' => $newImageValue]);
                } else {
                    Log::error('API Upload Failed (Update - Invalid Response or Format):', [
                        'product_id' => $product->id,
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'url_received' => $response->json()['url'] ?? 'N/A'
                    ]);
                }
            } catch (ConnectionException $e) {
                Log::error('API Connection Failed (Update):', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            } catch (\Exception $e) {
                Log::error('Generic API Upload Error or HTTP Client Error (Update):', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            }

            // --- 2. Fallback ke Storage Lokal jika API gagal (Gambar Baru) ---
            if (!$uploadSuccess) {
                try {
                    Log::warning('API upload failed (Update), falling back to local storage...', ['product_id' => $product->id]);
                    $newImageValue = $file->store('products', 'public'); // Path baru
                    if ($newImageValue) {
                        $uploadSuccess = true; // Sukses di lokal
                        Log::info('Local storage upload successful (Update).', ['path' => $newImageValue]);
                    } else {
                        Log::error('Local storage upload failed to return a path (Update).', ['product_id' => $product->id]);
                    }
                } catch (\Exception $e) {
                    Log::error('Local Storage Upload Failed Exception (Update):', ['product_id' => $product->id, 'error' => $e->getMessage()]);
                    // Jika upload baru gagal total, mungkin lebih baik hentikan atau beri tahu user
                    // Di sini kita biarkan $uploadSuccess false
                }
            }

            // Jika upload gambar baru (baik API maupun lokal) gagal, batalkan update gambar
            if (!$uploadSuccess) {
                // Kembalikan nilai gambar ke yang lama
                $newImageValue = $oldImageValue;
                $attemptedUpload = false; // Anggap tidak ada percobaan upload yang berhasil
                // Beri pesan error jika ingin
                // return back()->withErrors(['image' => 'Gagal menyimpan gambar baru. Gambar lama dipertahankan.'])->withInput();
                Log::error('Failed to upload new image via API or Local Storage during update.', ['product_id' => $product->id]);
                // Kita lanjutkan update field lain, tapi gambar tidak berubah
            }


            // --- 3. Hapus Gambar Lama jika upload baru berhasil *dan* gambar lama ada ---
            if ($attemptedUpload && $uploadSuccess && $oldImageValue && $oldImageValue !== $newImageValue) {
                // Cek apakah gambar lama adalah path lokal (tidak dimulai http/https)
                if (!Str::startsWith($oldImageValue, ['http://', 'https://'])) {
                    // Jika path lokal, coba hapus dari storage
                    if (Storage::disk('public')->exists($oldImageValue)) {
                        try {
                            Storage::disk('public')->delete($oldImageValue);
                            Log::info('Successfully deleted old local image during update.', ['path' => $oldImageValue]);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete old local image during update.', ['path' => $oldImageValue, 'error' => $e->getMessage()]);
                        }
                    } else {
                        Log::warning('Old local image path not found for deletion during update.', ['path' => $oldImageValue]);
                    }
                } else {
                    // Jika URL API, tidak lakukan apa-apa (atau mungkin panggil API delete jika ada)
                    Log::info('Old image was on API, not deleting from external source during update.', ['url' => $oldImageValue]);
                }
            }
        } // End of if ($request->hasFile('image'))

        // Siapkan data untuk diupdate
        $productData = Arr::only($validated, ['name', 'description', 'price']);
        // Hanya update 'image' jika ada gambar baru yang berhasil diupload
        if ($request->hasFile('image') && $uploadSuccess) {
            $productData['image'] = $newImageValue; // Simpan URL atau Path baru
        } else if (!$request->hasFile('image')) {
            // Jika tidak ada file baru di request, pastikan nilai image lama tetap tersimpan
            $productData['image'] = $oldImageValue;
        }
        // Jika ada file baru tapi upload gagal, $productData['image'] tidak di-set di sini,
        // sehingga update() akan menggunakan nilai yang sudah ada di $product (yaitu $oldImageValue).

        $product->update($productData);

        // Update stock combinations (Hapus semua lalu tambahkan lagi)
        $product->stockCombinations()->delete();
        foreach ($request->stocks as $key => $qty) {
            if (is_numeric($qty) && (int) $qty > 0) {
                if (strpos($key, '-') !== false) {
                    [$sizeId, $colorId] = explode('-', $key);
                    if (Size::find($sizeId) && Color::find($colorId)) {
                        ProductSizeColor::create([
                            'product_id' => $product->id,
                            'size_id'    => $sizeId,
                            'color_id'   => $colorId,
                            'stock'      => (int) $qty,
                        ]);
                    } else {
                        Log::warning('Invalid size_id or color_id skipped during stock update.', ['key' => $key, 'product_id' => $product->id]);
                    }
                } else {
                    Log::warning('Invalid stock key format skipped during update.', ['key' => $key, 'product_id' => $product->id]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product.
     * Deletes local image file if the 'image' field contains a path.
     */
    public function destroy(Product $product)
    {
        $imageValue = $product->image;

        // Cek apakah gambar adalah path lokal (bukan URL) dan ada
        if ($imageValue && !Str::startsWith($imageValue, ['http://', 'https://'])) {
            if (Storage::disk('public')->exists($imageValue)) {
                try {
                    Storage::disk('public')->delete($imageValue);
                    Log::info('Successfully deleted local image on product destroy.', ['path' => $imageValue, 'product_id' => $product->id]);
                } catch (\Exception $e) {
                    Log::error('Failed to delete local image on product destroy.', ['path' => $imageValue, 'product_id' => $product->id, 'error' => $e->getMessage()]);
                }
            } else {
                Log::warning('Local image path not found during product destroy.', ['path' => $imageValue, 'product_id' => $product->id]);
            }
        } elseif ($imageValue) {
            // Jika itu URL API
            Log::info('Product image is on API, not deleting from external source during destroy.', ['url' => $imageValue, 'product_id' => $product->id]);
            // Di sini Anda bisa menambahkan logika untuk memanggil API penghapusan jika ada
        }

        try {
            // Hapus relasi dan produk
            $product->stockCombinations()->delete(); // Hapus relasi dulu
            $product->delete(); // Hapus produk
            Log::info('Product and associated stock deleted successfully.', ['product_id' => $product->id]);
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete product or stock combinations.', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.products.index')->with('error', 'Gagal menghapus produk.');
        }
    }

    /**
     * API endpoint to get product details.
     * Uses the accessor to get the full image URL.
     */
    public function apiGetProduct(Product $product)
    {
        // Eager load relasi untuk efisiensi
        $product->load('stockCombinations.size', 'stockCombinations.color');

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image_url' => $product->image_url, // Menggunakan accessor dari Model
            'stock_combinations' => $product->stockCombinations->map(function ($combination) {
                return [
                    'id' => $combination->id,
                    'size' => $combination->size ? [
                        'id' => $combination->size->id,
                        'name' => $combination->size->name,
                    ] : null,
                    'color' => $combination->color ? [
                        'id' => $combination->color->id,
                        'name' => $combination->color->name,
                        // Optionally add color code if needed
                        // 'code' => $combination->color->code,
                    ] : null,
                    'stock' => $combination->stock,
                    'is_out_of_stock' => $combination->is_out_of_stock, // <-- Add this line
                ];
            }),
            'created_at' => $product->created_at ? $product->created_at->toIso8601String() : null,
            'updated_at' => $product->updated_at ? $product->updated_at->toIso8601String() : null,
        ]);
    }
}
