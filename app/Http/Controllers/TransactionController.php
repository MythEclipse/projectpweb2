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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; // Import Log untuk error handling
use Illuminate\Support\Facades\Validator; // Import Validator untuk validasi manual

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
    public function quickUpdate(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            // ... (validation rules remain the same) ...
             'field' => ['required', 'string', Rule::in(['payment_status', 'shipping_status'])],
             'value' => ['required', function ($attribute, $value, $fail) use ($request) {
                 if ($request->input('field') === 'payment_status') {
                     if (!in_array($value, ['paid', 'unpaid'])) {
                         $fail('Nilai status pembayaran tidak valid.');
                     }
                 } elseif ($request->input('field') === 'shipping_status') {
                     if (!in_array($value, ['not_shipped', 'shipped', 'delivered'])) {
                         $fail('Nilai status pengiriman tidak valid.');
                     }
                 }
             }],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        $field = $request->input('field');
        $value = $request->input('value');

        try {
            $originalMainStatus = $transaction->status; // Store original status

            // --- Revised Status Logic ---

            // 1. Never automatically change status IF it's 'cancelled'
            if ($originalMainStatus === 'cancelled') {
                 // If cancelled, only update the specific field requested
                 $transaction->{$field} = $value;
                 $mainStatusChanged = false; // Main status deliberately not changed

                 // Optional: Depending on business rules, you might prevent *any* updates.
                 // If so, return early:
                 // return response()->json([
                 //     'success' => false, // Or true, depending if updating the field is allowed
                 //     'message' => 'Transaksi yang dibatalkan tidak dapat diubah.',
                 //     // ... potentially include current values ...
                 // ]);

            } else {
                 // 2. Apply the requested change to the specific field first (in memory)
                 $transaction->{$field} = $value;

                 // 3. Determine the NEW target main status based on CURRENT payment/shipping state
                 $newTargetStatus = 'pending'; // Default status if no other condition matches

                 if ($transaction->payment_status == 'paid') {
                     if ($transaction->shipping_status == 'delivered') {
                         $newTargetStatus = 'completed';
                     } else { // Paid, but not delivered (shipped or not_shipped)
                         $newTargetStatus = 'processing';
                     }
                 } else { // Payment is unpaid
                     $newTargetStatus = 'pending';
                 }

                 // 4. Update the main status field IF the calculated status is different
                 if ($newTargetStatus !== $originalMainStatus) {
                     $transaction->status = $newTargetStatus;
                     $mainStatusChanged = true;
                 } else {
                     $mainStatusChanged = false;
                 }
            }

            // 5. Save the transaction (persists changes to $field and potentially $status)
            $transaction->save();

            // --- End Revised Status Logic ---

            // Return the outcome, including the *final* main status
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'updated_field' => $field,
                'new_value' => $value,
                'main_status_updated' => $mainStatusChanged, // True if status actually changed
                'new_main_status' => $transaction->status   // The current status from the DB
            ]);

        } catch (\Exception $e) {
            Log::error("Error updating transaction {$transaction->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
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
        $products = Product::orderBy('name')->get();
        $sizes = Size::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        // $users = User::orderBy('name')->get(); // Jika diperlukan

        // Opsi status transaksi (masih diperlukan jika ada dropdown)
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        // Opsi status pengiriman (untuk radio button group)
        $shippingStatuses = ['not_shipped', 'shipped', 'delivered'];

        return view('admin.transactions.edit', compact(
            'transaction',
            'products',
            'sizes',
            'colors',
            // 'users',
            'statuses', // Masih dikirim jika status transaksi tetap dropdown
            'shippingStatuses' // Dikirim untuk radio button
        ));
    }

    /**
     * Memperbarui transaksi yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        // 1. Validasi input dasar
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            // Validasi untuk input status pengiriman (dari radio)
            'shipping_status' => ['required', Rule::in(['not_shipped', 'shipped', 'delivered'])],
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Validasi untuk input toggle payment status (opsional, tapi bagus untuk memastikan boolean atau 'on')
            // Kita akan proses nilainya di bawah, jadi validasi dasar bisa 'nullable' atau 'sometimes'
            'payment_toggle' => 'nullable|string|in:on', // Checkbox mengirim 'on' jika dicentang
        ]);

        // 2. Proses Nilai Toggle Status Pembayaran
        // Jika checkbox 'payment_toggle' dicentang (ada di request dan nilainya 'on'), statusnya 'paid'.
        // Jika tidak dicentang (tidak ada di request), statusnya 'unpaid'.
        // Kita tidak langsung memasukkan 'payment_toggle' ke $updateData
        $paymentStatus = $request->has('payment_toggle') && $request->input('payment_toggle') === 'on' ? 'paid' : 'unpaid';

        // !! PENTING: Periksa apakah status 'refunded' perlu ditangani secara terpisah.
        // Jika ya, toggle ini mungkin tidak cocok, atau perlu logika tambahan.
        // Asumsi saat ini: toggle hanya untuk 'paid' / 'unpaid'.
        // Jika status sebelumnya 'refunded', toggle ini akan menimpanya jadi 'paid' atau 'unpaid'.
        // Pertimbangkan jika ini perilaku yang diinginkan.

        // 3. Hitung ulang total
        $total = $validated['quantity'] * $validated['price'];

        // 4. Siapkan data untuk update
        // Gunakan $validated untuk field lain, tapi gunakan $paymentStatus yang sudah diproses
        $updateData = [
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'total' => $total,
            'status' => $validated['status'],
            'payment_status' => $paymentStatus, // Gunakan status pembayaran yang diproses
            'shipping_status' => $validated['shipping_status'], // Dari radio button
            'tracking_number' => $validated['tracking_number'],
            'notes' => $validated['notes'],
        ];

        // 5. Update transaksi
        $transaction->update($updateData);

        // 6. Redirect
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Memperbarui transaksi yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */


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
