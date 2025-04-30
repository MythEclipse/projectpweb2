<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Detail Transaksi #{{ $transaction->id }}
            </h1>
            <div>
                <a href="{{ route('transactions.index') }}"
                   class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-dark-subcard dark:hover:bg-dark-border text-text-dark dark:text-text-light px-4 py-2 rounded mr-2 transition duration-150 ease-in-out">
                    Kembali
                </a>
                <a href="{{ route('transactions.edit', $transaction->id) }}"
                   class="text-sm bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white px-4 py-2 rounded transition duration-150 ease-in-out">
                    Edit Transaksi
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card shadow-md rounded-lg overflow-hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-text-dark dark:text-text-light">

                {{-- Kolom Kiri --}}
                <div>
                    <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2">Detail Produk & Pemesanan</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->product->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ukuran</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->size->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Warna</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->color->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->quantity }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Satuan</dt>
                            <dd class="mt-1 text-sm">Rp {{ number_format($transaction->price, 0, ',', '.') }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-bold text-gray-500 dark:text-gray-400">Total Harga</dt>
                            <dd class="mt-1 text-sm font-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pembeli</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->user->name ?? 'N/A' }} ({{ $transaction->user->email ?? 'N/A' }})</dd>
                        </div>
                    </dl>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2">Status & Informasi Tambahan</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Transaksi</dt>
                            <dd class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($transaction->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                        @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                        @case('completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endswitch
                                ">
                                    {{ ucfirst($transaction->status ?? 'N/A') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                            <dd class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($transaction->payment_status)
                                        @case('paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('unpaid') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                        @case('refunded') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @break
                                        @default bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @endswitch
                                ">
                                    {{ ucfirst($transaction->payment_status ?? 'N/A') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pengiriman</dt>
                             <dd class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($transaction->shipping_status)
                                        @case('shipped') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                        @case('delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('not_shipped') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endswitch
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->shipping_status ?? 'N/A')) }}
                                </span>
                            </dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pelacakan (Resi)</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->tracking_number ?: '-' }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                            <dd class="mt-1 text-sm whitespace-pre-wrap">{{ $transaction->notes ?: '-' }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->created_at->format('d M Y H:i:s') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diperbarui</dt>
                            <dd class="mt-1 text-sm">{{ $transaction->updated_at->format('d M Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
