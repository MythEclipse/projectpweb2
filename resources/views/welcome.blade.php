<x-app-layout>
    <!-- Hero Section -->
    <section
        class="relative pt-20 pb-32 px-4 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50 dark:from-[#1a1a1a] dark:to-[#2d2d2d] overflow-hidden">
        <!-- Background Image -->
        <img src="{{ asset('img/Background.webp') }}" alt="Background"
            class="absolute top-0 left-0 w-full h-full object-cover z-0 opacity-30">

        <div class="container mx-auto max-w-4xl text-center relative z-10">
            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 animate-fade-in-up">
                Temukan Gaya Terbaikmu
            </h1>
            <p class="text-xl text-gray-800 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                Koleksi eksklusif baju kekinian dengan kualitas premium dan harga terjangkau. Tampil percaya diri dengan
                gaya terkini!
            </p>
            <a href="{{ route('homepage') }}"
                class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-8 py-4 rounded-lg text-lg font-medium transition-transform hover:scale-105 shadow-lg">
                Jelajahi Koleksi
            </a>
        </div>
    </section>


    <!-- Features Section -->
    <!-- Tentang Kami Section -->
    <section id="tentang" class="py-20 bg-white dark:bg-[#1a1a1a]">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-pink-600/10 rounded-2xl transform rotate-2 group-hover:rotate-1 transition-all">
                    </div>
                    <img src="{{ asset('img/Baju.png') }}" alt="Tentang Kami"
                        class="rounded-2xl relative shadow-lg transform group-hover:-translate-y-2 transition-all">
                </div>

                <div>
                    <h2 class="text-4xl font-bold mb-6 dark:text-white">
                        Cerita Dibalik Fashionku
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        Sejak 2015, kami berkomitmen menghadirkan fashion berkualitas dengan harga terjangkau. Setiap
                        produk
                        kami rancang dengan memperhatikan detail terkecil, memadukan trend global dan sentuhan lokal.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-transform hover:scale-105 shadow-lg">
                            <h3 class="text-pink-600 font-semibold mb-2">1.000+</h3>
                            <p class="text-sm dark:text-gray-400">Pelanggan Bahagia</p>
                        </div>
                        <div
                            class="p-4 bg-gray-100 dark:bg-[#2d2d2d] rounded-xl transition-transform hover:scale-105 shadow-lg">
                            <h3 class="text-pink-600 font-semibold mb-2">100+</h3>
                            <p class="text-sm dark:text-gray-400">Desain Eksklusif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hubungi Kami Section -->
    <section id="kontak" class="py-20 bg-gray-50 dark:bg-[#0a0a0a]">
        <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 dark:text-white">Hubungi Kami</h2>
                <p class="text-gray-600 dark:text-gray-400">Punya pertanyaan? Tim kami siap membantu 24/7</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-white dark:bg-[#1a1a1a] rounded-xl transition-transform hover:scale-105 shadow-lg">
                    <div
                        class="w-14 h-14 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 mx-auto flex items-center justify-center">
                        📍
                    </div>
                    <h3 class="font-medium mb-2 dark:text-white">Lokasi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Jl. Merdeka No.123<br>
                        Kuningan, Jawa Barat
                    </p>
                </div>

                <div class="p-6 bg-white dark:bg-[#1a1a1a] rounded-xl transition-transform hover:scale-105 shadow-lg">
                    <div
                        class="w-14 h-14 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 mx-auto flex items-center justify-center">
                        📞
                    </div>
                    <h3 class="font-medium mb-2 dark:text-white">Telepon</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        +62 812 3456 7890<br>
                        Senin-Minggu, 08:00 - 20:00 WIB
                    </p>
                </div>

                <div class="p-6 bg-white dark:bg-[#1a1a1a] rounded-xl transition-transform hover:scale-105 shadow-lg">
                    <div
                        class="w-14 h-14 bg-pink-100 dark:bg-pink-900 rounded-lg mb-4 mx-auto flex items-center justify-center">
                        📧
                    </div>
                    <h3 class="font-medium mb-2 dark:text-white">Email</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        info@fashionku.id<br>
                        support@fashionku.id
                    </p>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
