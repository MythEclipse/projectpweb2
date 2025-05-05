{{-- resources/views/admin/transactions/show.blade.php --}}
<x-app-layout>
    <div class="py-12">
        {{-- @php
            dd($transactionItem);
        @endphp --}}
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 px-4 sm:px-0">
                <h1 class="text-2xl font-bold text-text-dark dark:text-text-light">
                    Detail Item Transaksi #{{ $transactionItem->id }}
                    @if($transactionItem->order)
                        <span class="text-lg font-normal text-gray-600 dark:text-gray-400">(Pesanan #{{ $transactionItem->order->id }})</span>
                    @endif
                </h1>
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.transactions.index') }}"
                       class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-dark-subcard dark:hover:bg-dark-border text-text-dark dark:text-text-light px-4 py-2 rounded-md mr-2 transition duration-150 ease-in-out inline-block">
                        Kembali ke Daftar Item
                    </a>
                    @if($transactionItem->order)
                         <a href="{{ route('admin.transactions.show', $transactionItem->order->id) }}"
                            class="text-sm bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-150 ease-in-out inline-block">
                             Lihat Detail Pesanan
                         </a>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card shadow-md rounded-lg overflow-hidden p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 text-text-dark dark:text-text-light">

                    <div>
                        <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2">Detail Item</h2>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                                <dd class="mt-1 text-sm">{{ $transactionItem->product->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ukuran</dt>
                                <dd class="mt-1 text-sm">{{ $transactionItem->size->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Warna</dt>
                                <dd class="mt-1 text-sm">{{ $transactionItem->color->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                                <dd class="mt-1 text-sm">{{ $transactionItem->quantity }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Satuan</dt>
                                <dd class="mt-1 text-sm">Rp {{ number_format($transactionItem->price, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-bold text-gray-500 dark:text-gray-400">Total Harga (Item)</dt>
                                <dd class="mt-1 text-sm font-bold text-pink-brand dark:text-pink-brand">Rp {{ number_format($transactionItem->quantity * $transactionItem->price, 0, ',', '.') }}</dd>
                            </div>
                             <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Item Dibuat</dt>
                                {{-- *** Menggunakan null-safe operator ?-> *** --}}
                                <dd class="mt-1 text-sm">{{ $transactionItem->created_at?->format('d M Y H:i:s') }}</dd>
                            </div>
                             <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Item Terakhir Diperbarui</dt>
                                {{-- *** Menggunakan null-safe operator ?-> *** --}}
                                <dd class="mt-1 text-sm">{{ $transactionItem->updated_at?->format('d M Y H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2">Detail Pesanan Induk</h2>
                        @if($transactionItem->order)
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pesanan</dt>
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembeli</dt>
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->user->name ?? 'N/A' }} ({{ $transactionItem->order->user->email ?? 'N/A' }})</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Pengiriman</dt>
                                    <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $transactionItem->order->shipping_address ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Jumlah Pesanan</dt>
                                    <dd class="mt-1 text-sm font-bold text-pink-brand dark:text-pink-brand">Rp {{ number_format($transactionItem->order->total_amount, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pesanan Utama</dt>
                                    <dd class="mt-1 text-sm">
                                        @php
                                            $orderStatus = $transactionItem->order->status ?? 'unknown';
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
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit {{ $badgeClasses }}">
                                            {{ ucfirst($orderStatus) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                                    <dd class="mt-1 text-sm">
                                        @php
                                            $paymentStatus = $transactionItem->order->payment_status ?? 'N/A';
                                            $badgeClasses = '';
                                            switch($paymentStatus) {
                                                case 'paid':
                                                case 'settlement':
                                                case 'capture': $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                                case 'unpaid':
                                                case 'deny':
                                                case 'expire':
                                                case 'cancel':
                                                case 'failure': $badgeClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'; break;
                                                case 'pending': $badgeClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                                default: $badgeClasses = 'bg-gray-100 text-gray-800 dark:bg-dark-subcard dark:text-gray-400'; break;
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit {{ $badgeClasses }}">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</dt>
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->payment_method ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengiriman</dt>
                                    <dd class="mt-1 text-sm">
                                         @php
                                            $shippingStatus = $transactionItem->order->shipping_status ?? 'N/A';
                                            $badgeClasses = '';
                                            switch($shippingStatus) {
                                                case 'shipped': $badgeClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'; break;
                                                case 'delivered': $badgeClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'; break;
                                                case 'not_shipped': $badgeClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; break;
                                                default: $badgeClasses = 'bg-gray-100 text-gray-800 dark:bg-dark-subcard dark:text-gray-400'; break;
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit {{ $badgeClasses }}">
                                             {{ ucfirst(str_replace('_', ' ', $shippingStatus)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pelacakan (Resi)</dt>
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->tracking_number ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Pesanan</dt>
                                    <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $transactionItem->order->notes ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pesanan Dibuat</dt>
                                    {{-- *** Menggunakan null-safe operator ?-> *** --}}
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->created_at?->format('d M Y H:i:s') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pesanan Terakhir Diperbarui</dt>
                                    {{-- *** Menggunakan null-safe operator ?-> *** --}}
                                    <dd class="mt-1 text-sm">{{ $transactionItem->order->updated_at?->format('d M Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Item ini tidak terhubung dengan pesanan induk.</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
