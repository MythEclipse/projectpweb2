<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Message -->
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-text-dark dark:text-text-light">
                    <h3 class="text-lg font-medium">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Berikut adalah ringkasan aktivitas terbaru di toko Anda.
                    </p>
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stat Card 1: Total Produk -->
                <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Produk</h4>
                            <span class="text-pink-brand text-2xl">🛍️</span>
                        </div>
                        <p class="text-3xl font-bold text-text-dark dark:text-text-light">
                            {{ $totalProducts }}
                        </p>
                    </div>
                    <a href="{{ route('admin.products.index') }}"
                       class="mt-4 text-sm text-pink-brand hover:text-pink-brand-dark font-medium">
                        Kelola Produk →
                    </a>
                </div>

                <!-- Stat Card 2: Pesanan Baru -->
                <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Pesanan Baru (24 Jam)</h4>
                            <span class="text-blue-500 text-2xl">📦</span>
                        </div>
                        <p class="text-3xl font-bold text-text-dark dark:text-text-light">
                            {{ $newOrdersCount }}
                        </p>
                    </div>
                    {{-- Assuming admin.transactions.index route is now handled by an OrderController --}}
                    <a href="{{ route('admin.transactions.index') }}"
                       class="mt-4 text-sm text-blue-600 hover:text-blue-800 dark:hover:text-blue-400 font-medium">
                        Lihat Pesanan →
                    </a>
                </div>

                <!-- Stat Card 3: Total Pelanggan -->
                <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Pelanggan</h4>
                            <span class="text-green-500 text-2xl">👥</span>
                        </div>
                        <p class="text-3xl font-bold text-text-dark dark:text-text-light">
                            {{ number_format($totalCustomers, 0, ',', '.') }}
                        </p>
                    </div>
                    {{-- Add a link to customer management if you have one --}}
                    {{-- <a href="{{ route('admin.users.index') }}"
                       class="mt-4 text-sm text-green-600 hover:text-green-800 dark:hover:text-green-400 font-medium">
                        Kelola Pelanggan →
                    </a> --}}
                </div>

                <!-- Stat Card 4: Pendapatan Bulan Ini -->
                <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between hover:shadow-lg transition-shadow duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Pendapatan Bulan Ini</h4>
                            <span class="text-yellow-500 text-2xl">💰</span>
                        </div>
                        <p class="text-3xl font-bold text-text-dark dark:text-text-light">
                            {{ $formattedRevenue }}
                        </p>
                    </div>
                    {{-- Add a link to revenue reports if you have one --}}
                    {{-- <a href="{{ route('admin.reports.revenue') }}"
                       class="mt-4 text-sm text-yellow-600 hover:text-yellow-800 dark:hover:text-yellow-400 font-medium">
                        Lihat Laporan →
                    </a> --}}
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-dark-border">
                    <h3 class="text-lg font-medium text-text-dark dark:text-text-light">Aksi Cepat</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-block bg-pink-brand hover:bg-pink-brand-dark text-white px-6 py-3 rounded-lg text-sm font-medium transition duration-300 text-center">
                        Tambah Produk Baru
                    </a>
                    {{-- Assuming admin.transactions.index route is now handled by an OrderController --}}
                    <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition duration-300 text-center">
                        Proses Pesanan Tertunda
                    </a>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-dark-border">
                    <h3 class="text-lg font-medium text-text-dark dark:text-text-light">Aktivitas Terbaru (Pesanan)</h3> {{-- Updated title --}}
                </div>
                <div class="p-6">
                    {{-- Check if $recentOrders is not empty, iterate as $order --}}
                    @if ($recentOrders->isNotEmpty())
                        <ul class="space-y-4">
                            @foreach ($recentOrders as $order) {{-- Loop through recentOrders as $order --}}
                                <li class="text-sm text-gray-700 dark:text-gray-300 flex flex-wrap items-center gap-x-2">
                                    <span class="font-semibold text-green-600 dark:text-green-400">[Pesanan Baru]</span>
                                    <span>Pesanan #{{ $order->id }}</span> {{-- Access $order->id --}}
                                    {{-- Check if user exists (optional, but good practice) --}}
                                    @if ($order->user)
                                        <span>oleh <span class="font-medium">{{ $order->user->name }}</span></span> {{-- Access $order->user->name --}}
                                    @endif
                                    <span>(Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }})</span> {{-- Access $order->total_amount --}}
                                    <span class="text-gray-500 dark:text-gray-400">- {{ $order->created_at->diffForHumans() }}</span> {{-- Access $order->created_at --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        {{-- Check if $recentOrders is empty --}}
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas pesanan terbaru.</p> {{-- Updated message --}}
                    @endif

                    <div class="mt-4">
                        {{-- Link might need to go to admin.orders.index now --}}
                        {{-- <a href="{{ route('admin.activity.log') }}"
                            class="text-sm text-pink-brand hover:text-pink-brand-dark font-medium">
                            Lihat Semua Aktivitas →
                        </a> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
