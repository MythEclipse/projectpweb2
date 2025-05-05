<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\User; // Pastikan User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException; // Import ini untuk digunakan di Quick Update jika perlu

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
        $users = User::orderBy('name')->get(); // Admin kemungkinan perlu memilih user

        // Opsi status transaksi
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        // Opsi status pembayaran
        $paymentStatuses = ['unpaid', 'paid', 'refunded']; // Tambahkan status 'refunded' jika ada
        // Opsi status pengiriman
        $shippingStatuses = ['not_shipped', 'shipped', 'delivered'];

        // Pastikan view 'admin.transactions.create' ada
        return view('admin.transactions.create', compact(
            'products',
            'sizes',
            'colors',
            'users',
            'statuses',
            'paymentStatuses',
            'shippingStatuses'
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
        // Validasi input dari form create, termasuk field-field tambahan
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id', // Admin memilih user
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'payment_method' => 'nullable|string|max:255', // Bisa null saat dibuat
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'refunded'])],
            'shipping_address' => 'nullable|string', // Bisa null saat dibuat (mungkin diisi belakangan)
            'tracking_number' => 'nullable|string|max:255', // Default null saat create
            'shipping_status' => ['required', Rule::in(['not_shipped', 'shipped', 'delivered'])],
            'notes' => 'nullable|string',
        ]);

        // Hitung total berdasarkan input yang valid
        $total = $validated['quantity'] * $validated['price'];

        // Buat transaksi baru dengan SEMUA field yang diminta
        Transaction::create([
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'total' => $total,
            'user_id' => $validated['user_id'], // Ambil dari input admin
            'status' => $validated['status'], // Ambil dari input admin
            'payment_method' => $validated['payment_method'], 
            'payment_status' => $validated['payment_status'], // Ambil dari input admin
            'shipping_address' => $validated['shipping_address'], 
            'tracking_number' => $validated['tracking_number'], 
            'shipping_status' => $validated['shipping_status'], // Ambil dari input admin
            'notes' => $validated['notes'] ?? null, 

            // id, created_at, updated_at biasanya otomatis.
        ]);

        // Redirect ke halaman index admin dengan pesan sukses
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    /**
     * Handle quick updates for specific transaction fields (like payment or shipping status).
     * This is likely an AJAX endpoint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction (Route Model Binding)
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(Request $request, Transaction $transaction): JsonResponse
    {
        $validator = Validator::make($request->all(), [
             'field' => ['required', 'string', Rule::in(['payment_status', 'shipping_status'])], // Hanya izinkan update 2 field ini
             'value' => ['required', function ($attribute, $value, $fail) use ($request) {
                 // Validasi nilai berdasarkan field yang diupdate
                 if ($request->input('field') === 'payment_status') {
                     if (!in_array($value, ['unpaid', 'paid', 'refunded'])) { // Tambahkan 'refunded'
                         $fail('Nilai status pembayaran tidak valid.');
                     }
                 } elseif ($request->input('field') === 'shipping_status') {
                     if (!in_array($value, ['not_shipped', 'shipped', 'delivered'])) {
                         $fail('Nilai status pengiriman tidak valid.');
                     }
                 }
                 // Tambahkan validasi untuk field lain jika quickUpdate diperluas
             }],
        ]);

        if ($validator->fails()) {
             // Return JSON response for validation errors
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        $field = $request->input('field');
        $value = $request->input('value');

        try {
            // Store original status before potential change
            $originalMainStatus = $transaction->status;
            $mainStatusChanged = false;

            // --- Revised Status Logic (from previous iteration, keeps 'cancelled' untouched) ---

            // Only proceed with calculated status update if the transaction is not cancelled
            if ($originalMainStatus !== 'cancelled') {
                // Apply the requested change to the specific field (in memory)
                $transaction->{$field} = $value;

                // Determine the NEW target main status based on the CURRENT state of payment/shipping
                $newTargetStatus = 'pending'; // Default

                if ($transaction->payment_status === 'paid') {
                    if ($transaction->shipping_status === 'delivered') {
                        $newTargetStatus = 'completed';
                    } elseif ($transaction->shipping_status === 'shipped') {
                         $newTargetStatus = 'processing'; // Atau status lain yang sesuai setelah dikirim
                    } else { // Paid, but not shipped (not_shipped)
                        $newTargetStatus = 'processing'; // Masih diproses sebelum dikirim
                    }
                } elseif ($transaction->payment_status === 'refunded') {
                    $newTargetStatus = 'cancelled'; // Jika refund, anggap cancelled (sesuaikan logika bisnis Anda)
                }
                // Jika payment_status 'unpaid', status tetap 'pending' (default)

                // Update the main status field IF the calculated status is different AND it's not cancelled
                if ($newTargetStatus !== $originalMainStatus) {
                    $transaction->status = $newTargetStatus;
                    $mainStatusChanged = true;
                }
            } else {
                 // If transaction is cancelled, just update the specific field IF it's allowed
                 // (Current logic allows updating payment/shipping status even if main is cancelled)
                 $transaction->{$field} = $value;
                 // mainStatusChanged remains false
            }

            // Save the transaction (persists changes to the field and potentially the main status)
            $transaction->save();

            // --- End Revised Status Logic ---

            // Return success response with updated information
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'updated_field' => $field,
                'new_value' => $value,
                'main_status_updated' => $mainStatusChanged, // True if main status actually changed
                'new_main_status' => $transaction->status   // The current status from the DB
            ]);

        } catch (\Exception $e) {
            // Log the error
            Log::error("Error quick updating transaction {$transaction->id} field {$field}: " . $e->getMessage(), ['exception' => $e]);

            // Return JSON error response
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server saat memperbarui status.'], 500);
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
        // Load relasi jika belum ter-load
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
        $users = User::orderBy('name')->get(); // Jika diperlukan

        // Opsi status transaksi (untuk dropdown)
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        // Opsi status pembayaran (untuk dropdown/radio)
        $paymentStatuses = ['unpaid', 'paid', 'refunded']; // Tambahkan status 'refunded'
        // Opsi status pengiriman (untuk dropdown/radio)
        $shippingStatuses = ['not_shipped', 'shipped', 'delivered'];

        return view('admin.transactions.edit', compact(
            'transaction',
            'products',
            'sizes',
            'colors',
            'users', // Pastikan ini ada di compact jika digunakan di view
            'statuses',
            'paymentStatuses', // Kirim opsi status pembayaran
            'shippingStatuses' // Kirim opsi status pengiriman
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
        // Validasi semua field yang bisa diubah di form edit
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id', // Admin memilih user
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'cancelled'])],
            'payment_method' => 'nullable|string|max:255',
            'payment_status' => ['required', Rule::in(['unpaid', 'paid', 'refunded'])], // Validasi status pembayaran
            'shipping_address' => 'nullable|string',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_status' => ['required', Rule::in(['not_shipped', 'shipped', 'delivered'])], // Validasi status pengiriman
            'notes' => 'nullable|string',
             // Remove 'payment_toggle' validation if using standard dropdown/radio for payment_status
        ]);

        // Hitung ulang total
        $total = $validated['quantity'] * $validated['price'];

        // Siapkan data untuk update, ambil semua dari $validated
        $updateData = [
            'product_id' => $validated['product_id'],
            'size_id' => $validated['size_id'],
            'color_id' => $validated['color_id'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'total' => $total,
            'user_id' => $validated['user_id'], // Ambil dari input
            'status' => $validated['status'], // Ambil dari input
            'payment_method' => $validated['payment_method'], // Ambil dari input
            'payment_status' => $validated['payment_status'], // Ambil dari input
            'shipping_address' => $validated['shipping_address'], // Ambil dari input
            'tracking_number' => $validated['tracking_number'], // Ambil dari input
            'shipping_status' => $validated['shipping_status'], // Ambil dari input
            'notes' => $validated['notes'], // Ambil dari input
        ];

        // Update transaksi
        $transaction->update($updateData);

        // Redirect
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
            Log::error("Error deleting transaction {$transaction->id}: " . $e->getMessage(), ['exception' => $e]);

            // Redirect ke halaman index admin dengan pesan error
            return redirect()->route('admin.transactions.index')
                ->with('error', 'Gagal menghapus transaksi. Mungkin terkait dengan data lain.');
        }
    }
}
