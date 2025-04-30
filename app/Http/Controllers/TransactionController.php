<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Pastikan Rule di-import
use Illuminate\View\View; // Import View
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Eager load relasi untuk efisiensi dan urutkan terbaru
        $transactions = Transaction::with(['product', 'size', 'color', 'user'])
            ->latest() // Urutkan berdasarkan created_at descending
            ->paginate(15); // Gunakan paginasi untuk performa pada data besar

        // Pastikan view 'admin.transactions.index' ada
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan form untuk membuat transaksi baru.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        // Load data yang diperlukan untuk dropdown form, urutkan untuk usability
        $products = Product::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        // $users = User::orderBy('name')->get(); // Uncomment jika admin perlu memilih user saat create

        // Pastikan view 'admin.transactions.create' ada
        return view('admin.transactions.create', compact(
            'products',
            'sizes',
            'colors'
            // ,'users' // Uncomment jika admin perlu memilih user
        ));
    }

    /**
     * Menyimpan transaksi baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form create
        //    Pertimbangkan menggunakan Form Request (e.g., StoreTransactionRequest) untuk validasi yang lebih kompleks/reusable
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            // 'user_id' => 'required|exists:users,id', // Uncomment jika admin memilih user
            // 'payment_method' => 'required|string|max:255', // Contoh validasi field tambahan
            // 'shipping_address' => 'required|string',       // Contoh validasi field tambahan
            'notes' => 'nullable|string',                   // Contoh validasi field tambahan
        ]);

        // 2. Hitung total berdasarkan input yang valid
        $total = $validated['quantity'] * $validated['price'];

        // 3. Buat transaksi baru
        Transaction::create([
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'total' => $total,
            'notes' => $validated['notes'] ?? null, // Ambil notes jika ada

            // !! Penting: Saat ini user_id diisi dengan ID admin yang sedang login.
            // Jika admin seharusnya bisa membuat transaksi UNTUK user lain, uncomment validasi 'user_id'
            // dan ubah baris ini menjadi: 'user_id' => $validated['user_id'],
            'user_id' => Auth::id(),

            // Isi field lain dengan nilai default saat create
            'status' => 'pending', // Nilai default status
            'payment_status' => 'unpaid', // Nilai default status pembayaran
            'shipping_status' => 'not_shipped', // Nilai default status pengiriman
            // 'payment_method' => $validated['payment_method'] ?? null, // Contoh mengisi field tambahan
            // 'shipping_address' => $validated['shipping_address'] ?? null, // Contoh mengisi field tambahan
            'tracking_number' => null, // Default null saat create
        ]);

        // 4. Redirect ke halaman index admin dengan pesan sukses
        //    Pastikan nama route 'admin.transactions.index' sesuai definisi di routes/web.php
        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail transaksi spesifik.
     *
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function show(Transaction $transaction): View
    {
        // Load relasi jika belum ter-load (biasanya sudah oleh `with` jika datang dari index,
        // tapi aman untuk memastikan jika diakses langsung)
        $transaction->loadMissing(['product', 'size', 'color', 'user']);

        // Pastikan view 'admin.transactions.show' ada
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Menampilkan form untuk mengedit transaksi.
     *
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function edit(Transaction $transaction): View
    {
        // Load data yang dibutuhkan untuk dropdown form
        $products = Product::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        // $users = User::orderBy('name')->get(); // Uncomment jika admin bisa mengganti user transaksi

        // Definisikan opsi status yang mungkin untuk dropdown
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $shippingStatuses = ['not_shipped', 'shipped', 'delivered'];

        // Pastikan view 'admin.transactions.edit' ada
        return view('admin.transactions.edit', compact(
            'transaction',
            'products',
            'sizes',
            'colors',
            // 'users', // Uncomment jika user bisa diganti
            'statuses',
            'paymentStatuses',
            'shippingStatuses'
        ));
    }

    /**
     * Memperbarui transaksi yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // 1. Validasi input dari form edit
        //    Pertimbangkan menggunakan Form Request (e.g., UpdateTransactionRequest)
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            // 'user_id' => 'required|exists:users,id', // Uncomment jika user bisa diubah

            // Validasi field status dan lainnya yang bisa diubah admin
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'refunded'])],
            'shipping_status' => ['required', Rule::in(['not_shipped', 'shipped', 'delivered'])],
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // 2. Hitung ulang total jika quantity atau price berubah
        $total = $validated['quantity'] * $validated['price'];

        // 3. Update data transaksi dengan data yang valid + total baru
        //    User ID biasanya tidak diubah saat update transaksi yang sudah ada.
        $transaction->update(array_merge($validated, ['total' => $total]));

        // 4. Redirect ke halaman index admin dengan pesan sukses
        //    Pastikan nama route 'admin.transactions.index' sesuai definisi di routes/web.php
        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi dari database.
     *
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        try {
            // Coba hapus transaksi
            $transaction->delete();

            // Redirect ke halaman index admin dengan pesan sukses
            return redirect()->route('admin.transactions.index')
                             ->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            // Tangani jika terjadi error saat menghapus (misal: relasi database)
            // Log error untuk debugging: \Log::error("Error deleting transaction {$transaction->id}: " . $e->getMessage());

            // Redirect ke halaman index admin dengan pesan error
            return redirect()->route('admin.transactions.index')
                             ->with('error', 'Gagal menghapus transaksi. Mungkin terkait dengan data lain.'); // Pesan lebih umum
        }
    }
}
