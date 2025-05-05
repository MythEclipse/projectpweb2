<x-app-layout>
    {{-- Slot untuk Judul Halaman di Header (konsisten dengan layout) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }} {{-- Menggunakan 'Dashboard' karena umum --}}
        </h2>
    </x-slot>

    {{-- Kontainer Utama dengan Padding Vertikal Konsisten --}}
    <div class="py-12 md:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8"> {{-- Jarak vertikal antar card --}}

            {{-- ======================================== --}}
            {{-- 1. Welcome Card                        --}}
            {{-- ======================================== --}}
            <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-lg sm:rounded-xl">
                {{-- Header Card --}}
                <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white mb-2">
                        {{ __('Selamat datang kembali, ') }} <span
                            class="text-pink-600 dark:text-pink-400">{{ Auth::user()->name }}</span>! 👋
                    </h3>
                    <p class="text-base text-gray-600 dark:text-gray-300">
                        {{ __("Berikut adalah ringkasan cepat mengenai akun Anda.") }}
                    </p>
                </div>

                {{-- Konten Card (Grid untuk Info Cepat & Tombol Aksi) --}}
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-center"> {{-- items-center agar tombol align vertikal --}}

                    {{-- Info Cepat 1: Status Akun --}}
                    <div
                        class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] hover:shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 bg-pink-100 dark:bg-pink-900/80 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    {{ __('Status Akun') }}</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ __('Aktif') }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Info Cepat 2: Tanggal Bergabung --}}
                    <div
                        class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] hover:shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/80 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    {{ __('Bergabung Sejak') }}</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ Auth::user()->created_at->format('M d, Y') }}</dd> {{-- Format tanggal tetap --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ======================================== --}}
            {{-- 2. Section: Quick Access              --}}
            {{-- ======================================== --}}
            <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white">
                        {{ __('Akses Cepat') }}
                    </h3>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-300">
                        {{ __('Navigasi ke bagian yang sering digunakan.') }}
                    </p>
                </div>
                <div class="p-6 md:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">

                    {{-- Akses Cepat: Pesanan Saya --}}
                    {{-- Rute 'orders.index' untuk user belum ada, gunakan # --}}
                    <a href="{{ route('orders.index') }}" {{-- Pastikan ini benar --}}
                    class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-lg hover:bg-gray-100 dark:hover:bg-[#3a3a3a] transition duration-300 ease-in-out transform hover:scale-[1.03] hover:shadow-md group">
                     <div class="flex items-center space-x-4">
                         <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/80 p-3 rounded-lg">
                             {{-- Icon: Shopping Bag --}}
                             <svg class="h-6 w-6 text-purple-600 dark:text-purple-400"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                 d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                         </svg>
                         </div>
                         <div>
                             <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-purple-700 dark:group-hover:text-purple-300">{{ __('Pesanan Saya') }}</h4>
                             <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Lihat riwayat pesanan Anda') }}</p>
                         </div>
                     </div>
                 </a>

                    {{-- Akses Cepat: Wishlist --}}
                    {{-- Rute 'wishlist.index' belum ada, gunakan # --}}
                    <a href="{{ route('wishlist.index') }}" {{-- <-- Ganti href="#" --}}
                        class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-lg hover:bg-gray-100 dark:hover:bg-[#3a3a3a] transition duration-300 ease-in-out transform hover:scale-[1.03] hover:shadow-md group">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 bg-red-100 dark:bg-red-900/80 p-3 rounded-lg">
                                {{-- Icon: Heart --}}
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-red-700 dark:group-hover:text-red-300">
                                    {{ __('Daftar Keinginan Saya') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Lihat item yang Anda simpan') }}
                                </p>
                            </div>
                        </div>
                    </a>

                    {{-- Akses Cepat: Pengaturan Akun (Link ke Edit Profile) --}}
                    {{-- Menggunakan route('profile.edit') yang sudah ada --}}
                    <a href="{{ route('profile.edit') }}"
                        class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-lg hover:bg-gray-100 dark:hover:bg-[#3a3a3a] transition duration-300 ease-in-out transform hover:scale-[1.03] hover:shadow-md group">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/80 p-3 rounded-lg">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m18 0h-1.5m-15.0-5.19v-1.8a1.5 1.5 0 011.83-.82l.74.15a.75.75 0 01.71.71v.17a.75.75 0 001.28.53l.4-.59a.75.75 0 011.21-.07l1.15.86a.75.75 0 00.94 0l1.15-.86a.75.75 0 011.21.07l.4.59a.75.75 0 001.28-.53v-.17a.75.75 0 01.71-.71l.74-.15a1.5 1.5 0 011.83.82v1.8M4.5 12.49v1.8a1.5 1.5 0 001.83.82l.74-.15a.75.75 0 00.71-.71V14a.75.75 0 011.28-.53l.4.59a.75.75 0 001.21.07l1.15.86a.75.75 0 01.94 0l1.15-.86a.75.75 0 001.21-.07l.4-.59a.75.75 0 011.28.53v.41a.75.75 0 00.71.71l.74.15a1.5 1.5 0 001.83-.82v-1.8m-15-4.17v-1.8a1.5 1.5 0 011.83-.82l.74.15a.75.75 0 01.71.71v.17a.75.75 0 001.28.53l.4-.59a.75.75 0 011.21-.07l1.15.86a.75.75 0 00.94 0l1.15-.86a.75.75 0 011.21.07l.4.59a.75.75 0 001.28-.53v-.17a.75.75 0 01.71-.71l.74-.15a1.5 1.5 0 011.83.82v1.8" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-green-700 dark:group-hover:text-green-300">
                                    {{ __('Pengaturan Akun') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Kelola profil & preferensi Anda') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Tempat untuk section lain (misal: Notifikasi) --}}
            {{-- ... code ... --}}

        </div>
    </div>
</x-app-layout>
