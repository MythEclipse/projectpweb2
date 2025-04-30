<x-app-layout>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Daftar Transaksi</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Transaksi</a> --}}

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-dark-card text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-dark-subcard">
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-left">Ukuran</th>
                        <th class="p-2 text-left">Warna</th>
                        <th class="p-2 text-left">Jumlah</th>
                        <th class="p-2 text-left">Harga</th>
                        <th class="p-2 text-left">Total</th>
                        <th class="p-2 text-left">User</th>
                        <th class="p-2 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr class="border-b dark:border-dark-border">
                        <td class="p-2">{{ $tx->product->name ?? '-' }}</td>
                        <td class="p-2">{{ $tx->size->name ?? '-' }}</td>
                        <td class="p-2">{{ $tx->color->name ?? '-' }}</td>
                        <td class="p-2">{{ $tx->quantity }}</td>
                        <td class="p-2">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                        <td class="p-2">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                        <td class="p-2">{{ $tx->user->name ?? '-' }}</td>
                        <td class="p-2">{{ $tx->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
