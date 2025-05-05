<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            {{ __('Riwayat Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Optional: Display session messages if needed --}}
            @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg relative dark:bg-green-900/30 dark:border-green-700/50 dark:text-green-300" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg relative dark:bg-red-900/30 dark:border-red-700/50 dark:text-red-300" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6 text-text-dark dark:text-text-light">

                    <h3 class="text-2xl font-semibold mb-6 border-b border-gray-200 dark:border-dark-border pb-3">{{ __('Daftar Pesanan') }}</h3>

                    {{-- Loop melalui setiap ORDER --}}
                    @forelse ($orders as $order)
                        {{-- Card per Order --}}
                        <div class="mb-6 bg-gray-50 dark:bg-dark-subcard p-4 sm:p-6 rounded-xl border border-gray-200 dark:border-dark-border shadow-sm transition-shadow hover:shadow-md">

                            {{-- Header Order: ID, Tanggal, Total, Status Utama --}}
                            <div class="border-b border-gray-200 dark:border-dark-border pb-4 mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Info Utama Order --}}
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold text-text-dark dark:text-text-light mb-1">
                                        Pesanan #{{ $order->id }}
                                    </h4>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-text-light/70">
                                        Tanggal: {{ $order->created_at->isoFormat('D MMMM YYYY, HH:mm') }}
                                    </p>
                                    <p class="text-sm sm:text-base font-bold text-pink-brand dark:text-pink-brand-dark mt-2">
                                        Total Pesanan: Rp {{ number_format($order->total_amount, 0, ',', '.') }} {{-- Mengakses total_amount dari Order --}}
                                    </p>
                                </div>
                                {{-- Status Order --}}
                                <div class="space-y-1.5 flex flex-col items-start md:items-end">
                                    <h5 class="text-sm font-medium text-text-dark dark:text-text-light md:text-right">Status Pesanan:</h5>
                                    {{-- Status Utama Order --}}
                                    <span @class([
                                        'inline-block px-2.5 py-1 text-xs font-semibold rounded-full',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' => $order->status == 'pending',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' => $order->status == 'processing',
                                        'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' => $order->status == 'completed',
                                        'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' => $order->status == 'cancelled',
                                        'bg-gray-100 text-gray-800 dark:bg-dark-border dark:text-text-light/80' => !in_array($order->status, ['pending','processing','completed','cancelled']),
                                    ])>
                                        {{ Str::title(str_replace('_', ' ', $order->status)) }}
                                    </span>

                                    <h5 class="text-sm font-medium mt-3 mb-1 text-text-dark dark:text-text-light md:text-right">Status Detail:</h5>
                                     <div class="flex flex-col items-start md:items-end space-y-1.5">
                                        {{-- Status Pembayaran Order --}}
                                        <span @class([
                                            'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full',
                                            'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' => $order->payment_status == 'paid',
                                            'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' => $order->payment_status == 'unpaid',
                                            'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-300' => $order->payment_status == 'refunded', // Example for refunded
                                            'bg-gray-100 text-gray-800 dark:bg-dark-border dark:text-text-light/80' => !in_array($order->payment_status, ['paid','unpaid','refunded']),
                                        ])>
                                            @if($order->payment_status == 'paid') <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> @endif
                                            Pembayaran: {{ Str::title(str_replace('_', ' ', $order->payment_status)) }}
                                        </span>

                                        {{-- Status Pengiriman Order --}}
                                        <span @class([
                                            'inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full',
                                            'bg-gray-100 text-gray-800 dark:bg-dark-border dark:text-text-light/80' => $order->shipping_status == 'not_shipped',
                                            'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300' => $order->shipping_status == 'shipped',
                                            'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' => $order->shipping_status == 'delivered',
                                            'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' => $order->shipping_status == 'failed_delivery', // Example for failed
                                        ])>
                                            @if($order->shipping_status == 'delivered') <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> @endif
                                             @if($order->shipping_status == 'shipped') <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5h10.5" /></svg> @endif
                                            Pengiriman: {{ Str::title(str_replace('_', ' ', $order->shipping_status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div> {{-- End Order Header --}}

                            {{-- List of Transaction Items within this Order --}}
                            <div class="space-y-4">
                                <h5 class="text-base font-medium text-text-dark dark:text-text-light mb-2">{{ __('Item Pesanan:') }}</h5>
                                {{-- Loop melalui setiap ITEM dalam Order ini --}}
                                @forelse ($order->transactionItems as $item) {{-- Mengakses relasi transactionItems --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pb-4 border-b border-gray-100 dark:border-dark-border last:border-b-0 last:pb-0"> {{-- Sesuaikan layout untuk item --}}

                                        {{-- Kolom 1 (Item): Info Produk --}}
                                        <div class="sm:col-span-1 flex items-start space-x-4">
                                            {{-- Gambar Produk --}}
                                            <div class="flex-shrink-0">
                                                @if($item->product?->image_url) {{-- Akses product dari $item --}}
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                                         class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover bg-gray-200 dark:bg-dark-border">
                                                @else
                                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-gray-200 dark:bg-dark-border flex items-center justify-center text-gray-400 dark:text-gray-600">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Detail Produk Item --}}
                                            <div class="flex-grow">
                                                <h5 class="text-sm sm:text-base font-semibold text-text-dark dark:text-text-light mb-0.5">
                                                    {{ $item->product?->name ?? 'Produk Dihapus' }} {{-- Akses product dari $item --}}
                                                </h5>
                                                <p class="text-xs sm:text-sm text-gray-500 dark:text-text-light/70">
                                                    Ukuran: <span class="font-medium">{{ $item->size?->name ?? 'N/A' }}</span> {{-- Akses size dari $item --}}
                                                </p>
                                                <p class="text-xs sm:text-sm text-gray-500 dark:text-text-light/70">
                                                    Warna: <span class="font-medium">{{ $item->color?->name ?? 'N/A' }}</span> {{-- Akses color dari $item --}}
                                                    {{-- Optional Color Swatch --}}
                                                    @if($item->color?->code)
                                                        <span class="inline-block w-3 h-3 rounded-full ml-1 border border-gray-400 dark:border-dark-border" style="background-color: {{ $item->color->code }};"></span>
                                                    @endif
                                                </p>
                                                <p class="text-xs sm:text-sm text-gray-500 dark:text-text-light/70">
                                                    Jumlah: <span class="font-medium">{{ $item->quantity }}</span> {{-- Akses quantity dari $item --}}
                                                </p>
                                                <p class="text-sm sm:text-base font-bold text-pink-brand dark:text-pink-brand-dark mt-1">
                                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }} {{-- Hitung subtotal item --}}
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Kolom 2 & 3 (Item): Bisa digunakan untuk detail item tambahan jika ada,
                                             atau dibiarkan kosong/digunakan untuk tata letak --}}

                                        {{-- Catatan: Informasi shipping, payment, notes, dll. adalah di level Order, bukan Item.
                                             Mereka sudah ditampilkan di header atau footer Order card. --}}

                                    </div> {{-- End Item Grid --}}
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-text-light/70 italic">{{ __('Tidak ada item dalam pesanan ini.') }}</p>
                                @endforelse
                            </div> {{-- End List of Items --}}

                            {{-- Footer Order: Alamat, Resi, Catatan --}}
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-dark-border text-xs sm:text-sm text-gray-600 dark:text-text-light/80 space-y-1.5">
                                 <p><strong>Metode Bayar:</strong> {{ Str::title(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</p> {{-- Akses dari Order --}}
                                <div>
                                    <strong>Alamat Kirim:</strong>
                                    <p class="pl-2">{{ $order->shipping_address ?? '-' }}</p> {{-- Akses dari Order --}}
                                </div>
                                 @if($order->tracking_number && in_array($order->shipping_status, ['shipped', 'delivered'])) {{-- Akses dari Order --}}
                                     <p><strong>No. Resi:</strong> <span class="font-semibold text-text-dark dark:text-text-light bg-gray-200 dark:bg-dark-border px-1.5 py-0.5 rounded">{{ $order->tracking_number }}</span></p>
                                    @endif
                                    @if($order->notes) {{-- Akses dari Order --}}
                                        <p class="mt-2 pt-2 border-t border-gray-200 dark:border-dark-border"><strong>Catatan:</strong> {{ $order->notes }}</p>
                                    @endif
                            </div> {{-- End Order Footer --}}


                            {{-- Optional: Button Detail --}}
                            {{-- Tombol ini seharusnya menuju detail Order, bukan item. --}}
                            {{--
                            <div class="mt-4 pt-2 border-t border-gray-200 dark:border-dark-border md:text-right">
                                {{-- Pastikan route 'orders.show' ada dan menerima ID Order --}}
                                {{-- <a href="{{ route('orders.show', $order->id) }}" class="text-sm text-pink-brand dark:text-pink-brand-dark hover:underline">
                                    Lihat Detail Pesanan
                                </a>
                            </div>
                            --}}

                        </div> {{-- End Order Card --}}
                    @empty
                        {{-- Tampilan Jika Tidak Ada Order --}}
                         <div class="text-center py-16 border border-dashed border-gray-300 dark:border-dark-border rounded-lg">
                             <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                             <h4 class="mt-4 text-lg font-medium text-text-dark dark:text-text-light">{{ __("Belum Ada Pesanan") }}</h4>
                            <p class="mt-2 text-sm text-gray-500 dark:text-text-light/70">{{ __("Anda belum melakukan pemesanan.") }}</p>
                            <a href="{{ route('homepage') }}" class="mt-6 inline-flex items-center px-6 py-2.5 bg-pink-brand border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card transition ease-in-out duration-150">
                                {{ __('Lihat Produk') }}
                            </a>
                        </div>
                    @endforelse

                    {{-- Tampilkan Link Paginasi --}}
                    @if ($orders->hasPages())
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @endif

                </div> {{-- End p-6 --}}
            </div> {{-- End bg-white --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}
</x-app-layout>
