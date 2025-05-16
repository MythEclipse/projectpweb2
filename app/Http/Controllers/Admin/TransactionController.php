<?php

namespace App\Http\Controllers\Admin; // Ensure this is in the Admin namespace

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction; // Now represents an Order Item (admin's detailed view)
use App\Models\Order;       // Import the Order model (needed for relationships)
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\User; // Pastikan User di-import
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse; // Still needed for potential JSON responses, but quickUpdate removed
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // Needed for recalculating order total on item delete

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua ITEM transaksi (item dalam pesanan) untuk admin.
     * Setiap baris akan menampilkan detail item DAN detail pesanan induknya.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Eager load relasi item (product, size, color) dan relasi ke Order + User di Order
        // Ini penting untuk performa agar tidak N+1 problem saat mengakses order/user/product/size/color di view
        $transactionItems = Transaction::with(['order.user', 'product', 'size', 'color'])
            ->latest() // Urutkan berdasarkan created_at descending (tanggal item dibuat/ditambah ke order)
            ->paginate(15); // Gunakan paginasi

        // Note: View ini menampilkan daftar ITEM, bukan daftar pesanan lengkap.
        // Data pesanan (Order) diakses melalui relasi $item->order->...
        // Anda perlu menyesuaikan view 'admin.transactions.index' untuk struktur kolom yang diminta.
        // The view you provided in the prompt already matches this structure.
        return view('admin.transactions.index', compact('transactionItems'));
    }

    /**
     * Menampilkan form untuk membuat transaksi baru.
     * CATATAN: Metode ini *tidak* sesuai dengan skema baru (membuat item tanpa order).
     * Manajemen order dan item seharusnya dilakukan via Admin\OrderController.
     * Metode ini dinonaktifkan.
     */
    // public function create(): View
    // {
    //      abort(404, "Method not available for creating individual transaction items directly in this admin view.");
    // }

    /**
     * Menyimpan transaksi baru ke database.
     * CATATAN: Metode ini *tidak* sesuai dengan skema baru (membuat item tanpa order).
     * Manajemen order dan item seharusnya dilakukan via Admin\OrderController.
     * Metode ini dinonaktifkan.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     abort(404, "Method not available for storing individual transaction items directly in this admin view.");
    // }

    /**
     * Handle quick updates for specific fields.
     * CATATAN: Status Pembayaran/Pengiriman/Utama sekarang ada di model ORDER.
     * Fungsi quick update ini harus dipindahkan ke Admin\OrderController dan menerima Order ID.
     * Dihapus dari TransactionController ini.
     */
    // public function quickUpdate(...) { /* ... */ }


    /**
     * Menampilkan detail ITEM transaksi spesifik untuk admin.
     *
     * @param  \App\Models\Transaction  $transactionItem (Route Model Binding - Renamed variable for clarity)
     * @return \Illuminate\View\View
     */
    public function show(Transaction $transactionItem): View
    {
        // Load relasi jika belum ter-load
        $transactionItem->loadMissing(['order.user', 'product', 'size', 'color']);

        // Pastikan view 'admin.transactions.show' ada dan disesuaikan untuk menampilkan item
        // Anda bisa menampilkan detail item ini, plus link ke order induknya ($transactionItem->order->id)
        return view('admin.transactions.show', compact('transactionItem'));
    }

    /**
     * Menampilkan form untuk mengedit ITEM transaksi.
     * CATATAN: Metode ini *tidak* sesuai dengan skema baru (mengedit item tanpa order context).
     * Manajemen item dalam order seharusnya dilakukan via Admin\OrderController.
     * Metode ini dinonaktifkan.
     */
    // public function edit(Transaction $transactionItem): View
    // {
    //     abort(404, "Method not available for editing individual transaction items directly in this admin view.");
    // }

    /**
     * Memperbarui ITEM transaksi yang ada di database.
     * CATATAN: Metode ini *tidak* sesuai dengan skema baru (memperbarui item tanpa order context).
     * Manajemen item dalam order seharusnya dilakukan via Admin\OrderController.
     * Metode ini dinonaktifkan.
     */
    // public function update(Request $request, Transaction $transactionItem): RedirectResponse
    // {
    //      abort(404, "Method not available for updating individual transaction items directly in this admin view.");
    // }


    /**
     * Menghapus ITEM transaksi dari database (admin action).
     * Setelah menghapus item, hitung ulang total pesanan induknya.
     *
     * @param  \App\Models\Transaction  $transactionItem (Route Model Binding - Renamed variable)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        try {
            // Simpan ID order induk sebelum item dihapus
            $orderId = $transaction->order_id;

            // Coba hapus item transaksi
            $transaction->delete();

            // Setelah item dihapus, hitung ulang total_amount untuk order induknya
            $parentOrder = Order::find($orderId);
            if ($parentOrder) {
                 // Gunakan DB::raw() untuk menghitung total quantity * price dari item yang tersisa
                 // Ensure 'quantity' and 'price' are column names in the 'transactions' table
                 $newTotalAmount = $parentOrder->transactionItems()->sum(DB::raw('quantity * price'));

                 // Perbarui total_amount di tabel orders
                 $parentOrder->update(['total_amount' => $newTotalAmount]);

                 // Opsional: Tangani kasus jika semua item dihapus dan total menjadi 0
                 if ($newTotalAmount == 0 && $parentOrder->transactionItems()->count() == 0) {
                    // Logika bisnis: apakah order dihapus? di-cancel?
                    // Example: If you want to delete the order too if no items remain
                    // $parentOrder->delete();
                    // Log::info("Order {$orderId} deleted as all items were removed.");
                 }
            }

            // Redirect ke halaman index item transaksi (this controller's index)
            // Alternatif: Redirect ke halaman detail Order induk ($orderId) jika Anda membuatnya di Admin\OrderController
            return redirect()->route('admin.transactions.index') // Keep redirecting here as per controller's purpose
                ->with('success', 'Item transaksi berhasil dihapus.'); // Ganti pesan

        } catch (\Exception $e) {
            // Tangani jika terjadi error saat menghapus
            Log::error("Error deleting transaction item {$transaction->id}: " . $e->getMessage(), ['exception' => $e]);

            // Redirect kembali dengan pesan error
            return redirect()->route('admin.transactions.index') // Keep redirecting here
                ->with('error', 'Gagal menghapus item transaksi. Mungkin terkait dengan data lain.');
        }
    }





    /**
     * Handle quick updates for specific fields on the *Order* model from the item list.
     * This method receives an Order ID via route binding.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order (Route Model Binding - Now binds to Order)
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(Request $request, Order $order): JsonResponse
    {
        // Log untuk debugging Route Model Binding
        Log::info('Quick Update received Order object', [
            'order_id_from_route' => request()->route('order'), // ID dari parameter route
            'order_object_id' => $order->id ?? 'null', // ID dari objek model
            'order_object_exists' => $order->exists, // Status exists
            // 'order_object_attributes' => $order->getAttributes(), // Opsional: lihat atribut
        ]);

        // Jika Route Model Binding gagal (model tidak ditemukan), $order->exists akan false
        // Laravel seharusnya melempar 404 secara default, tapi jika tidak,
        // kita bisa tambahkan cek eksplisit untuk memastikan model ditemukan.
        if (!$order->exists) {
             Log::error('Quick Update failed: Order model not found by Route Model Binding', ['order_id_from_route' => request()->route('order')]);
             return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }


        $validator = Validator::make($request->all(), [
             'field' => ['required', 'string', Rule::in(['shipping_status'])],
             'value' => ['required', function ($attribute, $value, $fail) use ($request) {
                 if ($request->input('field') === 'shipping_status') {
                     if (!in_array($value, ['not_shipped', 'shipped', 'delivered', 'returned'])) {
                         $fail('Nilai status pengiriman tidak valid.');
                     }
                 }
             }],
        ]);

        if ($validator->fails()) {
            Log::warning('Quick Update validation failed', ['errors' => $validator->errors(), 'order_id' => $order->id]);
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        $field = $request->input('field');
        $value = $request->input('value');

        DB::beginTransaction();
        try {
            // $order sudah merupakan model yang ditemukan oleh Route Model Binding
            // Tidak perlu re-fetch kecuali Anda butuh lockForUpdate() spesifik di sini
            // $order->lockForUpdate();

            $originalMainStatus = $order->status;
            $mainStatusChanged = false;

            $order->{$field} = $value;

            if (!in_array($originalMainStatus, ['completed', 'cancelled', 'failed'])) {
                $newCalculatedStatus = 'pending';

                if (in_array($order->payment_status, ['paid', 'settlement', 'capture'])) {
                    if ($order->shipping_status === 'delivered') {
                        $newCalculatedStatus = 'completed';
                    } elseif ($order->shipping_status === 'shipped') {
                         $newCalculatedStatus = 'processing';
                    } else {
                        $newCalculatedStatus = 'processing';
                    }
                } elseif (in_array($order->payment_status, ['deny', 'expire', 'cancel', 'refunded'])) {
                    $newCalculatedStatus = 'cancelled';
                }

                if ($newCalculatedStatus !== $originalMainStatus) {
                    $order->status = $newCalculatedStatus;
                    $mainStatusChanged = true;
                }
            }

            // Panggil save() pada model yang sudah ada
            $order->save(); // <<< Ini seharusnya melakukan UPDATE jika $order->exists adalah true

            DB::commit();

            Log::info('Quick Update successful', ['order_id' => $order->id, 'field' => $field, 'new_value' => $value, 'new_main_status' => $order->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'updated_field' => $field,
                'new_value' => $value,
                'main_status_updated' => $mainStatusChanged,
                'new_main_status' => $order->status,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error quick updating order {$order->id} field {$field}: " . $e->getMessage(), ['exception' => $e, 'stacktrace' => $e->getTraceAsString()]); // Log stacktrace lengkap

            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server saat memperbarui status.'], 500);
        }
    }

}
