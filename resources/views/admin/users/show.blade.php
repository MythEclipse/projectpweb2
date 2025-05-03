<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Detail User: {{ $user->name }}
            </h2>
            <div class="flex flex-wrap justify-end gap-2">
                {{-- Tombol kembali --}}
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-subcard border border-gray-300 dark:border-dark-border rounded-lg font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-dark-card transition">
                   <x-heroicon-s-arrow-left class="h-4 w-4 mr-1.5" />
                    Kembali
                </a>
                {{-- Tombol Edit --}}
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 transition">
                   <x-heroicon-s-pencil class="h-4 w-4 mr-1.5" />
                    Edit
                </a>
                @if (Auth::user()->id != $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Menghapus user ini bersifat permanen. Apakah Anda yakin?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 transition">
                            <x-heroicon-s-trash class="h-4 w-4 mr-1.5" />
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-card p-6 sm:p-8 rounded-2xl shadow-lg">
                <h3 class="text-xl font-semibold text-text-dark dark:text-text-light mb-6 border-b pb-3 border-gray-200 dark:border-dark-border">
                    Informasi Detail User
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div class="font-medium text-gray-600 dark:text-gray-400">ID User</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">{{ $user->id }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">{{ $user->name }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Alamat Email</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">{{ $user->email }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Status Verifikasi Email</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">
                        @if ($user->email_verified_at)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Terverifikasi
                            </span>
                            <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">({{ $user->email_verified_at->format('d M Y H:i') }})</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Status Admin</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">
                        @if ($user->is_admin)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                Admin
                            </span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                User Biasa
                            </span>
                        @endif
                    </div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Tanggal Akun Dibuat</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">
                        {{ $user->created_at->format('d M Y H:i:s') }}
                        <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">({{ $user->created_at->diffForHumans() }})</span>
                    </div>

                    <div class="font-medium text-gray-600 dark:text-gray-400">Tanggal Terakhir Diperbarui</div>
                    <div class="text-text-dark dark:text-text-light md:col-span-2">
                        {{ $user->updated_at->format('d M Y H:i:s') }}
                        <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">({{ $user->updated_at->diffForHumans() }})</span>
                    </div>
                </div>

                <div class="mt-8 pt-4 border-t border-gray-200 dark:border-dark-border text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Informasi diambil pada: {{ now()->format('d M Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
