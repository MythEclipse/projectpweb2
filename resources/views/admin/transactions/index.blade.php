{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Daftar Transaksi
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8"> {{-- Outer padding handled by sm/lg --}}

            {{-- Session Status / Alerts (Keeping semantic colors for alerts) --}}
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
                           class="flex-grow border border-gray-300 dark:border-dark-border rounded-md py-2 pl-4 pr-10 focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-bg dark:text-text-light w-full" {{-- Use dark-border, dark-bg, text-light, pink-brand --}}
                           placeholder="Cari transaksi...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-brand"> {{-- Use pink-brand --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                    </button>
                 </form>
             </div>

            {{-- Table Section --}}
            <div class="space-y-6 px-4 sm:px-0"> {{-- Inner container matching reference --}}

                <div class="overflow-x-auto bg-white dark:bg-dark-bg rounded-xl shadow"> {{-- Use dark-bg --}}
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-dark-border"> {{-- Use dark-border for division --}}
                        <thead class="bg-gray-100 dark:bg-dark-card"> {{-- Use dark-card for head bg --}}
                            <tr class="text-gray-600 dark:text-gray-400"> {{-- Use lighter gray for dark head text --}}
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
                        <tbody class="bg-white dark:bg-dark-bg divide-y divide-gray-100 dark:divide-dark-border text-text-dark dark:text-text-light" id="transaction-table-body"> {{-- Use dark-bg, dark-border, text-dark, text-light --}}
                            @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-card transition" id="transaction-row-{{ $tx->id }}"> {{-- Use dark-card for hover --}}
                                {{-- Use px-4 py-4 for data cells --}}
                                <td class="px-4 py-4 break-words font-semibold">{{ $tx->product->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">{{ $tx->size->name ?? 'N/A' }} / {{ $tx->color->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden sm:table-cell">{{ $tx->quantity }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden lg:table-cell">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap font-semibold text-pink-brand dark:text-pink-brand">Rp {{ number_format($tx->total, 0, ',', '.') }}</td> {{-- Use pink-brand --}}
                                <td class="px-4 py-4 break-words">{{ $tx->user->name ?? 'N/A' }}</td>

                                {{-- Kolom Status Transaksi (Keeping semantic colors, updating default dark) --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Base classes applied here to potentially help IntelliSense --}}
                                    <span class="transaction-status-badge-{{ $tx->id }} inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit
                                        {{-- Color classes applied conditionally --}}
                                        @switch($tx->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @break {{-- Keep semantic --}}
                                            @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @break {{-- Keep semantic --}}
                                            @case('completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @break {{-- Keep semantic --}}
                                            @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break {{-- Keep semantic --}}
                                            @default bg-gray-100 text-gray-800 dark:bg-dark-subcard dark:text-gray-400 {{-- Use dark-subcard for default dark --}}
                                        @endswitch
                                    ">
                                        {{ ucfirst($tx->status ?? 'N/A') }}
                                    </span>
                                    {{-- Note: IntelliSense might still show warnings here due to how @switch works with class lists, but functionally it's correct. --}}
                                </td>

                                {{-- Kolom Status Pembayaran --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     <div class="flex items-center space-x-2" title="Klik untuk ubah status pembayaran">
                                        <label for="payment_toggle_{{ $tx->id }}" class="inline-flex items-center cursor-pointer">
                                            <span class="relative">
                                                <input type="checkbox" id="payment_toggle_{{ $tx->id }}" class="sr-only peer quick-update-toggle" data-transaction-id="{{ $tx->id }}" data-field="payment_status" data-value-checked="paid" data-value-unchecked="unpaid" @checked($tx->payment_status == 'paid')>
                                                {{-- Matching Toggle Style using custom palette --}}
                                                <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-dark-subcard peer-focus:ring-1 peer-focus:ring-pink-brand dark:peer-focus:ring-pink-brand peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-dark-border peer-checked:bg-pink-brand"></div> {{-- Use dark-subcard, pink-brand, dark-border --}}
                                            </span>
                                            <span class="ml-2 text-xs font-medium text-gray-600 dark:text-gray-400 payment-status-text-{{ $tx->id }}"> {{-- Keep gray for muted text --}}
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
                                                class="quick-update-select text-xs p-1 border border-gray-300 dark:border-dark-border rounded shadow-sm focus:outline-none focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-card dark:text-text-light w-full sm:w-auto" {{-- Use dark-border, pink-brand, dark-card, text-light --}}
                                                data-transaction-id="{{ $tx->id }}"
                                                data-field="shipping_status">
                                            <option value="not_shipped" @selected($tx->shipping_status == 'not_shipped')>Belum Kirim</option>
                                            <option value="shipped" @selected($tx->shipping_status == 'shipped')>Dikirim</option>
                                            <option value="delivered" @selected($tx->shipping_status == 'delivered')>Diterima</option>
                                        </select>
                                        <span class="status-indicator text-xs"></span> {{-- Indicator for quick update status --}}
                                    </div>
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $tx->created_at->format('d M Y H:i') }}</td> {{-- Keep muted gray --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Actions matching reference style (Keeping semantic colors for actions) --}}
                                    <div class="flex gap-2 flex-wrap justify-center">
                                         <a href="{{ route('admin.transactions.show', $tx->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                            View
                                        </a>
                                        {{-- Delete uses red --}}
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
                                <td colspan="11" class="text-center py-8 text-gray-500 dark:text-gray-400"> {{-- Colspan updated to 11, keep muted gray --}}
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

                 {{-- Pagination (Using Default Tailwind View - colors may need separate customization if needed) --}}
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

        {{-- Quick Update JavaScript (Adjusted getStatusBadgeClasses default) --}}
        <script>
             document.addEventListener('DOMContentLoaded', function () {
                const tableBody = document.getElementById('transaction-table-body');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const indicatorTimeouts = {};

                // --- Function: getStatusBadgeClasses (Updated default dark mode) ---
                 function getStatusBadgeClasses(status) {
                     switch (status) {
                        case 'pending':    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; // Keep semantic
                        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';      // Keep semantic
                        case 'completed':  return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';    // Keep semantic
                        case 'cancelled':  return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';        // Keep semantic
                        default:           return 'bg-gray-100 text-gray-800 dark:bg-[#2d2d2d] dark:text-gray-400'; // Use dark-subcard hex + gray text
                     }
                 }

                // --- Function: updateMainStatusBadge ---
                function updateMainStatusBadge(transactionId, newStatus) {
                    const badgeElement = document.querySelector(`.transaction-status-badge-${transactionId}`);
                    if (!badgeElement) { console.error(`Badge element not found for transaction ${transactionId}`); return; }

                    // Update text content
                    badgeElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

                    // Get the base classes (already present) and new color classes
                    const baseClasses = 'inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit';
                    const newColorClasses = getStatusBadgeClasses(newStatus);

                    // Set the new class list (base + new colors)
                    badgeElement.className = `${baseClasses} ${newColorClasses}`;
                }


                // --- Function: showIndicator (Keep semantic icon colors) ---
                function showIndicator(indicatorElement, type = 'loading', message = '', transactionId = null, field = null) {
                    if (!indicatorElement) return;
                    const timeoutKey = `${transactionId}-${field}`;
                    if (indicatorTimeouts[timeoutKey]) { clearTimeout(indicatorTimeouts[timeoutKey]); delete indicatorTimeouts[timeoutKey]; }
                    indicatorElement.innerHTML = ''; let icon = ''; let colorClass = '';
                    switch (type) {
                        // NOTE: Ensure FontAwesome is loaded if using these icons
                        case 'loading': icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; break; // Keep blue for loading
                        // case 'loading': icon = '<span class="inline-block w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></span>'; // CSS Spinner Alternative
                        case 'success': icon = '<i class="fas fa-check-circle text-green-500"></i>'; colorClass = 'text-green-500'; break; // Keep green for success
                        case 'error': icon = '<i class="fas fa-times-circle text-red-500"></i>'; colorClass = 'text-red-500'; message = message || 'Error'; indicatorElement.title = message; break; // Keep red for error
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

                // --- Function: handleQuickUpdate (No changes needed here) ---
                function handleQuickUpdate(element, transactionId, field, value) {
                     const updateUrl = `/admin/transactions/${transactionId}/quick-update`;
                     const controlWrapper = element.closest('.flex');
                     const indicatorElement = controlWrapper ? controlWrapper.querySelector('.status-indicator') : null;
                     if (indicatorElement) showIndicator(indicatorElement, 'loading', '', transactionId, field);
                     element.disabled = true;
                     let originalValue = null;
                     if (element.tagName === 'SELECT') originalValue = element.options[element.selectedIndex].value;
                     else if (element.type === 'checkbox') originalValue = !element.checked; // Previous state before click

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
                             // If the quick update changed the main transaction status, update the main badge
                             if (data.main_status_updated && data.new_main_status) {
                                 updateMainStatusBadge(transactionId, data.new_main_status);
                             }
                         } else { throw new Error(data.message || 'Gagal memperbarui status dari server.'); }
                     })
                     .catch(error => {
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

                 // Event Listeners for Quick Updates (No changes needed here)
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

        {{-- SweetAlert Delete Confirmation (Using custom palette via JS) --}}
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
                            cancelButtonColor: '#6b7280', // neutral gray (standard, ok for cancel)
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
