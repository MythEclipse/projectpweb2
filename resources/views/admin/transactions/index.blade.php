{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Daftar Item Transaksi
            </h2>
            <a href="{{ route('admin.transactions.download') }}" target="_blank"
               class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                Download Laporan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">

            {{-- Session Status / Alerts --}}
            <div class="px-4 sm:px-0">
                 @if (session('success') || session('status'))
                    <div id="alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') ?? session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div id="alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                @endif

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
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6">
                 <form method="GET" action="{{ route('admin.transactions.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="flex-grow border border-gray-300 dark:border-dark-border rounded-md py-2 pl-4 pr-10 focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-bg dark:text-text-light w-full"
                           placeholder="Cari item transaksi...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-brand">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                    </button>
                 </form>
             </div>

            {{-- Table Section --}}
            <div class="space-y-6 px-4 sm:px-0">

                <div class="overflow-x-auto bg-white dark:bg-dark-bg rounded-xl shadow">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-dark-border">
                        <thead class="bg-gray-100 dark:bg-dark-card">
                            <tr class="text-gray-600 dark:text-gray-400">
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Detail Item</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden sm:table-cell">Qty</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden lg:table-cell">Harga Satuan</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider">Total (Item)</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembeli</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Status Pesanan</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembayaran</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pengiriman</th> {{-- Header for Shipping Status Select --}}
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Tanggal Pesanan</th>
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-bg divide-y divide-gray-100 dark:divide-dark-border text-text-dark dark:text-text-light" id="transaction-table-body">
                            @forelse($transactionItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-card transition">
                                <td class="px-4 py-4 break-words font-semibold">{{ $item->product->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">
                                    {{ $item->size->name ?? '' }}
                                    @if($item->size && $item->color) / @endif
                                    {{ $item->color->name ?? '' }}
                                    @if(!$item->size && !$item->color) - @endif
                                </td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden sm:table-cell">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden lg:table-cell">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-4 text-right whitespace-nowrap font-semibold text-pink-brand dark:text-pink-brand">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>

                                <td class="px-4 py-4 break-words">{{ $item->order->user->name ?? 'N/A' }}</td>

                                {{-- Display Status from Order model --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $orderStatus = $item->order->status ?? 'unknown';
                                        $badgeClasses = '';
                                        switch($orderStatus) {
                                            case 'pending': $badgeClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                            case 'processing': $badgeClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'; break;
                                            case 'completed': $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                            case 'cancelled':
                                            case 'failed': $badgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; break;
                                            default: $badgeClasses = 'bg-gray-100 text-gray-800 dark:bg-dark-subcard dark:text-gray-400'; break;
                                        }
                                    @endphp
                                    {{-- Add class to easily select the badge for JS updates --}}
                                    <span class="order-status-badge-{{ $item->order->id }} inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit {{ $badgeClasses }}">
                                        {{ ucfirst($orderStatus) }}
                                    </span>
                                </td>

                                {{-- Display Payment Status from Order model --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $item->order->payment_status ?? 'N/A' }}
                                    {{-- Note: Quick Update UI for payment status is NOT added back here --}}
                                </td>

                                {{-- Kolom Status Pengiriman (Quick Update Select) --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     {{-- Wrap in a div for flex and indicator --}}
                                     <div class="flex items-center space-x-2" title="Ubah status pengiriman">
                                        {{-- Add data-order-id instead of data-transaction-id --}}
                                        <select name="shipping_status"
                                                class="quick-update-select text-xs p-1 border border-gray-300 dark:border-dark-border rounded shadow-sm focus:outline-none focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-card dark:text-text-light w-full sm:w-auto"
                                                data-order-id="{{ $item->order->id }}" {{-- <<< Use Order ID --}}
                                                data-field="shipping_status">
                                            <option value="not_shipped" @selected($item->order->shipping_status == 'not_shipped')>Belum Kirim</option>
                                            <option value="shipped" @selected($item->order->shipping_status == 'shipped')>Dikirim</option>
                                            <option value="delivered" @selected($item->order->shipping_status == 'delivered')>Diterima</option>
                                            {{-- Add other statuses if needed, e.g., returned --}}
                                            <option value="returned" @selected($item->order->shipping_status == 'returned')>Dikembalikan</option>
                                        </select>
                                        {{-- Add a span for the loading/success/error indicator --}}
                                        <span class="status-indicator text-xs"></span>
                                    </div>
                                </td>

                                {{-- Display Order Creation Date --}}
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                     {{ $item->order->created_at->format('d M Y H:i') ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Actions --}}
                                    <div class="flex gap-2 flex-wrap justify-center">
                                         {{-- Link to View the Parent Order (Recommended) --}}
                                         {{-- Assume you have an Admin\OrderController with a 'show' method and corresponding route named 'admin.orders.show' --}}
                                         @if($item->order)
                                             {{-- Link to the Order show page --}}
                                             <a href="{{ route('admin.transactions.show', $item->order->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                                View Order
                                            </a>
                                         @else
                                              <span class="text-gray-500 dark:text-gray-400 text-sm">No Order</span>
                                         @endif

                                        {{-- Delete uses red --}}
                                        {{-- This still deletes the individual ITEM --}}
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $item->id) }}" class="inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:underline text-sm" data-turbo="false">
                                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Delete Item
                                            </button>
                                        </form>
                                     </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    Tidak ada data item transaksi ditemukan.
                                     @if(request('search'))
                                        <span class="block text-sm">Coba ubah kata kunci pencarian Anda.</span>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                 {{-- Pagination --}}
                 @if ($transactionItems->hasPages())
                 <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                     <div>
                         {{ $transactionItems->appends(request()->query())->links('vendor.pagination.tailwind') }}
                     </div>
                 </div>
                 @endif

            </div>
        </div>
    </div>

    {{-- CSRF Token Meta Tag (Still needed for Delete form and AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome (if needed for indicator icons) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}


    {{-- Scripts Section --}}
    @push('scripts')
        {{-- Dependencies for Alerts and Delete Confirmation --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Quick Update JavaScript (Adapted for Order ID) --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
               const tableBody = document.getElementById('transaction-table-body');
               const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
               const indicatorTimeouts = {};

               // --- Function: getStatusBadgeClasses ---
                function getStatusBadgeClasses(status) {
                    switch (status) {
                       case 'pending':    return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                       case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                       case 'completed':  return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                       case 'cancelled':
                       case 'failed':     return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                       default:           return 'bg-gray-100 text-gray-800 dark:bg-[#2d2d2d] dark:text-gray-400';
                    }
                }

               // --- Function: updateMainStatusBadge ---
               function updateMainStatusBadge(orderId, newStatus) {
                   const badgeElement = document.querySelector(`.order-status-badge-${orderId}`);
                   if (!badgeElement) { console.error(`Badge element not found for order ${orderId}`); return; }
                   badgeElement.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                   const baseClasses = 'inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit';
                   const newColorClasses = getStatusBadgeClasses(newStatus);
                   badgeElement.className = `${baseClasses} ${newColorClasses}`;
               }

               // --- Function: showIndicator ---
               function showIndicator(indicatorElement, type = 'loading', message = '', orderId = null, field = null) {
                   if (!indicatorElement) return;
                   const timeoutKey = `${orderId}-${field}`;
                   if (indicatorTimeouts[timeoutKey]) { clearTimeout(indicatorTimeouts[timeoutKey]); delete indicatorTimeouts[timeoutKey]; }
                   indicatorElement.innerHTML = ''; let icon = ''; let colorClass = '';
                   switch (type) {
                       case 'loading': icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; break;
                       case 'success': icon = '<i class="fas fa-check-circle text-green-500"></i>'; colorClass = 'text-green-500'; break;
                       case 'error': icon = '<i class="fas fa-times-circle text-red-500"></i>'; colorClass = 'text-red-500'; message = message || 'Error'; indicatorElement.title = message; break;
                       default: indicatorElement.innerHTML = ''; indicatorElement.title = ''; return;
                   }
                   indicatorElement.innerHTML = icon;
                   if ((type === 'success' || type === 'error') && orderId && field) {
                       indicatorTimeouts[timeoutKey] = setTimeout(() => {
                           if (indicatorElement.innerHTML.includes('fa-check-circle') || indicatorElement.innerHTML.includes('fa-times-circle')) {
                               indicatorElement.innerHTML = ''; indicatorElement.title = '';
                           } delete indicatorTimeouts[timeoutKey];
                       }, 3500);
                   } else if (type !== 'loading') { indicatorElement.innerHTML = ''; indicatorElement.title = ''; }
               }

               // --- Function: handleQuickUpdate (Correct URL Generation) ---
               function handleQuickUpdate(element, orderId, field, value) {
                    // *** GENERATE URL YANG BENAR MENGGUNAKAN ROUTE NAME ***
                    // Gunakan placeholder :orderId yang akan diganti dengan ID Order sebenarnya
                    const updateUrlTemplate = '{{ route('admin.orders.quick-update', ['order' => ':orderId']) }}';
                    const updateUrl = updateUrlTemplate.replace(':orderId', orderId); // Ganti placeholder dengan ID Order

                    const controlWrapper = element.closest('.flex');
                    const indicatorElement = controlWrapper ? controlWrapper.querySelector('.status-indicator') : null;
                    if (indicatorElement) showIndicator(indicatorElement, 'loading', '', orderId, field);
                    element.disabled = true;
                    let originalValue = element.tagName === 'SELECT' ? element.options[element.selectedIndex].value : null;

                    fetch(updateUrl, { // <<< Gunakan URL yang sudah diperbaiki
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
                            if(indicatorElement) showIndicator(indicatorElement, 'success', '', orderId, field);
                            if (data.main_status_updated && data.new_main_status) {
                                updateMainStatusBadge(orderId, data.new_main_status);
                            }
                        } else { throw new Error(data.message || 'Gagal memperbarui status dari server.'); }
                    })
                    .catch(error => {
                        console.error('Update Error:', error);
                        if(indicatorElement) showIndicator(indicatorElement, 'error', error.message, orderId, field);
                        if (element.tagName === 'SELECT' && originalValue !== null) { element.value = originalValue; }
                    })
                    .finally(() => { element.disabled = false; });
                }

                // Event Listener for Quick Updates on Select elements
                if (tableBody) {
                   tableBody.addEventListener('change', function(event) {
                       const target = event.target;
                       if (target.matches('.quick-update-select') && target.tagName === 'SELECT') {
                           const orderId = target.dataset.orderId;
                           const field = target.dataset.field;
                           const value = target.value;
                           if (orderId && field) {
                               handleQuickUpdate(target, orderId, field, value);
                           } else {
                               console.error("Missing data attributes for quick update select.", target);
                           }
                       }
                   });
                } else {
                    console.warn("Element with ID 'transaction-table-body' not found. Quick updates might not work.");
                }

            });
       </script>

        {{-- SweetAlert Delete Confirmation (Still needed for Delete Item) --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const deleteForms = document.querySelectorAll('.delete-form');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const isDarkMode = document.documentElement.classList.contains('dark');

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Item transaksi ini akan dihapus secara permanen. Tindakan ini tidak dapat diurungkan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#EC4899',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            background: isDarkMode ? '#0a0a0a' : '#ffffff',
                            color: isDarkMode ? '#EDEDEC' : '#1b1b18',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @push('scripts')
        {{-- Add Font Awesome if you use icons for indicators --}}
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}
    @endpush
    @endpush {{-- Ensure this matches your layout's push/stack --}}

</x-app-layout>
