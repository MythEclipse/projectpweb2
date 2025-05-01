{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Daftar Transaksi
            </h2>
            <a href="{{ route('admin.transactions.create') }}"  class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                Tambah Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8"> {{-- Outer padding handled by sm/lg --}}

            {{-- Session Status / Alerts --}}
            <div class="px-4 sm:px-0"> {{-- Add padding if needed --}}
                 @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif
                {{-- Add other alerts --}}
            </div>


            {{-- Actions: Search and Add Button --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0">
                {{-- Search Form --}}
                <div class="relative w-full sm:w-auto flex-grow">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white"
                               placeholder="Cari transaksi (belum implementasi)...">
                        <button type="submit" title="Cari"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                        </button>
                    </form>
                     {{-- Add note about implementation if search is not functional yet --}}
                    @if(!isset($hasSearchImplementation))
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pencarian belum diimplementasikan di controller.</p>
                    @endif
                </div>


             </div>


            {{-- Table Section --}}
            <div class="space-y-6 px-4 sm:px-0"> {{-- Inner container matching reference --}}

                <div class="overflow-x-auto bg-white dark:bg-[#0a0a0a] rounded-xl shadow">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-[#3E3E3A]">
                        <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                            <tr class="text-gray-600 dark:text-gray-300">
                                {{-- Use px-4 py-3 for headers --}}
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Detail</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden sm:table-cell">Qty</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden lg:table-cell">Harga</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembeli</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Status Trx</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembayaran</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pengiriman</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Tanggal</th>
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-gray-100 dark:divide-[#2d2d2d] text-gray-700 dark:text-gray-300" id="transaction-table-body">
                            {{-- You could add a loading state similar to the reference if needed --}}
                            {{-- <template x-if="loading"> ... skeleton rows ... </template> --}}

                            {{-- <template x-if="!loading"> --}}
                            @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition" id="transaction-row-{{ $tx->id }}">
                                {{-- Use px-4 py-4 for data cells --}}
                                <td class="px-4 py-4 break-words font-semibold">{{ $tx->product->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">{{ $tx->size->name ?? 'N/A' }} / {{ $tx->color->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden sm:table-cell">{{ $tx->quantity }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden lg:table-cell">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap font-semibold text-pink-600 dark:text-pink-400">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 break-words">{{ $tx->user->name ?? 'N/A' }}</td>

                                {{-- Kolom Status Transaksi --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="transaction-status-badge-{{ $tx->id }} inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit
                                        @switch($tx->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @break
                                            @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @break
                                            @case('completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @break
                                            @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break
                                            @default bg-gray-100 text-gray-800 dark:bg-[#2d2d2d] dark:text-gray-400
                                        @endswitch
                                    ">
                                        {{ ucfirst($tx->status ?? 'N/A') }}
                                    </span>
                                </td>

                                {{-- Kolom Status Pembayaran --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     <div class="flex items-center space-x-2" title="Klik untuk ubah status pembayaran">
                                        <label for="payment_toggle_{{ $tx->id }}" class="inline-flex items-center cursor-pointer">
                                            <span class="relative">
                                                <input type="checkbox" id="payment_toggle_{{ $tx->id }}" class="sr-only peer quick-update-toggle" data-transaction-id="{{ $tx->id }}" data-field="payment_status" data-value-checked="paid" data-value-unchecked="unpaid" @checked($tx->payment_status == 'paid')>
                                                {{-- Matching Toggle Style --}}
                                                <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-[#3E3E3A] peer-focus:ring-1 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-500 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-pink-500"></div>
                                            </span>
                                            <span class="ml-2 text-xs font-medium text-gray-600 dark:text-gray-400 payment-status-text-{{ $tx->id }}">
                                                {{ $tx->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </label>
                                        <span class="status-indicator text-xs"></span>
                                    </div>
                                </td>

                                {{-- Kolom Status Pengiriman --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     <div class="flex items-center space-x-2" title="Ubah status pengiriman">
                                        <select name="shipping_status"
                                                class="quick-update-select text-xs p-1 border border-gray-300 dark:border-[#3E3E3A] rounded shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-[#1a1a1a] dark:text-gray-300 w-full sm:w-auto"
                                                data-transaction-id="{{ $tx->id }}"
                                                data-field="shipping_status">
                                            <option value="not_shipped" @selected($tx->shipping_status == 'not_shipped')>Belum Kirim</option>
                                            <option value="shipped" @selected($tx->shipping_status == 'shipped')>Dikirim</option>
                                            <option value="delivered" @selected($tx->shipping_status == 'delivered')>Diterima</option>
                                        </select>
                                        <span class="status-indicator text-xs"></span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Actions matching reference style --}}
                                    <div class="flex gap-2 flex-wrap justify-center">
                                         <a href="{{ route('admin.transactions.show', $tx->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                            View
                                        </a>
                                        <a href="{{ route('admin.transactions.edit', $tx->id) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" data-turbo="false">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $tx->id) }}" class="inline delete-form" onsubmit="return confirm('Anda yakin ingin menghapus transaksi ini? Aksi ini tidak dapat diurungkan.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm" data-turbo="false">
                                                Delete
                                            </button>
                                        </form>
                                     </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-gray-500 dark:text-gray-400"> {{-- Updated colspan --}}
                                    Tidak ada data transaksi.
                                </td>
                            </tr>
                            @endforelse
                            {{-- </template> --}}
                        </tbody>
                    </table>
                </div>

                 {{-- Pagination matching reference style --}}
                 @if ($transactions->hasPages())
                 <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                     {{-- Pagination Links --}}
                     <div class="flex items-center space-x-1">
                         @if ($transactions->onFirstPage())
                             <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed"><</span>
                         @else
                             <a href="{{ $transactions->previousPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition"><</a>
                         @endif

                         @foreach ($transactions->links()->elements[0] as $page => $url)
                            @php $url = request('search') ? $url . '&search=' . request('search') : $url; @endphp
                            @if ($page == $transactions->currentPage())
                                <span class="px-3 py-1 rounded-md bg-pink-500 text-white font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition">{{ $page }}</a>
                            @endif
                         @endforeach


                         @if ($transactions->hasMorePages())
                              <a href="{{ $transactions->nextPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition">></a>
                         @else
                              <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed">></span>
                         @endif
                     </div>

                     {{-- Pagination Summary --}}
                     <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
                         Showing page <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $transactions->currentPage() }}</span>
                         of <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $transactions->lastPage() }}</span>
                         (Total: <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $transactions->total() }}</span> transactions)
                     </div>
                 </div>
                 @endif


            </div> {{-- End inner container --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- CSRF Token Meta Tag --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome (jika belum ada di layout utama & dibutuhkan oleh icon JS) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}

    {{-- Quick Update JavaScript (Keep the last working version) --}}
    @push('scripts')
    <script>
        // --- Paste the exact JavaScript from the previous answer ---
        // It handles the quick updates, status badge changes, indicators, etc.
        // No changes required here based on the styling update.

         document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('transaction-table-body');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const indicatorTimeouts = {};

             function getStatusBadgeClasses(status) { /* ... Same as before ... */
                 switch (status) {
                    case 'pending':    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; // Adjusted dark text color potentially
                    case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'; // Adjusted dark text color potentially
                    case 'completed':  return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; // Adjusted dark text color potentially
                    case 'cancelled':  return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; // Adjusted dark text color potentially
                    default:           return 'bg-gray-100 text-gray-800 dark:bg-[#2d2d2d] dark:text-gray-400';
                 }
             }

            function updateMainStatusBadge(transactionId, newStatus) { /* ... Same as before ... */
                const badgeElement = document.querySelector(`.transaction-status-badge-${transactionId}`);
                if (!badgeElement) { console.error(`Badge element not found for transaction ${transactionId}`); return; }
                badgeElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                // Remove old classes carefully
                badgeElement.classList.remove(
                    'bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-300',
                    'bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-300',
                    'bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-300',
                    'bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-300',
                    'bg-gray-100', 'text-gray-800', 'dark:bg-[#2d2d2d]', 'dark:text-gray-400'
                );
                // Add new classes
                badgeElement.classList.add(...getStatusBadgeClasses(newStatus).split(' '));
            }

            function showIndicator(indicatorElement, type = 'loading', message = '', transactionId = null, field = null) { /* ... Same as before ... */
                if (!indicatorElement) return;
                const timeoutKey = `${transactionId}-${field}`;
                if (indicatorTimeouts[timeoutKey]) { clearTimeout(indicatorTimeouts[timeoutKey]); delete indicatorTimeouts[timeoutKey]; }
                indicatorElement.innerHTML = ''; let icon = ''; let colorClass = '';
                switch (type) {
                    case 'loading': icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; break; // Make sure Font Awesome is loaded if using icons
                    // case 'loading': icon = '<span class="inline-block w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></span>'; break; // Alternative pure CSS spinner
                    case 'success': icon = '<i class="fas fa-check-circle text-green-500"></i>'; colorClass = 'text-green-500'; break;
                    case 'error': icon = '<i class="fas fa-times-circle text-red-500"></i>'; colorClass = 'text-red-500'; message = message || 'Error'; indicatorElement.title = message; break;
                    default: indicatorElement.innerHTML = ''; indicatorElement.title = ''; return;
                }
                indicatorElement.innerHTML = icon;
                if ((type === 'success' || type === 'error') && transactionId && field) {
                    indicatorTimeouts[timeoutKey] = setTimeout(() => {
                        if (indicatorElement.innerHTML.includes('fa-check-circle') || indicatorElement.innerHTML.includes('fa-times-circle')) {
                            indicatorElement.innerHTML = ''; indicatorElement.title = '';
                        } delete indicatorTimeouts[timeoutKey];
                    }, 3500);
                } else if (type !== 'loading') { indicatorElement.innerHTML = ''; indicatorElement.title = ''; }
            }


            function handleQuickUpdate(element, transactionId, field, value) { /* ... Same as before ... */
                 const updateUrl = `/admin/transactions/${transactionId}/quick-update`;
                 const controlWrapper = element.closest('.flex'); // Targets the flex container of the control+indicator
                 const indicatorElement = controlWrapper ? controlWrapper.querySelector('.status-indicator') : null;
                 if (indicatorElement) showIndicator(indicatorElement, 'loading', '', transactionId, field);
                 element.disabled = true;
                 let originalValue = null;
                 if (element.tagName === 'SELECT') originalValue = element.options[element.selectedIndex].value;
                 else if (element.type === 'checkbox') originalValue = !element.checked;

                 fetch(updateUrl, { /* ... */
                    method: 'PATCH',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                    body: JSON.stringify({ field: field, value: value })
                 })
                 .then(response => { /* ... */
                     if (!response.ok) {
                         return response.json().catch(() => ({})).then(errorData => {
                             let errorMsg = `Server error: ${response.status}.`;
                             if (errorData && errorData.message) errorMsg += ` Pesan: ${errorData.message}`;
                             if (errorData && errorData.errors) { errorMsg += ` Detail: ${Object.values(errorData.errors).flat().join(' ')}`; }
                             throw new Error(errorMsg);
                         });
                     }
                     return response.json();
                 })
                 .then(data => {
                     if (data.success) {
                         console.log('Update successful:', data.message || 'Status diperbarui.');
                         if(indicatorElement) showIndicator(indicatorElement, 'success', '', transactionId, field);
                         if (data.updated_field === 'payment_status') {
                             const textSpan = element.closest('label')?.querySelector(`.payment-status-text-${transactionId}`);
                             if(textSpan) textSpan.textContent = data.new_value === 'paid' ? 'Lunas' : 'Belum Lunas';
                         }
                         if (data.main_status_updated && data.new_main_status) {
                             updateMainStatusBadge(transactionId, data.new_main_status);
                         }
                     } else { throw new Error(data.message || 'Gagal memperbarui status dari server.'); }
                 })
                 .catch(error => { /* ... */
                     console.error('Update Error:', error);
                     if(indicatorElement) showIndicator(indicatorElement, 'error', error.message, transactionId, field);
                     // Revert UI
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

             // Event Listeners
             tableBody.addEventListener('change', function(event) { /* ... Same as before ... */
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
