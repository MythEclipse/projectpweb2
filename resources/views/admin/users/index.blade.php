<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Daftar User
            </h2>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                + Tambah User Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            {{-- Alerts --}}
            <div class="px-4 sm:px-0">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Success!</span> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm transition-opacity duration-500"
                         role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                @endif

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

            {{-- Search --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="flex-grow border border-gray-300 dark:border-dark-border rounded-md py-2 pl-4 pr-10 focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-bg dark:text-text-light w-full"
                           placeholder="Cari user (nama/email)...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-brand-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="space-y-6 px-4 sm:px-0">
                <div class="overflow-x-auto bg-white dark:bg-dark-bg rounded-xl shadow">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-dark-border">
                        <thead class="bg-gray-100 dark:bg-dark-card">
                            <tr class="text-gray-600 dark:text-gray-300">
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">#</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Status</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden lg:table-cell">Tgl Dibuat</th>
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-dark-bg divide-y divide-gray-100 dark:divide-dark-subcard text-gray-700 dark:text-gray-300">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-dark-card transition">
                                    <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">
                                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold break-words">{{ $user->name }}</td>
                                    <td class="px-4 py-4 break-words">{{ $user->email }}</td>
                                    <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                        @if ($user->is_admin)
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-pink-brand/10 text-pink-brand dark:bg-pink-brand-dark/10 dark:text-pink-brand-dark">
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 dark:bg-dark-subcard text-gray-700 dark:text-gray-300">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 hidden lg:table-cell whitespace-nowrap">
                                        {{ $user->created_at->format('d M Y, H:i') }}
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">({{ $user->created_at->diffForHumans() }})</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex gap-2 flex-wrap justify-center">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-pink-brand dark:text-pink-brand hover:underline text-sm" title="Lihat Detail">View</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" title="Edit User">Edit</a>
                                            @if(Auth::id() !== $user->id)
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
                                                <span class="text-gray-400 dark:text-gray-500 text-sm italic">Delete</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
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

                @if ($users->hasPages())
                    <div class="mt-6 px-4 sm:px-0">
                        {{ $users->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
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
                            confirmButtonColor: '#DC2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            background: isDarkMode ? '#0a0a0a' : '#ffffff',
                            color: isDarkMode ? '#EDEDEC' : '#1b1b18',
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
