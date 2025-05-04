<x-app-layout>
    {{-- Hero Section --}}
    <section
        x-data="{ loaded: false }" x-init="() => { setTimeout(() => loaded = true, 100) }"
        class="relative pt-24 pb-36 lg:pt-32 lg:pb-48 px-4 lg:px-8 bg-gradient-to-br from-pink-50 to-purple-50 dark:from-gray-900 dark:to-[#1a1a1a] overflow-hidden">

        {{-- Background Elements --}}
        <div class="absolute inset-0 z-0 opacity-20 dark:opacity-15">
             {{-- Gambar background lebih subtle --}}
            <img src="{{ asset('img/Background.webp') }}" alt="Background Pattern" loading="lazy"
                class="w-full h-full object-cover">
            {{-- Overlay Gradient Halus untuk kontras teks --}}
            <div class="absolute inset-0 bg-gradient-to-b from-white/30 via-white/0 to-white/0 dark:from-black/40 dark:via-transparent"></div>
        </div>

        {{-- Subtle Shapes (Optional Decorative Elements) --}}
         <div class="absolute top-1/4 left-10 w-32 h-32 bg-pink-200 dark:bg-pink-900/50 rounded-full opacity-20 blur-2xl -translate-x-10 animate-pulse"></div>
         <div class="absolute bottom-1/4 right-10 w-48 h-48 bg-purple-200 dark:bg-purple-900/50 rounded-full opacity-15 blur-3xl translate-x-10 animation-delay-400 animate-pulse"></div>

        <div class="container mx-auto max-w-4xl text-center relative z-10">
            {{-- Judul dengan animasi masuk lebih halus --}}
            <h1 class="text-5xl lg:text-7xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight transition-all duration-1000 ease-out"
                :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'">
                Temukan <span class="text-pink-600 dark:text-pink-400">Gaya Terbaikmu</span>
            </h1>
            {{-- Paragraf dengan animasi masuk delay --}}
            <p class="text-lg lg:text-xl text-gray-700 dark:text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed transition-all duration-1000 ease-out delay-200"
                 :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-5'">
                Koleksi eksklusif baju kekinian dengan kualitas premium dan harga terjangkau. Tampil percaya diri dengan
                gaya terkini!
            </p>
            {{-- Tombol CTA dengan animasi masuk & efek hover lebih bagus --}}
            <a href="{{ route('homepage') }}" data-turbo="false"
                class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-1000 ease-out delay-300 transform hover:scale-105 hover:shadow-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :class="loaded ? 'opacity-100 scale-100' : 'opacity-0 scale-90'">
                Jelajahi Koleksi
                 <i class="fas fa-arrow-right ml-2 text-sm"></i> {{-- Jika pakai Font Awesome --}}
                {{-- Atau SVG inline: --}}
                {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="inline-block w-5 h-5 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg> --}}
            </a>
        </div>
    </section>


    {{-- Tentang Kami Section dengan Animasi Scroll --}}
    <section id="tentang" class="py-24 bg-white dark:bg-[#111111]">
        <div x-data="{ shown: false }" x-intersect.once="shown = true" class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-16 items-center transition-all duration-1000 ease-out"
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                {{-- Kolom Gambar --}}
                <div class="relative group">
                    {{-- Elemen dekoratif background, animasi lebih halus --}}
                    <div class="absolute inset-0 bg-gradient-to-tr from-pink-100 to-purple-100 dark:from-pink-900/30 dark:to-purple-900/30 rounded-2xl transform -rotate-2 group-hover:rotate-0 group-hover:scale-105 transition-transform duration-500 ease-out -z-10 blur-sm group-hover:blur-none">
                    </div>
                    <img src="{{ asset('img/Baju.png') }}" alt="Model Baju Fashionku" loading="lazy"
                         class="rounded-2xl relative shadow-xl transform transition-all duration-500 ease-out group-hover:scale-105 group-hover:-translate-y-2 border-4 border-white dark:border-gray-800">
                </div>

                {{-- Kolom Teks --}}
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-6 text-gray-900 dark:text-white tracking-tight">
                        Cerita Dibalik <span class="text-pink-600 dark:text-pink-400">Fashionku</span>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                        Sejak 2015, kami berkomitmen menghadirkan fashion berkualitas dengan harga terjangkau. Setiap produk
                        kami rancang dengan memperhatikan detail terkecil, memadukan trend global dan sentuhan lokal untuk gaya Anda yang unik.
                    </p>
                    {{-- Stats dengan ikon dan hover lebih baik --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-xl transition-all duration-300 hover:shadow-lg hover:bg-white dark:hover:bg-gray-700/50 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700/50">
                            {{-- Ganti dengan SVG Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-3 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-pink-600 dark:text-pink-400 mb-1">1.000+</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pelanggan Bahagia</p>
                        </div>
                        <div class="p-5 bg-gray-50 dark:bg-gray-800/50 rounded-xl transition-all duration-300 hover:shadow-lg hover:bg-white dark:hover:bg-gray-700/50 transform hover:-translate-y-1 border border-gray-100 dark:border-gray-700/50">
                            {{-- Ganti dengan SVG Icon --}}
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-3 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-600 dark:text-purple-400 mb-1">100+</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Desain Eksklusif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Hubungi Kami Section dengan Animasi Scroll --}}
    <section id="kontak" class="py-24 bg-gray-50 dark:bg-[#0a0a0a]">
        <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
             {{-- Judul Section dengan Animasi --}}
            <div x-data="{ shown: false }" x-intersect.once="shown = true"
                 class="text-center mb-16 transition-all duration-1000 ease-out"
                 :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-gray-900 dark:text-white tracking-tight">Hubungi Kami</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                    Punya pertanyaan atau butuh bantuan? Tim support kami siap melayani Anda.
                </p>
            </div>

            {{-- Grid Kontak Cards dengan Animasi Staggered --}}
            <div class="grid md:grid-cols-3 gap-8 text-center">
                 {{-- Card Lokasi --}}
                 <div x-data="{ shown: false }" x-intersect.once="shown = true"
                      class="p-6 lg:p-8 bg-white dark:bg-[#1a1a1a] rounded-2xl transition-all duration-1000 ease-out transform hover:-translate-y-2 hover:shadow-xl shadow-lg border border-transparent hover:border-pink-200 dark:hover:border-pink-800"
                       :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                    <div class="w-16 h-16 bg-pink-100 dark:bg-pink-900/50 rounded-xl mb-6 mx-auto flex items-center justify-center text-pink-600 dark:text-pink-400">
                        {{-- Ganti Emoji dengan Heroicon MapPin --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Lokasi Kami</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Jl. Merdeka No.123<br>
                        Kuningan, Jawa Barat
                    </p>
                 </div>

                {{-- Card Telepon --}}
                 <div x-data="{ shown: false }" x-intersect.once="shown = true"
                       class="p-6 lg:p-8 bg-white dark:bg-[#1a1a1a] rounded-2xl transition-all duration-1000 ease-out delay-100 transform hover:-translate-y-2 hover:shadow-xl shadow-lg border border-transparent hover:border-purple-200 dark:hover:border-purple-800"
                       :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/50 rounded-xl mb-6 mx-auto flex items-center justify-center text-purple-600 dark:text-purple-400">
                       {{-- Ganti Emoji dengan Heroicon Phone --}}
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 6.75z" />
                       </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Telepon</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        +62 812 3456 7890<br>
                        Senin-Minggu, 08:00 - 20:00
                    </p>
                 </div>

                 {{-- Card Email --}}
                 <div x-data="{ shown: false }" x-intersect.once="shown = true"
                       class="p-6 lg:p-8 bg-white dark:bg-[#1a1a1a] rounded-2xl transition-all duration-1000 ease-out delay-200 transform hover:-translate-y-2 hover:shadow-xl shadow-lg border border-transparent hover:border-teal-200 dark:hover:border-teal-800"
                       :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                     <div class="w-16 h-16 bg-teal-100 dark:bg-teal-900/50 rounded-xl mb-6 mx-auto flex items-center justify-center text-teal-600 dark:text-teal-400">
                       {{-- Ganti Emoji dengan Heroicon Envelope --}}
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                       </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Email</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        info@fashionku.id<br>
                        support@fashionku.id
                    </p>
                 </div>
            </div>
        </div>
    </section>

     {{-- Optional: Section lain bisa ditambahkan di sini (e.g., Testimonials, Featured Products) --}}

</x-app-layout>

{{-- Jangan lupa tambahkan style untuk animasi delay jika belum ada --}}
<style>
.animation-delay-200 { animation-delay: 0.2s; }
.animation-delay-400 { animation-delay: 0.4s; }
</style>
