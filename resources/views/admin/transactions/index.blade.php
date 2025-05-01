{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Session Status / Alerts --}}
            {{-- ... (keep your alerts here) ... --}}
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tombol Aksi --}}
            <div class="flex justify-end mb-4">
                 <a href="{{ route('admin.transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-brand border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-brand-dark focus:outline-none focus:border-pink-brand-dark focus:ring focus:ring-pink-300 disabled:opacity-25 transition">
                     Tambah Transaksi
                 </a>
             </div>

            <div class="bg-white dark:bg-dark-card shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-dark-card text-sm">
                        <thead class="bg-gray-50 dark:bg-dark-thead">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembeli</th>
                                {{-- --- Updated Headers --- --}}
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Trx</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pembayaran</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengiriman</th>
                                {{-- --- End Updated Headers --- --}}
                                <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="p-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-dark-border text-text-dark dark:text-text-light" id="transaction-table-body">
                            @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-subcard transition duration-150 ease-in-out" id="transaction-row-{{ $tx->id }}">
                                <td class="p-3 whitespace-nowrap align-top">{{ $tx->product->name ?? 'N/A' }}</td>
                                <td class="p-3 whitespace-nowrap align-top">
                                    {{ $tx->size->name ?? 'N/A' }} / {{ $tx->color->name ?? 'N/A' }}
                                </td>
                                <td class="p-3 whitespace-nowrap text-right align-top">{{ $tx->quantity }}</td>
                                <td class="p-3 whitespace-nowrap text-right align-top">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                                <td class="p-3 whitespace-nowrap text-right font-semibold align-top">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                <td class="p-3 whitespace-nowrap align-top">{{ $tx->user->name ?? 'N/A' }}</td>

                                {{-- === Kolom Status Transaksi === --}}
                                <td class="p-3 whitespace-nowrap align-top">
                                    <span class="block px-2 py-0.5 text-xs leading-5 font-semibold rounded-full w-fit
                                        @switch($tx->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                            @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                            @case('completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                            @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                            @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                        @endswitch
                                    ">
                                        {{ ucfirst($tx->status ?? 'N/A') }}
                                    </span>
                                </td>

                                {{-- === Kolom Status Pembayaran (Toggle + Indikator) === --}}
                                <td class="p-3 whitespace-nowrap align-top">
                                     <div class="flex items-center space-x-2" title="Klik untuk ubah status pembayaran">
                                        <label for="payment_toggle_{{ $tx->id }}" class="inline-flex items-center cursor-pointer">
                                            <span class="relative">
                                                <input type="checkbox" id="payment_toggle_{{ $tx->id }}"
                                                    class="sr-only peer quick-update-toggle"
                                                    data-transaction-id="{{ $tx->id }}"
                                                    data-field="payment_status"
                                                    data-value-checked="paid"
                                                    data-value-unchecked="unpaid"
                                                    @checked($tx->payment_status == 'paid')>
                                                {{-- Toggle Switch Styling --}}
                                                <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-1 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-brand peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-pink-brand"></div>
                                            </span>
                                            <span class="ml-2 text-xs font-medium text-gray-600 dark:text-gray-400 payment-status-text-{{ $tx->id }}">
                                                {{ $tx->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </label>
                                        {{-- Dedicated Indicator for Payment Status --}}
                                        <span class="status-indicator text-xs"></span>
                                    </div>
                                </td>

                                {{-- === Kolom Status Pengiriman (Select + Indikator) === --}}
                                <td class="p-3 whitespace-nowrap align-top">
                                     <div class="flex items-center space-x-2" title="Ubah status pengiriman">
                                        <select name="shipping_status"
                                                class="quick-update-select text-xs p-1 border border-gray-300 dark:border-dark-border rounded shadow-sm focus:outline-none focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-input dark:text-text-light w-full sm:w-auto" {{-- Adjust width if needed --}}
                                                data-transaction-id="{{ $tx->id }}"
                                                data-field="shipping_status">
                                            <option value="not_shipped" @selected($tx->shipping_status == 'not_shipped')>Belum Kirim</option>
                                            <option value="shipped" @selected($tx->shipping_status == 'shipped')>Dikirim</option>
                                            <option value="delivered" @selected($tx->shipping_status == 'delivered')>Diterima</option>
                                        </select>
                                        {{-- Dedicated Indicator for Shipping Status --}}
                                        <span class="status-indicator text-xs"></span>
                                    </div>
                                </td>
                                {{-- ============================================================= --}}

                                <td class="p-3 whitespace-nowrap text-gray-500 dark:text-gray-400 align-top">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                <td class="p-3 whitespace-nowrap text-center align-top">
                                    <div class="flex items-center justify-center space-x-2">
                                         <a href="{{ route('admin.transactions.show', $tx->id) }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand dark:hover:text-pink-500 transition duration-150 ease-in-out" title="Lihat Detail"><i class="fas fa-eye fa-fw"></i></a>
                                         {{-- <a href="{{ route('admin.transactions.edit', $tx->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition duration-150 ease-in-out" title="Edit Lengkap"><i class="fas fa-edit fa-fw"></i></a> --}}
                                         <form action="{{ route('admin.transactions.destroy', $tx->id) }}" method="POST" class="inline" onsubmit="return confirm('Anda yakin ingin menghapus transaksi ini? Aksi ini tidak dapat diurungkan.');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-150 ease-in-out" title="Hapus">
                                                 <i class="fas fa-trash-alt fa-fw"></i>
                                             </button>
                                         </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                {{-- === Updated colspan for empty row === --}}
                                <td colspan="11" class="p-6 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data transaksi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                 @if ($transactions->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-dark-card border-t border-gray-200 dark:border-dark-border">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Include CSRF token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome (jika belum ada di layout utama) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}

    {{-- ================== JavaScript (No Changes Needed) ================== --}}
    {{-- The existing JavaScript should work as is because indicator targeting is relative --}}
    @push('scripts')
    <script>
        // Paste the SAME JavaScript code from the previous correct answer here.
        // No changes are required for the JavaScript logic itself.
         document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('transaction-table-body');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const indicatorTimeouts = {};

            function showIndicator(indicatorElement, type = 'loading', message = '', transactionId = null, field = null) {
                if (!indicatorElement) return;
                const timeoutKey = `${transactionId}-${field}`;
                if (indicatorTimeouts[timeoutKey]) {
                    clearTimeout(indicatorTimeouts[timeoutKey]);
                    delete indicatorTimeouts[timeoutKey];
                }
                indicatorElement.innerHTML = '';
                let icon = '';
                let colorClass = '';
                switch (type) {
                    case 'loading': icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; break;
                    case 'success': icon = '<i class="fas fa-check-circle text-green-500"></i>'; colorClass = 'text-green-500'; break;
                    case 'error': icon = '<i class="fas fa-times-circle text-red-500"></i>'; colorClass = 'text-red-500'; message = message || 'Error'; indicatorElement.title = message; break;
                    default: indicatorElement.innerHTML = ''; indicatorElement.title = ''; return;
                }
                indicatorElement.innerHTML = icon;
                if ((type === 'success' || type === 'error') && transactionId && field) {
                    indicatorTimeouts[timeoutKey] = setTimeout(() => {
                        if (indicatorElement.innerHTML.includes('fa-check-circle') || indicatorElement.innerHTML.includes('fa-times-circle')) {
                             indicatorElement.innerHTML = ''; indicatorElement.title = '';
                        }
                       delete indicatorTimeouts[timeoutKey];
                    }, 3500);
                } else if (type !== 'loading') {
                     indicatorElement.innerHTML = ''; indicatorElement.title = '';
                }
            }

            function handleQuickUpdate(element, transactionId, field, value) {
                const updateUrl = `/admin/transactions/${transactionId}/quick-update`;
                const controlWrapper = element.closest('.flex');
                const indicatorElement = controlWrapper ? controlWrapper.querySelector('.status-indicator') : null;
                if (indicatorElement) showIndicator(indicatorElement, 'loading', '', transactionId, field);
                element.disabled = true;
                let originalValue = null;
                if (element.tagName === 'SELECT') originalValue = element.options[element.selectedIndex].value;
                else if (element.type === 'checkbox') originalValue = !element.checked;

                fetch(updateUrl, {
                    method: 'PATCH',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                    body: JSON.stringify({ field: field, value: value })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().catch(() => ({})).then(errorData => {
                            let errorMsg = `Server error: ${response.status}.`;
                            if (errorData && errorData.message) errorMsg += ` Pesan: ${errorData.message}`;
                            if (errorData && errorData.errors) {
                                const validationErrors = Object.values(errorData.errors).flat().join(' ');
                                errorMsg += ` Detail: ${validationErrors}`;
                            }
                           throw new Error(errorMsg);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log('Update successful:', data.message || 'Status diperbarui.');
                         if(indicatorElement) showIndicator(indicatorElement, 'success', '', transactionId, field);
                         if (field === 'payment_status') {
                            const textSpan = element.closest('label')?.querySelector(`.payment-status-text-${transactionId}`);
                            if(textSpan) textSpan.textContent = data.new_value === 'paid' ? 'Lunas' : 'Belum Lunas';
                         }
                    } else { throw new Error(data.message || 'Gagal memperbarui status.'); }
                })
                .catch(error => {
                    console.error('Update Error:', error);
                    if(indicatorElement) showIndicator(indicatorElement, 'error', error.message, transactionId, field);
                    if (element.type === 'checkbox') {
                        element.checked = originalValue;
                         if (field === 'payment_status') {
                            const textSpan = element.closest('label')?.querySelector(`.payment-status-text-${transactionId}`);
                             if(textSpan) textSpan.textContent = originalValue ? 'Lunas' : 'Belum Lunas';
                         }
                    } else if (element.tagName === 'SELECT' && originalValue !== null) { element.value = originalValue; }
                })
                .finally(() => { element.disabled = false; });
            }

            tableBody.addEventListener('change', function(event) {
                const target = event.target;
                if (target.matches('.quick-update-toggle') && target.type === 'checkbox') {
                    handleQuickUpdate(target, target.dataset.transactionId, target.dataset.field, target.checked ? target.dataset.valueChecked : target.dataset.valueUnchecked);
                } else if (target.matches('.quick-update-select') && target.tagName === 'SELECT') {
                    handleQuickUpdate(target, target.dataset.transactionId, target.dataset.field, target.value);
                }
            });
        });
    </script>
    @endpush

</x-app-layout>
