<x-app-layout>
    {{-- Slot untuk Judul Halaman di Header (konsisten dengan layout) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Menggunakan padding vertikal yang sama dengan section homepage --}}
    <div class="py-12 md:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8"> {{-- Menambah space antar elemen --}}

            {{-- 1. Welcome Card (Style disamakan dengan card di homepage) --}}
            <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-lg sm:rounded-xl">
                {{-- Header Card --}}
                <div class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white mb-2">
                        {{ __("Welcome back, ") }} <span class="text-pink-600 dark:text-pink-400">{{ Auth::user()->name }}</span>! 👋
                    </h3>
                    <p class="text-base text-gray-600 dark:text-gray-300">
                        {{ __("Manage your profile and access your fashion essentials.") }}
                    </p>
                </div>

                {{-- Konten Card (Grid untuk info & action) --}}
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    {{-- Quick Stat/Info 1 (Gaya disamakan dengan stat di "Tentang Kami") --}}
                    <div class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] shadow-md hover:shadow-lg">
                        <div class="flex items-center space-x-3">
                             <div class="flex-shrink-0 bg-pink-100 dark:bg-pink-900/80 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Account Status') }}</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Active') }}</dd>
                            </div>
                        </div>
                    </div>

                     {{-- Quick Stat/Info 2 (Gaya disamakan) --}}
                    <div class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] shadow-md hover:shadow-lg">
                         <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/80 p-2 rounded-lg"> {{-- Warna bisa divariasikan jika perlu --}}
                               <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                               </svg>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Member Since') }}</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->created_at->format('M d, Y') }}</dd>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Action: Edit Profile (Tombol disamakan dengan tombol di Hero) --}}
                     <div class="md:col-span-1 flex items-center justify-start md:justify-end mt-4 md:mt-0">
                         <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-pink-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-[#1a1a1a] transition ease-in-out duration-150 transform hover:scale-105 shadow-lg">
                             <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                              </svg>
                             {{ __('Edit Profile') }}
                        </a>
                     </div>
                </div>
            </div>

            {{-- 2. Section: Quick Access (Gaya card disamakan) --}}
            <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-lg sm:rounded-xl">
                 <div class="p-6 md:p-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __("Quick Access") }} {{-- Judul lebih deskriptif --}}
                    </h3>

                    {{-- Contoh Link Cards (Gaya disamakan dengan Contact Card di homepage) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"> {{-- Potensial 3 kolom di layar besar --}}

                        {{-- Card Link 1: My Orders --}}
                        <a href="#" class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] shadow-md hover:shadow-lg group">
                            <div class="flex items-center space-x-4 mb-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-pink-100 dark:bg-pink-900/80 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5h14.813M6 14.25h12M6 14.25a2.25 2.25 0 01-2.25-2.25V7.5a2.25 2.25 0 012.25-2.25h12a2.25 2.25 0 012.25 2.25v4.5A2.25 2.25 0 0118 14.25H6M6 14.25v-3.75a.75.75 0 01.75-.75h10.5a.75.75 0 01.75.75v3.75m-12 0v-3.75" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">{{ __('My Orders') }}</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('Track your purchases, view order history, and manage returns.') }}
                            </p>
                        </a>

                         {{-- Card Link 2: My Wishlist --}}
                        <a href="#" class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] shadow-md hover:shadow-lg group">
                             <div class="flex items-center space-x-4 mb-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-pink-100 dark:bg-pink-900/80 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                 </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">{{ __('My Wishlist') }}</h4>
                            </div>
                             <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('Keep track of items you love and save them for later purchase.') }}
                            </p>
                        </a>

                        {{-- Card Link 3: Saved Addresses (Contoh) --}}
                        <a href="#" class="block p-6 bg-gray-50 dark:bg-[#2d2d2d] rounded-xl transition-all duration-300 hover:scale-[1.03] shadow-md hover:shadow-lg group">
                             <div class="flex items-center space-x-4 mb-3">
                                <div class="flex-shrink-0 w-12 h-12 bg-pink-100 dark:bg-pink-900/80 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                 </div>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">{{ __('Addresses') }}</h4>
                            </div>
                             <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ __('Manage your saved shipping and billing addresses.') }}
                            </p>
                        </a>

                        {{-- Tambahkan card link lain sesuai kebutuhan (Payment Methods, Settings, dll.) --}}

                    </div>

                 </div>
            </div>

             {{-- Tambahkan section lain jika diperlukan, misal Notifikasi, dll. --}}
             {{-- <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-lg sm:rounded-xl p-6 md:p-8">
                 <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Notifications</h3>
                 <p class="text-sm text-gray-500 dark:text-gray-400">You have no new notifications.</p>
             </div> --}}

        </div>
    </div>
</x-app-layout>
