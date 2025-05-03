<x-app-layout>
    {{-- Slot Header untuk Judul Halaman --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Detail User: {{ $user->name }}
            </h2>
            {{-- Tombol Aksi di Header --}}
            <div class="flex flex-wrap justify-end gap-2">
                {{-- Tombol kembali ke daftar --}}
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-300 dark:focus:ring-gray-600 disabled:opacity-25 transition">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                   </svg>
                    Kembali
                </a>
                {{-- Tombol Edit --}}
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-300 disabled:opacity-25 transition">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                   </svg>
                    Edit
                </a>
                 {{-- Tombol Hapus hanya jika BUKAN user yang sedang login --}}
                @if (Auth::user()->id != $user->id)
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Menghapus user ini bersifat permanen. Apakah Anda yakin?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Kontainer Utama Detail --}}
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-lg transition-all">

                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 border-b pb-3 border-gray-200 dark:border-gray-700">
                    Informasi Detail User
                </h3>

                {{-- Grid untuk menampilkan detail key-value --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    {{-- Kolom Label --}}
                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">ID User</div>
                    {{-- Kolom Value --}}
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">{{ $user->id }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Nama Lengkap</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">{{ $user->name }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Alamat Email</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">{{ $user->email }}</div>

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Status Verifikasi Email</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">
                        @if ($user->email_verified_at)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Terverifikasi
                            </span>
                            <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">(pada {{ $user->email_verified_at->format('d M Y H:i') }})</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Status Admin</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">
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

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Tanggal Akun Dibuat</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">
                        {{ $user->created_at->format('d M Y H:i:s') }}
                        <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">({{ $user->created_at->diffForHumans() }})</span>
                    </div>

                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Tanggal Terakhir Diperbarui</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">
                        {{ $user->updated_at->format('d M Y H:i:s') }}
                        <span class="ml-1 text-gray-500 dark:text-gray-400 text-xs">({{ $user->updated_at->diffForHumans() }})</span>
                    </div>

                    {{-- Anda bisa menambahkan detail lain jika diperlukan di sini --}}
                    {{-- Contoh: Menampilkan data dari relasi (jika ada) --}}
                    {{--
                    <div class="font-medium text-gray-600 dark:text-gray-400 md:col-span-1">Jumlah Postingan</div>
                    <div class="text-gray-800 dark:text-gray-200 md:col-span-2">{{ $user->posts->count() }}</div>
                    --}}
                </div>

                 {{-- Footer Informasi Tambahan (jika perlu) --}}
                 <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                     <p class="text-xs text-gray-500 dark:text-gray-400">
                         Informasi diambil pada: {{ now()->format('d M Y H:i:s') }}
                     </p>
                 </div>

            </div>

            {{-- Jika Anda ingin menambahkan bagian lain, misal riwayat aktivitas user --}}
            {{-- <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-lg mt-6"> ... </div> --}}
        </div>
    </div>
</x-app-layout>
