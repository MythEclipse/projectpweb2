{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- Header Title --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            {{-- Adjusted title to reflect this lists ITEMs, not full Orders --}}
            <h2 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Daftar Item Transaksi (Detail Pesanan)
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

            {{-- Actions: Search (Still relevant for filtering items based on product/user etc.) --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6">
                 {{-- Search Form (Note: Controller's index method needs to handle this 'search' parameter) --}}
                 <form method="GET" action="{{ route('admin.transactions.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="flex-grow border border-gray-300 dark:border-dark-border rounded-md py-2 pl-4 pr-10 focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-bg dark:text-text-light w-full"
                           placeholder="Cari item transaksi..."> {{-- Adjusted placeholder --}}
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
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Detail Item</th> {{-- Clarified header --}}
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden sm:table-cell">Qty</th>
                                <th class="px-4 py-3 text-right uppercase tracking-wider hidden lg:table-cell">Harga Satuan</th> {{-- Clarified header --}}
                                <th class="px-4 py-3 text-right uppercase tracking-wider">Total (Item)</th> {{-- Clarified header --}}
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembeli</th>
                                {{-- Headers referencing Order properties --}}
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Status Pesanan</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pembayaran</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Pengiriman</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Tanggal Pesanan</th> {{-- Changed header --}}
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-bg divide-y divide-gray-100 dark:divide-dark-border text-text-dark dark:text-text-light">
                            {{-- *** Loop through $transactionItems (new variable name) *** --}}
                            @forelse($transactionItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-dark-card transition">
                                <td class="px-4 py-4 break-words font-semibold">{{ $item->product->name ?? 'N/A' }}</td> {{-- Access product name --}}
                                <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">
                                    {{ $item->size->name ?? '' }} {{-- Access size name, handle null --}}
                                    @if($item->size && $item->color) / @endif {{-- Use '/' as separator --}}
                                    {{ $item->color->name ?? '' }} {{-- Access color name, handle null --}}
                                    @if(!$item->size && !$item->color) - @endif {{-- Display '-' if no size or color --}}
                                </td>
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden sm:table-cell">{{ $item->quantity }}</td> {{-- Item quantity --}}
                                <td class="px-4 py-4 text-right whitespace-nowrap hidden lg:table-cell">Rp {{ number_format($item->price, 0, ',', '.') }}</td> {{-- Item price per unit --}}
                                {{-- *** Calculate Item Total (quantity * price) *** --}}
                                <td class="px-4 py-4 text-right whitespace-nowrap font-semibold text-pink-brand dark:text-pink-brand">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>

                                {{-- *** Access data from the parent Order via the 'order' relationship *** --}}
                                <td class="px-4 py-4 break-words">{{ $item->order->user->name ?? 'N/A' }}</td> {{-- Access buyer name via order and user --}}

                                {{-- *** Display Status from Order model *** --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     {{-- Using Order status for the main badge --}}
                                    @php
                                        $orderStatus = $item->order->status ?? 'unknown'; // Use Order status
                                        $badgeClasses = '';
                                        switch($orderStatus) {
                                            case 'pending': $badgeClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                            case 'processing': $badgeClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'; break;
                                            case 'completed': $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                            case 'cancelled':
                                            case 'failed': $badgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; break; // Also use red for failed
                                            default: $badgeClasses = 'bg-gray-100 text-gray-800 dark:bg-dark-subcard dark:text-gray-400'; break;
                                        }
                                    @endphp
                                    <span class="inline-block px-2 py-1 text-xs leading-tight font-medium rounded-full w-fit {{ $badgeClasses }}">
                                        {{ ucfirst($orderStatus) }}
                                    </span>
                                    {{-- Quick Update UI for status REMOVED --}}
                                </td>

                                {{-- *** Display Payment Status from Order model *** --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{ $item->order->payment_status ?? 'N/A' }}
                                    {{-- Quick Update UI for payment status REMOVED --}}
                                </td>

                                {{-- *** Display Shipping Status from Order model *** --}}
                                <td class="px-4 py-4 whitespace-nowrap">
                                     {{ $item->order->shipping_status ?? 'N/A' }}
                                     {{-- Quick Update UI for shipping status REMOVED --}}
                                </td>

                                {{-- *** Display Order Creation Date *** --}}
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 hidden md:table-cell">
                                     {{ $item->order->created_at->format('d M Y H:i') ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    {{-- Actions --}}
                                    <div class="flex gap-2 flex-wrap justify-center">
                                         {{-- Link to View the Parent Order (Recommended) --}}
                                         {{-- Assume you have an Admin\OrderController with a 'show' method and corresponding route named 'admin.orders.show' --}}
                                         @if($item->order)
                                             <a href="{{ route('admin.transactions.show', $item->order->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                                View Order
                                            </a>
                                         @else
                                              <span class="text-gray-500 dark:text-gray-400 text-sm">No Order</span> {{-- Fallback if somehow item has no order --}}
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
                                {{-- Colspan updated to 11 (matches number of <th>) --}}
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
                 {{-- Updated variable name to $transactionItems --}}
                 @if ($transactionItems->hasPages())
                 <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                     <div>
                         {{ $transactionItems->appends(request()->query())->links('vendor.pagination.tailwind') }}
                     </div>
                 </div>
                 @endif

            </div> {{-- End inner container --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- CSRF Token Meta Tag (Still needed for Delete form) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Font Awesome (if needed for SweetAlert icons) --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" /> --}}

    {{-- Scripts Section --}}
    @push('scripts')
        {{-- Dependencies for Alerts and Delete Confirmation --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- QUICK UPDATE JAVASCRIPT HAS BEEN REMOVED --}}
        {{-- Quick updates for Order status, payment status, shipping status should be handled
             on an Order management page using an Admin\OrderController. --}}

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
                            text: "Item transaksi ini akan dihapus secara permanen. Tindakan ini tidak dapat diurungkan!", // Clarified text
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
