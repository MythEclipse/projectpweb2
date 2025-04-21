<x-app-layout>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        {{-- <x-sidebar/> --}}

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Produk</h1>
            <p class="text-gray-600">Gunakan menu di samping untuk mengelola produk, kategori, dan pesanan.</p>

            {{-- Tempatkan konten halaman produk di sini --}}
            @yield('content')
        </main>
    </div>
</x-app-layout>
