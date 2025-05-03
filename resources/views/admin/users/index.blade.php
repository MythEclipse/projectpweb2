<x-app-layout>
    {{-- Header Title & Action Button --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Daftar User
            </h2>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
               {{-- Menggunakan warna biru sebagai contoh, sesuaikan jika perlu --}}
                + Tambah User Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">

            {{-- Session Status / Alerts (Mirip Produk) --}}
            <div class="px-4 sm:px-0">
                @if (session('success'))
                    <div id="alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') }}
                    </div>
                @endif

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

            {{-- Actions: Search (Mirip Produk) --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6">
                 {{-- Tambahkan form search jika diperlukan, contoh: --}}
                 <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                    class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-blue-500 focus:border-blue-500 dark:bg-[#0a0a0a] dark:text-white w-full" {{-- Warna focus disesuaikan --}}
                           placeholder="Cari user (nama/email)...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="space-y-6 px-4 sm:px-0">
                <div class="overflow-x-auto bg-white dark:bg-[#0a0a0a] rounded-xl shadow">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-[#3E3E3A]">
                        <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                            <tr class="text-gray-600 dark:text-gray-300">
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">#</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Status</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden lg:table-cell">Tgl Dibuat</th>
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-gray-100 dark:divide-[#2d2d2d] text-gray-700 dark:text-gray-300">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                                    {{-- Penomoran Paginasi yang Benar --}}
                                    <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold break-words">{{ $user->name }}</td>
                                    <td class="px-4 py-4 break-words">{{ $user->email }}</td>
                                    {{-- Status Admin dengan Badge --}}
                                    <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                        @if ($user->is_admin)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                Admin
                                            </span>
                                        @else
                                             <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    {{-- Format Tanggal --}}
                                    <td class="px-4 py-4 hidden lg:table-cell whitespace-nowrap">
                                        {{ $user->created_at->format('d M Y, H:i') }}
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">({{ $user->created_at->diffForHumans() }})</span>
                                    </td>
                                    {{-- Aksi dengan Link dan Form Delete --}}
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex gap-2 flex-wrap justify-center">
                                             {{-- Tombol View Detail --}}
                                             <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" title="Lihat Detail">
                                                View
                                             </a>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" title="Edit User">
                                                Edit
                                            </a>
                                            {{-- Tombol Hapus (jika bukan user saat ini) --}}
                                            @if(Auth::user()->id != $user->id)
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:underline text-sm" title="Hapus User">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline -mt-px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Tampilkan teks disabled atau sembunyikan tombol delete --}}
                                                <span class="text-gray-400 dark:text-gray-500 text-sm italic" title="Tidak dapat menghapus diri sendiri">Delete</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Tampilan jika tidak ada data --}}
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400"> {{-- Sesuaikan colspan --}}
                                        Tidak ada data user ditemukan.
                                        @if(request('search'))
                                            <span class="block text-sm">Coba ubah kata kunci pencarian Anda.</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (Gunakan view Tailwind) --}}
                @if ($users->hasPages())
                    <div class="mt-6 px-4 sm:px-0">
                         {{-- Pastikan Anda sudah publish pagination view: php artisan vendor:publish --tag=laravel-pagination --}}
                        {{ $users->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scripts Section (Mirip Produk) --}}
    @push('scripts')
        {{-- Pastikan SweetAlert2 dan AlpineJS sudah di-load di layout utama atau load di sini --}}
        {{-- Contoh load jika belum ada: --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Script untuk SweetAlert Konfirmasi Delete
                const deleteForms = document.querySelectorAll('.delete-form');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const isDarkMode = document.documentElement.classList.contains('dark');

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "User ini akan dihapus secara permanen!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DC2626', // Merah (sesuaikan jika perlu)
                            cancelButtonColor: '#6b7280', // Abu-abu netral
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            background: isDarkMode ? '#1f2937' : '#ffffff', // Latar belakang dark/light
                            color: isDarkMode ? '#f3f4f6' : '#1f2937',      // Warna teks dark/light
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit(); // Submit form jika dikonfirmasi
                            }
                        });
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
