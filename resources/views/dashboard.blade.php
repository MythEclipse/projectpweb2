<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="mb-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-pink-600 dark:text-pink-400 mb-4">
                        Mulai Belanja Sekarang!
                    </h2>
                    <p class="text-gray-600 dark:text-[#EDEDEC] max-w-2xl mx-auto text-lg">
                        Temukan pengalaman berbelanja terbaik dengan berbagai keuntungan eksklusif
                    </p>
                </div>

                <!-- Fitur Card Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Card 1 -->
                    <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl border border-gray-200 dark:border-[#3E3E3A] hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-[#EDEDEC] mb-2">Belanja Mudah</h3>
                        <p class="text-gray-600 dark:text-gray-300">Proses checkout cepat dan aman dengan berbagai metode pembayaran</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl border border-gray-200 dark:border-[#3E3E3A] hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-[#EDEDEC] mb-2">Pengiriman Cepat</h3>
                        <p class="text-gray-600 dark:text-gray-300">Dikirim langsung dari gudang kami dengan jaminan tepat waktu</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white dark:bg-[#1a1a1a] p-6 rounded-xl border border-gray-200 dark:border-[#3E3E3A] hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-[#EDEDEC] mb-2">Garansi 100%</h3>
                        <p class="text-gray-600 dark:text-gray-300">Kepuasan pelanggan adalah prioritas utama kami</p>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="text-center">
                    <a href="{{ route('homepage') }}"
                       class="inline-flex items-center px-8 py-4 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-lg font-semibold transition-colors
                              dark:bg-pink-700 dark:hover:bg-pink-800">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Mulai Belanja Sekarang
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
