<x-app-layout>
    {{-- Asumsikan x-app-layout sudah menerapkan dark:bg-dark-bg pada body atau container utama --}}
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-text-dark dark:text-text-light">Daftar Transaksi</h1>
            <a href="{{ route('transactions.create') }}"
               class="bg-pink-brand hover:bg-pink-brand-dark text-white px-4 py-2 rounded inline-block text-sm transition duration-150 ease-in-out">
                Tambah Transaksi
            </a>
        </div>

        @if(session('success'))
            {{-- Alert Success --}}
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-900 dark:border-green-700 dark:text-green-100" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
             {{-- Alert Error --}}
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 dark:bg-red-900 dark:border-red-700 dark:text-red-100" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-dark-card shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                {{-- Tabel Utama --}}
                <table class="min-w-full bg-white dark:bg-dark-card text-sm">
                    {{-- Header Tabel --}}
                    <thead class="bg-gray-100 dark:bg-dark-subcard">
                        <tr>
                            {{-- Sesuaikan warna teks header untuk dark mode --}}
                            <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                            <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                            <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga</th>
                            <th class="p-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="p-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="p-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Body Tabel --}}
                    {{-- Sesuaikan warna pembatas dan teks default untuk dark mode --}}
                    <tbody class="divide-y divide-gray-200 dark:divide-dark-border text-text-dark dark:text-text-light">
                        @forelse($transactions as $tx)
                        {{-- Sesuaikan warna hover baris untuk dark mode --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-subcard">
                            <td class="p-3 whitespace-nowrap">{{ $tx->product->name ?? 'N/A' }}</td>
                            <td class="p-3 whitespace-nowrap">
                                {{ $tx->size->name ?? 'N/A' }} / {{ $tx->color->name ?? 'N/A' }}
                            </td>
                            <td class="p-3 whitespace-nowrap text-right">{{ $tx->quantity }}</td>
                            <td class="p-3 whitespace-nowrap text-right">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                            <td class="p-3 whitespace-nowrap text-right font-semibold">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $tx->user->name ?? 'N/A' }}</td>
                            <td class="p-3 whitespace-nowrap">
                                {{-- Status Transaksi (dengan penyesuaian dark mode) --}}
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                {{-- Status Pembayaran --}}
                                <span class="mt-1 block px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($tx->payment_status)
                                        @case('paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('unpaid') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                        @case('refunded') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @break
                                        @default bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @endswitch
                                ">
                                    {{ ucfirst($tx->payment_status ?? 'N/A') }}
                                </span>
                                {{-- Status Pengiriman --}}
                                <span class="mt-1 block px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($tx->shipping_status)
                                        @case('shipped') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                        @case('delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('not_shipped') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endswitch
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $tx->shipping_status ?? 'N/A')) }}
                                </span>
                            </td>
                            {{-- Sesuaikan warna teks tanggal untuk dark mode --}}
                            <td class="p-3 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $tx->created_at->format('d M Y H:i') }}</td>
                            <td class="p-3 whitespace-nowrap text-center">
                                {{-- Tombol Aksi (Detail - pakai pink-brand) --}}
                                <a href="{{ route('transactions.show', $tx->id) }}"
                                   class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand dark:hover:text-pink-500 mr-2 transition duration-150 ease-in-out" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Tombol Aksi (Edit - pakai warna kuning) --}}
                                <a href="{{ route('transactions.edit', $tx->id) }}"
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2 transition duration-150 ease-in-out" title="Edit">
                                     <i class="fas fa-edit"></i>
                                </a>
                                {{-- Tombol Aksi (Hapus - pakai warna merah) --}}
                                <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400 transition duration-150 ease-in-out" title="Hapus">
                                         <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Sesuaikan warna teks pesan kosong untuk dark mode --}}
                            <td colspan="9" class="p-6 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Tambahkan Paginasi jika diperlukan --}}
             {{-- <div class="p-4 bg-white dark:bg-dark-card border-t border-gray-200 dark:border-dark-border">
                {{-- Paginator biasanya perlu styling terpisah atau menggunakan view kustom --}}
                {{-- {{ $transactions->links() }} --}}
             {{-- </div> --}}
        </div>
    </div>

    {{-- Pastikan FontAwesome sudah ter-load jika menggunakan ikon --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" ... /> --}}

</x-app-layout>
