<x-app-layout>
    {{-- Slot untuk header jika layout Anda memilikinya --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Menggunakan nama pengguna yang sedang login --}}
                    <h3 class="text-lg font-medium">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Berikut adalah ringkasan aktivitas terbaru di toko Anda.
                    </p>
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stat Card 1: Total Produk -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Produk</h4>
                            <span class="text-pink-500 text-2xl">🛍️</span> <!-- Ganti dengan ikon SVG jika ada -->
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{-- Menampilkan data dinamis dari controller --}}
                            {{ $totalProducts }}
                        </p>
                    </div>
                    {{-- Pastikan route 'admin.products.index' sudah didefinisikan --}}
                    <a href="{{ route('products.index') }}"
                        class="mt-4 text-sm text-pink-600 hover:text-pink-800 dark:hover:text-pink-400 font-medium">
                        Kelola Produk →
                    </a>
                </div>

                <!-- Stat Card 2: Pesanan Baru -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Pesanan Baru (24
                                Jam)</h4>
                            <span class="text-blue-500 text-2xl">📦</span> <!-- Ganti dengan ikon SVG jika ada -->
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{-- Menampilkan data dinamis dari controller --}}
                            {{ $newOrdersCount }}
                        </p>
                    </div>
                    {{-- Pastikan route 'admin.orders.index' sudah didefinisikan --}}
                    <a href="{{ route('transactions.index') }}"
                        class="mt-4 text-sm text-blue-600 hover:text-blue-800 dark:hover:text-blue-400 font-medium">
                        Lihat Pesanan →
                    </a>
                </div>

                <!-- Stat Card 3: Total Pelanggan -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Pelanggan
                            </h4>
                            <span class="text-green-500 text-2xl">👥</span> <!-- Ganti dengan ikon SVG jika ada -->
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{-- Menampilkan data dinamis dari controller dengan format --}}
                            {{ number_format($totalCustomers, 0, ',', '.') }}
                        </p>
                    </div>
                    {{-- Pastikan route 'admin.users.index' sudah didefinisikan --}}
                    {{-- <a href="{{ route('admin.users.index') }}"
                        class="mt-4 text-sm text-green-600 hover:text-green-800 dark:hover:text-green-400 font-medium">
                        Manajemen Pengguna →
                    </a> --}}
                </div>

                <!-- Stat Card 4: Pendapatan Bulan Ini -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Pendapatan Bulan
                                Ini</h4>
                            <span class="text-yellow-500 text-2xl">💰</span> <!-- Ganti dengan ikon SVG jika ada -->
                        </div>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{-- Menampilkan data dinamis dari controller (sudah diformat) --}}
                            {{ $formattedRevenue }}
                        </p>
                    </div>
                    {{-- Pastikan route 'admin.reports.sales' sudah didefinisikan --}}
                    {{-- <a href="{{ route('admin.reports.sales') }}"
                        class="mt-4 text-sm text-yellow-600 hover:text-yellow-800 dark:hover:text-yellow-400 font-medium">
                        Lihat Laporan →
                    </a> --}}
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aksi Cepat</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Pastikan route 'admin.products.create' sudah didefinisikan --}}
                    <a href="{{ route('products.create') }}"
                        class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition duration-300 text-center">
                        Tambah Produk Baru
                    </a>
                    {{-- Pastikan route 'admin.orders.index' bisa menerima parameter status --}}
                    <a href="{{ route('transactions.index', ['status' => 'pending']) }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition duration-300 text-center">
                        Proses Pesanan Tertunda
                    </a>
                    {{-- Pastikan route 'admin.settings.index' sudah didefinisikan --}}
                    {{-- <a href="{{ route('admin.settings.index') }}"
                        class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition duration-300 text-center">
                        Pengaturan Toko
                    </a> --}}
                </div>
            </div>

            <!-- Recent Activity Section (Optional) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aktivitas Terbaru</h3>
                </div>
                <div class="p-6">
                    {{-- Loop data aktivitas (transaksi) terbaru dari controller --}}
                    @if ($recentTransactions->isNotEmpty())
                        <ul class="space-y-4">
                            @foreach ($recentTransactions as $transaction)
                                <li
                                    class="text-sm text-gray-700 dark:text-gray-300 flex flex-wrap items-center gap-x-2">
                                    <span class="font-semibold text-green-600 dark:text-green-400">[Pesanan Baru]</span>
                                    <span>Pesanan #{{ $transaction->id }}</span>
                                    @if ($transaction->user)
                                        {{-- Cek jika relasi user ada --}}
                                        <span>oleh <span
                                                class="font-medium">{{ $transaction->user->name }}</span></span>
                                    @endif
                                    {{-- Format total transaksi --}}
                                    <span>(Total: Rp {{ number_format($transaction->total, 0, ',', '.') }})</span>
                                    {{-- Tampilkan waktu relatif --}}
                                    <span class="text-gray-500 dark:text-gray-400">-
                                        {{ $transaction->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                            {{-- Tambahkan loop untuk aktivitas lain jika ada (misal: user baru) --}}
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru.</p>
                    @endif

                    <div class="mt-4">
                        {{-- Pastikan route 'admin.activity.log' sudah didefinisikan --}}
                        {{-- <a href="{{ route('admin.activity.log') }}"
                            class="text-sm text-pink-600 hover:text-pink-800 dark:hover:text-pink-400 font-medium">
                            Lihat Semua Aktivitas →
                        </a> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
