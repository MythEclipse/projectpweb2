{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Daftar Transaksi
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8"> {{-- Outer padding handled by sm/lg --}}

            {{-- Session Status / Alerts (Adopted from Products) --}}
            <div class="px-4 sm:px-0">
                 {{-- Success Alert --}}
                 @if (session('success') || session('status')) {{-- Catch both keys for flexibility --}}
                    <div id="alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') ?? session('status') }}
                    </div>
                @endif

                {{-- Error Alert --}}
                @if (session('error'))
                    <div id="alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                @endif

                 {{-- Display validation errors if needed --}}
                 @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm">
                        <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Actions: Search --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6"> {{-- Added mb-6 --}}
                {{-- Search Form --}}
                 <form method="GET" action="{{ route('admin.transactions.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white w-full" {{-- Added w-full for better mobile --}}
                           placeholder="Cari transaksi...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                    </button>
                     {{-- Add note about implementation if search is not functional yet --}}
                    {{-- @if(!isset($hasSearchImplementation))
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pencarian belum diimplementasikan di controller.</p>
                    @endif --}}
                 </form>
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
                                        <span class="status-indicator text-xs"></span> {{-- Indicator for quick update status --}}
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
                                        <span class="status-indicator text-xs"></span> {{-- Indicator for quick update status --}}
                                    </div>
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Actions matching reference style --}}
                                    <div class="flex gap-2 flex-wrap justify-center">
                                         <a href="{{ route('admin.transactions.show', $tx->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                            View
                                        </a>
                                        {{-- <a href="{{ route('admin.transactions.edit', $tx->id) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" data-turbo="false">
                                            Edit
                                        </a> --}}
                                        {{-- Adjusted Delete Form --}}
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $tx->id) }}" class="inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:underline text-sm" data-turbo="false">
                                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" /> {{-- Using X icon like product --}}
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                     </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-gray-500 dark:text-gray-400"> {{-- Colspan updated to 11 --}}
                                    Tidak ada data transaksi ditemukan.
                                     @if(request('search'))
                                        <span class="block text-sm">Coba ubah kata kunci pencarian Anda.</span>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                 {{-- Pagination (Using Default Tailwind View) --}}
                 @if ($transactions->hasPages())
                 <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                     <div>
                         {{-- This automatically handles search query persistence --}}
                         {{ $transactions->appends(request()->query())->links('vendor.pagination.tailwind') }}
                     </div>
                     {{-- Optional: Add summary text back if needed, styled appropriately --}}
                     {{-- <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
                         Showing page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }} (Total: {{ $transactions->total() }})
                     </div> --}}
                 </div>
                 @endif

            </div> {{-- End inner container --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- CSRF Token Meta Tag --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome (jika belum ada di layout utama & dibutuhkan oleh icon JS) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}

    {{-- Scripts Section --}}
    @push('scripts')
        {{-- Dependencies for Alerts and Delete Confirmation --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Quick Update JavaScript (Keep the existing logic) --}}
        <script>
             document.addEventListener('DOMContentLoaded', function () {
                const tableBody = document.getElementById('transaction-table-body');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const indicatorTimeouts = {};

                // --- Functions: getStatusBadgeClasses, updateMainStatusBadge, showIndicator ---
                // (These remain the same as your original 'transactions' script)
                 function getStatusBadgeClasses(status) { /* ... Same as before ... */
                     switch (status) {
                        case 'pending':    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                        case 'completed':  return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                        case 'cancelled':  return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
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
                        // NOTE: Ensure FontAwesome is loaded if using these icons
                        case 'loading': icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; break;
                        // case 'loading': icon = '<span class="inline-block w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></span>'; break; // CSS Spinner Alternative
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

                // --- Function: handleQuickUpdate ---
                // (Remains the same as your original 'transactions' script)
                function handleQuickUpdate(element, transactionId, field, value) { /* ... Same as before ... */
                     const updateUrl = `/admin/transactions/${transactionId}/quick-update`;
                     const controlWrapper = element.closest('.flex');
                     const indicatorElement = controlWrapper ? controlWrapper.querySelector('.status-indicator') : null;
                     if (indicatorElement) showIndicator(indicatorElement, 'loading', '', transactionId, field);
                     element.disabled = true;
                     let originalValue = null;
                     if (element.tagName === 'SELECT') originalValue = element.options[element.selectedIndex].value;
                     else if (element.type === 'checkbox') originalValue = !element.checked; // Previous state before click

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
                     .then(data => { /* ... */
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
                             element.checked = !element.checked; // Revert check state
                             if (field === 'payment_status') {
                                 const textSpan = element.closest('label')?.querySelector(`.payment-status-text-${transactionId}`);
                                 if(textSpan) textSpan.textContent = element.checked ? 'Lunas' : 'Belum Lunas'; // Revert text based on reverted check state
                             }
                         } else if (element.tagName === 'SELECT' && originalValue !== null) { element.value = originalValue; }
                     })
                     .finally(() => { element.disabled = false; });
                 }

                 // Event Listeners for Quick Updates
                 if (tableBody) {
                    tableBody.addEventListener('change', function(event) {
                        const target = event.target;
                        if (target.matches('.quick-update-toggle') && target.type === 'checkbox') {
                            handleQuickUpdate(target, target.dataset.transactionId, target.dataset.field, target.checked ? target.dataset.valueChecked : target.dataset.valueUnchecked);
                        } else if (target.matches('.quick-update-select') && target.tagName === 'SELECT') {
                            handleQuickUpdate(target, target.dataset.transactionId, target.dataset.field, target.value);
                        }
                    });
                 } else {
                     console.warn("Element with ID 'transaction-table-body' not found. Quick updates might not work.");
                 }

             });
        </script>

        {{-- SweetAlert Delete Confirmation (Adopted from Products) --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const deleteForms = document.querySelectorAll('.delete-form');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const isDarkMode = document.documentElement.classList.contains('dark');

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Tindakan ini tidak dapat diurungkan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#EC4899', // pink-brand
                            cancelButtonColor: '#6b7280', // neutral gray
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            background: isDarkMode ? '#0a0a0a' : '#ffffff', // dark-bg or white
                            color: isDarkMode ? '#EDEDEC' : '#1b1b18', // text-light or text-dark
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
