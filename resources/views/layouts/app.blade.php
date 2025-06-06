@props(['header'])

@php
    $isAdmin = auth()->check() && auth()->user()->is_admin;
    $onAdminPage = request()->is('admin*') || request()->is('transactions*');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: false,
    sidebarOpen: window.innerWidth >= 768
}" x-init="// Set dark mode from localStorage
darkMode = localStorage.getItem('dark-mode') === 'true';

// Responsive sidebar
window.addEventListener('resize', () => {
    sidebarOpen = window.innerWidth >= 768;
});

// Sync dark mode state
$watch('darkMode', value => {
    localStorage.setItem('dark-mode', value);
    document.documentElement.classList.toggle('dark', value);
});">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @livewireStyles --}}

    <!-- Inline script untuk apply dark mode sebelum render -->
    <script>
        (function() {
            const isDark = localStorage.getItem('dark-mode') === 'true';
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const darkMode = isDark !== null ? isDark : systemDark;
            document.documentElement.classList.toggle('dark', darkMode);
            localStorage.setItem('dark-mode', darkMode);
        })();
    </script>

    <link rel="icon" type="image/png" href="{{ asset('icon.svg') }}">
    <title>{{ config('app.name', 'FashionKu') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased min-h-screen">
    <div class="flex min-h-screen">

        @if ($isAdmin && $onAdminPage)
            @include('layouts.sidebar')
        @endif

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300">
            <!-- Top Navigation -->
            {{-- @if (request()->path() !== '/') --}}
            @include('layouts.navigation')
            {{-- @endif --}}

            <!-- Header -->
            @isset($header)
                <header
                    class="bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-sm shadow border-b border-gray-100 dark:border-[#3E3E3A]">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-4 mb-20 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>

        @if ($isAdmin && $onAdminPage)
            <!-- Drawer Handle -->
            <div class="fixed left-0 top-1/2 transform md:hidden -translate-y-1/2 z-50">
                <button @click="sidebarOpen = !sidebarOpen" x-show="window.innerWidth < 768 || !sidebarOpen"
                    class="w-3 h-16 bg-gray-800 dark:bg-gray-600 text-white rounded-r-lg cursor-pointer hover:bg-gray-700 transition-all shadow-lg">
                </button>
            </div>
        @endif

        {{-- >>> START: Floating Cart Button <<< --}}
        @auth {{-- Hanya tampilkan jika user login --}}
            {{-- Opsional: Sembunyikan jika sedang di halaman cart itu sendiri --}}
            @if (!request()->routeIs('cart.index'))
                {{-- Container untuk kedua versi tombol --}}
                {{-- Atur `right` agar tidak bertabrakan dengan tombol wishlist (jika ada) --}}
                <div class="fixed bottom-6 right-6 z-40"> {{-- Posisi fixed, bottom-right, z-index. Sesuaikan `right` jika perlu --}}

                    {{-- ============================================= --}}
                    {{-- Versi Mobile (Simple)                       --}}
                    {{-- Tampil default, hilang di layar md ke atas --}}
                    {{-- ============================================= --}}
                    <a href="{{ route('cart.index') }}" title="View Cart"
                        class="block md:hidden relative {{-- Relative untuk positioning badge --}}
                   flex items-center justify-center w-12 h-12 {{-- Ukuran mobile --}}
                   bg-teal-600 hover:bg-teal-700 {{-- Warna Teal (contoh) --}}
                   text-white {{-- Warna ikon putih --}}
                   rounded-full {{-- Bentuk bulat --}}
                   shadow-md hover:shadow-lg {{-- Bayangan standar --}}
                   transition-all duration-200 ease-in-out transform hover:scale-105 {{-- Animasi hover simpel --}}
                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg">
                        {{-- State Fokus --}}

                        {{-- Ikon Keranjang (Cart) --}}
                        <i class="fas fa-shopping-cart text-lg"></i> {{-- Font Awesome icon --}}


                        {{-- Badge Jumlah Item Cart (Mobile Version) --}}
                        {{-- Pastikan $cartCount tersedia (misal dari View Composer) --}}
                        @isset($cartCount)
                            {{-- Gunakan isset untuk mencegah error jika var tidak ada --}}
                            @if ($cartCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 flex items-center justify-center min-w-[18px] h-[18px] shadow-sm">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }} {{-- Limit display --}}
                                </span>
                            @endif
                        @endisset
                    </a>

                    {{-- ============================================= --}}
                    {{-- Versi Desktop (Complex & Animated)          --}}
                    {{-- Hilang default, tampil di layar md ke atas --}}
                    {{-- ============================================= --}}
                    <a href="{{ route('cart.index') }}" title="View Cart" x-data="{ hover: false, press: false }"
                        @mouseenter="hover = true" @mouseleave="hover = false"
                        @mousedown="press = true; setTimeout(() => press = false, 200)" @mouseup="press = false"
                        class="hidden md:block relative {{-- Relative untuk positioning badge & layers --}}
                   w-16 h-16 rounded-full overflow-hidden {{-- Ukuran desktop, overflow hidden jika ada inner layers --}}
                   transition-all duration-500 ease-out transform {{-- Transisi utama --}}
                   focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg"
                        :class="{
                            'bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-600 dark:from-teal-700 dark:via-cyan-700 dark:to-blue-800': true,
                            {{-- Gradient background --}} 'scale-110 shadow-xl shadow-cyan-400/50 dark:shadow-blue-800/50': hover && !press,
                            {{-- Hover state with stronger shadow --}} 'scale-90': press,
                            {{-- Press state --}} 'shadow-lg shadow-teal-400/40 dark:shadow-blue-700/40': !hover && !
                                press,
                            {{-- Idle state shadow --}}
                        }">

                        {{-- Optional: Background Effect Layer (Subtle gradient overlay) --}}
                         <div class="absolute inset-0 transition-opacity duration-500 opacity-50 mix-blend-overlay">
                              <div class="w-full h-full bg-gradient-to-tl from-white/20 via-transparent to-white/10"></div>
                        </div>

                        {{-- Icon Container (untuk animasi icon jika diinginkan) --}}
                        <div class="absolute inset-0 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'scale-110': hover && !press }">
                            {{-- Ikon Keranjang (Cart) - Desktop --}}
                            <i
                                class="fas fa-shopping-cart text-xl text-white drop-shadow-lg animate-subtle-pulse"></i>
                             {{-- Icon lebih besar, warna putih, shadow, pulse. Class `animate-subtle-pulse` sudah ada di style Anda --}}
                        </div>

                        {{-- Hover/Press Border Effect Layer --}}
                        <div class="absolute inset-0 rounded-full transition-all duration-300 border border-transparent pointer-events-none"
                            :class="{ 'border-white/40 scale-110': hover && !press, 'scale-95': press }">
                        </div>

                        {{-- Badge Jumlah Item Cart (Desktop Version) --}}
                        {{-- Pastikan $cartCount tersedia --}}
                        @isset($cartCount)
                            @if ($cartCount > 0)
                                <span
                                    class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 flex items-center justify-center min-w-[22px] h-[22px] shadow-md border-2 border-white dark:border-gray-800 transform transition-transform"
                                    :class="{ 'scale-110': hover && !press }"> {{-- Sedikit scale up badge saat hover --}}
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        @endisset

                    </a>
                </div>
            @endif
        @endauth
        {{-- >>> END: Floating Cart Button <<< --}}


        {{-- >>> START: Floating Wishlist Button <<< --}}
        @auth {{-- Hanya tampilkan jika user login --}}
            {{-- Opsional: Sembunyikan jika sedang di halaman wishlist itu sendiri --}}
            @if (!request()->routeIs('wishlist.index'))
                {{-- Container untuk kedua versi tombol --}}
                {{-- Atur `right` agar di sebelah kanan tombol cart --}}
                <div class="fixed bottom-6 right-20 md:right-24 z-40"> {{-- Posisi fixed, bottom-right, z-index --}}

                    {{-- ============================================= --}}
                    {{-- Versi Mobile (Simple)                       --}}
                    {{-- Tampil default, hilang di layar md ke atas --}}
                    {{-- ============================================= --}}
                    <a href="{{ route('wishlist.index') }}" title="View Wishlist"
                        class="block md:hidden relative {{-- Relative untuk positioning badge --}}
                   flex items-center justify-center w-12 h-12 {{-- Ukuran mobile --}}
                   bg-pink-600 hover:bg-pink-700 {{-- Warna Pink (contoh) --}}
                   text-white {{-- Warna ikon putih --}}
                   rounded-full {{-- Bentuk bulat --}}
                   shadow-md hover:shadow-lg {{-- Bayangan standar --}}
                   transition-all duration-200 ease-in-out transform hover:scale-105 {{-- Animasi hover simpel --}}
                   focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg">
                        {{-- State Fokus --}}

                        {{-- Ikon Hati (Wishlist) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="none" class="w-6 h-6"> {{-- Slightly smaller icon, fill added --}}
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                        {{-- Badge Jumlah Item Wishlist (Mobile Version) --}}
                        {{-- Pastikan $wishlistCount tersedia --}}
                        @php
                            // Uncomment baris ini jika $wishlistCount tidak di-pass dari controller
                            // $wishlistCount = Auth::user()->wishlistProducts()->count(); // Pastikan ini sesuai dengan logic Anda
                        @endphp
                        @isset($wishlistCount)
                            {{-- Gunakan isset untuk mencegah error jika var tidak ada --}}
                            @if ($wishlistCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 flex items-center justify-center min-w-[18px] h-[18px] shadow-sm">
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }} {{-- Limit display --}}
                                </span>
                            @endif
                        @endisset
                    </a>

                    {{-- ============================================= --}}
                    {{-- Versi Desktop (Complex & Animated)          --}}
                    {{-- Hilang default, tampil di layar md ke atas --}}
                    {{-- ============================================= --}}
                    <a href="{{ route('wishlist.index') }}" title="View Wishlist" x-data="{ hover: false, press: false }"
                        @mouseenter="hover = true" @mouseleave="hover = false"
                        @mousedown="press = true; setTimeout(() => press = false, 200)" @mouseup="press = false"
                        class="hidden md:block relative {{-- Relative untuk positioning badge & layers --}}
                   w-16 h-16 rounded-full overflow-hidden {{-- Ukuran desktop, overflow hidden jika ada inner layers --}}
                   transition-all duration-500 ease-out transform {{-- Transisi utama --}}
                   focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-bg"
                        :class="{
                            'bg-gradient-to-br from-pink-500 via-red-500 to-rose-600 dark:from-pink-700 dark:via-red-700 dark:to-rose-800': true,
                            {{-- Gradient background --}} 'scale-110 shadow-pink-glow-strong': hover && !press,
                            {{-- Hover state --}} 'scale-90': press,
                            {{-- Press state --}} 'shadow-lg shadow-red-400/40 dark:shadow-red-800/40': !hover && !
                                press,
                            {{-- Idle state shadow --}} 'shadow-pink-glow': hover || press {{-- Glow on hover/press (pakai custom shadow class) --}}
                        }">

                        {{-- Optional: Background Effect Layer (Mirip dark mode button) --}}
                        <div class="absolute inset-0 transition-opacity duration-500 opacity-50 mix-blend-overlay">
                            <div class="w-full h-full bg-gradient-to-tl from-white/20 via-transparent to-white/10"></div>
                        </div>

                        {{-- Icon Container (untuk animasi icon jika diinginkan) --}}
                        <div class="absolute inset-0 flex items-center justify-center transition-transform duration-300"
                            :class="{ 'scale-110': hover && !press }">
                            {{-- Ikon Hati (Wishlist) - Desktop --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="none"
                                class="w-8 h-8 text-white drop-shadow-lg animate-subtle-pulse"> {{-- Icon lebih besar, warna putih, shadow, pulse --}}
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </div>

                        {{-- Hover/Press Border Effect Layer --}}
                        <div class="absolute inset-0 rounded-full transition-all duration-300 border border-transparent pointer-events-none"
                            :class="{ 'border-white/40 scale-110': hover && !press, 'scale-95': press }">
                        </div>

                        {{-- Badge Jumlah Item Wishlist (Desktop Version) --}}
                        {{-- Pastikan $wishlistCount tersedia --}}
                        @isset($wishlistCount)
                             {{-- Jangan lupa pastikan $wishlistCount benar-benar ada di sini, misal dari View Composer --}}
                            @if ($wishlistCount > 0)
                                <span
                                    class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 flex items-center justify-center min-w-[22px] h-[22px] shadow-md border-2 border-white dark:border-gray-800 transform transition-transform"
                                    :class="{ 'scale-110': hover && !press }"> {{-- Sedikit scale up badge saat hover --}}
                                    {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                                </span>
                            @endif
                        @endisset

                    </a>
                </div>
            @endif
        @endauth
        {{-- >>> END: Floating Wishlist Button <<< --}}


        <!-- Dark Mode Toggle -->
        <div class="fixed bottom-6 left-6 z-50">

            <!-- Simple Mobile Button -->
            <!-- Shown by default, hidden on 'md' screens and up -->
            <button
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); window.dispatchEvent(new CustomEvent('dark-mode-toggled', { detail: darkMode }))"
                {{-- Hapus 'block' dari sini --}}
                class="md:hidden w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-[#0a0a0a]"
                :class="{
                    'bg-yellow-400 hover:bg-yellow-500 focus:ring-yellow-500': !darkMode,
                    'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500': darkMode
                }"
                aria-label="Toggle Dark Mode (Simple)">
                <!-- Simple Sun/Moon Icon -->
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Complex Desktop Button -->
            <!-- Hidden by default, shown on 'md' screens and up -->
            <button
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); window.dispatchEvent(new CustomEvent('dark-mode-toggled', { detail: darkMode }))"
                x-data="{ hover: false, press: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                @mousedown="press = true; setTimeout(() => press = false, 200)" @mouseup="press = false"
                class="hidden md:block relative w-16 h-16 rounded-full overflow-hidden transition-all duration-500 ease-out transform focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-[#0a0a0a]"
                :class="{
                    'bg-gradient-to-br from-pink-100 to-purple-100 dark:from-gray-800 dark:to-purple-900': true,
                    'scale-110 shadow-pink-glow-strong dark:shadow-purple-glow-strong': hover && !press,
                    'scale-90': press,
                    'shadow-lg shadow-pink-300/40 dark:shadow-purple-900/40': !hover && !press,
                    'shadow-pink-glow dark:shadow-purple-glow': hover || press /* Terapkan glow saat hover atau press */
                }"
                aria-label="Toggle Dark Mode (Complex)">

                <!-- Background Orb/Gradient Layer -->
                <div class="absolute inset-0 transition-opacity duration-500"
                    :class="darkMode ? 'opacity-100' : 'opacity-0'">
                    <div class="w-full h-full bg-gradient-to-br from-gray-700 via-purple-800 to-black opacity-80"></div>
                </div>
                <div class="absolute inset-0 transition-opacity duration-500"
                    :class="!darkMode ? 'opacity-100' : 'opacity-0'">
                    <div class="w-full h-full bg-gradient-to-br from-white via-pink-200 to-purple-200 opacity-90"></div>
                </div>

                <!-- Sun Icon Container -->
                <div class="absolute inset-0 flex items-center justify-center transition-all duration-700 ease-in-out"
                    :class="{
                        'opacity-0 -translate-y-full scale-50 rotate-90': darkMode,
                        'opacity-100 translate-y-0 scale-100 rotate-0':
                            !darkMode
                    }">
                    <!-- Sun Core -->
                    <div
                        class="w-6 h-6 bg-gradient-to-br from-yellow-300 to-orange-400 rounded-full shadow-md animate-subtle-pulse">
                    </div>
                    <!-- Sun Rays (subtle) -->
                    <div class="absolute w-10 h-10 animate-spin-slow opacity-60">
                        <div class="absolute top-0 left-1/2 -ml-px w-px h-2 bg-orange-300 transform origin-bottom">
                        </div>
                        <div class="absolute bottom-0 left-1/2 -ml-px w-px h-2 bg-orange-300 transform origin-top">
                        </div>
                        <div class="absolute left-0 top-1/2 -mt-px h-px w-2 bg-orange-300 transform origin-right">
                        </div>
                        <div class="absolute right-0 top-1/2 -mt-px h-px w-2 bg-orange-300 transform origin-left">
                        </div>
                    </div>
                </div>

                <!-- Moon Icon Container -->
                <div class="absolute inset-0 flex items-center justify-center transition-all duration-700 ease-in-out"
                    :class="{
                        'opacity-100 translate-y-0 scale-100 rotate-0': darkMode,
                        'opacity-0 translate-y-full scale-50 -rotate-90':
                            !darkMode
                    }">
                    <!-- Moon Body -->
                    <div
                        class="w-6 h-6 bg-gradient-to-br from-slate-300 to-slate-500 rounded-full shadow-inner shadow-slate-700/50 animate-subtle-float">
                        <!-- Moon Craters (simple) -->
                        <div class="absolute w-2 h-2 rounded-full bg-slate-400/50 top-2 left-3 opacity-70"></div>
                        <div class="absolute w-1 h-1 rounded-full bg-slate-600/50 bottom-3 right-3 opacity-60"></div>
                    </div>
                    <!-- Subtle Stars/Sparkle around Moon -->
                    <div class="absolute w-10 h-10 animate-spin-reverse-slow opacity-50">
                        <div
                            class="absolute top-1 left-2 w-0.5 h-0.5 bg-purple-300 rounded-full animate-subtle-pulse animation-delay-200">
                        </div>
                        <div
                            class="absolute bottom-2 right-1 w-px h-px bg-pink-200 rounded-full animate-subtle-pulse animation-delay-500">
                        </div>
                        <div
                            class="absolute top-3 right-4 w-0.5 h-0.5 bg-purple-300 rounded-full animate-subtle-pulse animation-delay-800">
                        </div>
                    </div>
                </div>

                <!-- Hover/Press Effect Layer -->
                <div class="absolute inset-0 rounded-full transition-all duration-300 border border-transparent"
                    :class="{ 'border-pink-400/50 dark:border-purple-500/50 scale-110': hover && !press, 'scale-95': press }">
                </div>

            </button>

        </div>

    </div>
    {{-- @livewireScripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function productList() {
            return {
                // --- Modal Visibility ---
                showBuyModal: false,
                modalOpen: false, // For the detail modal

                // --- Loading State ---
                loading: false, // Primarily for loading options in the buy modal

                // --- Selected Product Data ---
                selectedProduct: null, // Holds the full product object when a modal is opened

                // --- Buy Modal Form State ---
                selectedSizeId: '',
                selectedColorId: '',
                quantity: 1,
                maxStock: 0,
                availableSizes: [],
                availableColors: [],
                storageBaseUrl: '{{ rtrim(asset('storage'), '/') }}', // Base URL for local images

                // --- Helper ---
                isExternalImage(url) {
                    return url && (url.startsWith('http://') || url.startsWith('https://'));
                },

                getProductImageUrl(product, placeholder = 'https://via.placeholder.com/150/EEEEEE/AAAAAA?text=No+Image') {
                    if (!product) return placeholder; // Guard against null product

                    if (product.image_url) { // Primary: use the accessor value
                        return product.image_url;
                    } else if (product.image) { // Fallback check for raw image path
                        const imagePath = product.image;
                        if (this.isExternalImage(imagePath)) {
                            return imagePath;
                        } else {
                            // Ensure no double slashes if storageBaseUrl ends with / and imagePath starts with /
                            return this.storageBaseUrl + '/' + imagePath.replace(/^\/+/, '');
                        }
                    } else {
                        return placeholder; // No image info at all
                    }
                },

                // --- Methods for Modals ---
                openModal(product) { // Detail Modal
                    console.log("Opening Detail Modal for:", product);
                    if (!product) {
                        console.error("Cannot open detail modal, product data is null.");
                        return;
                    }
                    this.selectedProduct = product; // Set product data immediately
                    this.modalOpen = true; // Then open modal
                },

                closeModal() { // Detail Modal
                    this.modalOpen = false;
                    // Delay clearing product only if you need animations to finish smoothly
                    // Using @click.away on the modal content handles the closing trigger
                    setTimeout(() => {
                        if (!this.modalOpen) { // Check if it wasn't reopened quickly
                            this.selectedProduct = null;
                        }
                    }, 300); // Match transition duration
                },

                openBuyModal(product) { // Buy Modal
                    if (!product || !product.stock_combinations || product.stock_combinations.reduce((sum, c) => sum + (c
                            .stock || 0), 0) <= 0) {
                        console.warn("Buy modal blocked: Product/stockCombinations missing, or total stock is zero.",
                            product);
                        // Optionally show a user notification here (e.g., using a toast library)
                        alert("Produk ini sedang tidak tersedia."); // Simple alert example
                        return; // Prevent opening
                    }
                    console.log("Opening Buy Modal for:", product);
                    this.selectedProduct = product;
                    this.loading = true;
                    this.showBuyModal = true; // Open modal container first
                    this.resetBuyFormState();

                    this.$nextTick(() => { // Ensure modal structure exists before populating
                        try {
                            this.populateInitialOptions();
                        } catch (error) {
                            console.error("Error populating buy modal options:", error);
                            // Optionally close modal or show error message inside
                        } finally {
                            this.loading = false; // Turn off loading state
                        }
                    });
                },

                closeBuyModal() { // Buy Modal
                    this.showBuyModal = false;
                    setTimeout(() => {
                        if (!this.showBuyModal) { // Check if it wasn't reopened quickly
                            this.selectedProduct = null; // Clear product data after transition
                            this.resetBuyFormState(); // Reset form state as well
                        }
                    }, 300);
                },

                // --- Methods for Buy Modal Logic ---
                resetBuyFormState() {
                    this.selectedSizeId = '';
                    this.selectedColorId = '';
                    this.quantity = 1;
                    this.maxStock = 0;
                    this.availableSizes = [];
                    this.availableColors = [];
                    // Reset any validation messages if needed
                },

                populateInitialOptions() {
                    if (!this.selectedProduct || !this.selectedProduct.stock_combinations) {
                        console.error("Stock combinations missing for populating options.");
                        this.availableSizes = [];
                        this.availableColors = [];
                        return;
                    }

                    // --- Get All Unique Sizes & Colors from Combinations ---
                    const allSizes = new Map();
                    const allColors = new Map();
                    this.selectedProduct.stock_combinations.forEach(c => {
                        if (c.size) allSizes.set(c.size.id, c.size);
                        if (c.color) allColors.set(c.color.id, c.color);
                    });

                    // --- Determine Initially Available Sizes (those part of *any* combination with stock > 0) ---
                    const sizesWithAnyStock = new Set();
                    this.selectedProduct.stock_combinations.forEach(c => {
                        if (c.stock > 0 && c.size_id) {
                            sizesWithAnyStock.add(c.size_id);
                        }
                    });
                    this.availableSizes = [...allSizes.values()].filter(size => sizesWithAnyStock.has(size.id));

                    // --- Set All Colors Initially ---
                    // We filter colors *after* a size is selected.
                    this.availableColors = [...allColors.values()];

                    // --- Reset selections and stock ---
                    this.selectedSizeId = '';
                    this.selectedColorId = '';
                    this.maxStock = 0;
                    this.quantity = 1;

                    // --- Auto-select if only one size option ---
                    if (this.availableSizes.length === 1) {
                        this.selectedSizeId = this.availableSizes[0].id;
                        // IMPORTANT: Trigger updates after auto-selecting size
                        this.$nextTick(() => { // Ensure Alpine picks up the change
                            this.updateAvailableColors();
                            // No need to call updateMaxStock here, updateAvailableColors calls it
                        });
                    } else {
                        // Ensure color list is reset visually if no size is auto-selected
                        this.availableColors = [...allColors.values()];
                    }
                },

                updateAvailableColors() {
                    console.log("Updating colors for size:", this.selectedSizeId);
                    if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                    const combinations = this.selectedProduct.stock_combinations;
                    const sizeId = this.selectedSizeId ? parseInt(this.selectedSizeId) : null;

                    // --- Get All Unique Colors (for resetting) ---
                    const allColorsMap = new Map();
                    combinations.forEach(c => {
                        if (c.color) allColorsMap.set(c.color.id, c.color);
                    });

                    if (!sizeId) {
                        // Reset: Show all colors, clear selection, clear stock
                        this.availableColors = [...allColorsMap.values()];
                        this.selectedColorId = '';
                        this.maxStock = 0;
                        this.quantity = 1;
                        console.log("Size cleared, reset colors and stock.");
                        return;
                    }

                    // Find colors available for the selected size AND have stock > 0
                    const colorsForSizeWithStock = new Map();
                    combinations.forEach(c => {
                        if (c.size_id === sizeId && c.stock > 0 && c.color) {
                            colorsForSizeWithStock.set(c.color.id, c.color);
                        }
                    });

                    this.availableColors = [...colorsForSizeWithStock.values()];
                    console.log("Available colors for size " + sizeId + ":", this.availableColors);

                    // If the currently selected color is no longer valid for this size, reset it
                    const currentSelectedColorIsValid = this.availableColors.some(c => c.id === parseInt(this
                        .selectedColorId));
                    if (this.selectedColorId && !currentSelectedColorIsValid) {
                        console.log("Previously selected color", this.selectedColorId, "is not valid for size", sizeId,
                            ". Resetting color.");
                        this.selectedColorId = '';
                    }

                    // Auto-select color if only one option remains *and* it wasn't already selected
                    // Prevents infinite loops if updateAvailableSizes also auto-selects
                    if (this.availableColors.length === 1 && this.selectedColorId !== this.availableColors[0].id
                        .toString()) {
                        console.log("Auto-selecting the only available color:", this.availableColors[0].id);
                        this.selectedColorId = this.availableColors[0].id;
                        // Since color changed, trigger size update and stock update
                        this.$nextTick(() => {
                            // this.updateAvailableSizes(); // Usually not needed - flow is size->color
                            this.updateMaxStock();
                        });
                    } else {
                        // Always update max stock if color didn't auto-change,
                        // or if it was reset, or if multiple colors are available.
                        this.updateMaxStock();
                    }
                },

                updateAvailableSizes() { // Filter sizes based on selected color (less common)
                    console.log("Updating sizes for color:", this.selectedColorId);
                    if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                    const combinations = this.selectedProduct.stock_combinations;
                    const colorId = this.selectedColorId ? parseInt(this.selectedColorId) : null;

                    // --- Get All Unique Sizes (for resetting) ---
                    const allSizesMap = new Map();
                    combinations.forEach(c => {
                        if (c.size) allSizesMap.set(c.size.id, c.size);
                    });

                    if (!colorId) {
                        // Reset: Show all sizes *that have any stock*, clear selection, clear stock
                        const sizesWithAnyStock = new Set();
                        combinations.forEach(c => {
                            if (c.stock > 0 && c.size_id) sizesWithAnyStock.add(c.size_id);
                        });
                        this.availableSizes = [...allSizesMap.values()].filter(size => sizesWithAnyStock.has(size.id));
                        this.selectedSizeId = '';
                        this.maxStock = 0;
                        this.quantity = 1;
                        console.log("Color cleared, reset sizes and stock.");
                        return;
                    }

                    // Find sizes available for the selected color AND have stock > 0
                    const sizesForColorWithStock = new Map();
                    combinations.forEach(c => {
                        if (c.color_id === colorId && c.stock > 0 && c.size) {
                            sizesForColorWithStock.set(c.size.id, c.size);
                        }
                    });

                    this.availableSizes = [...sizesForColorWithStock.values()];
                    console.log("Available sizes for color " + colorId + ":", this.availableSizes);

                    // If the currently selected size is no longer valid for this color, reset it
                    const currentSelectedSizeIsValid = this.availableSizes.some(s => s.id === parseInt(this
                        .selectedSizeId));
                    if (this.selectedSizeId && !currentSelectedSizeIsValid) {
                        console.log("Previously selected size", this.selectedSizeId, "is not valid for color", colorId,
                            ". Resetting size.");
                        this.selectedSizeId = '';
                    }

                    // Auto-select size if only one option remains *and* it wasn't already selected
                    if (this.availableSizes.length === 1 && this.selectedSizeId !== this.availableSizes[0].id.toString()) {
                        console.log("Auto-selecting the only available size:", this.availableSizes[0].id);
                        this.selectedSizeId = this.availableSizes[0].id;
                        // Since size changed, trigger color update and stock update
                        this.$nextTick(() => {
                            this.updateAvailableColors();
                            // No need to call updateMaxStock here, updateAvailableColors calls it
                        });
                    } else {
                        // Always update max stock if size didn't auto-change
                        this.updateMaxStock();
                    }
                },

                updateMaxStock() {
                    if (!this.selectedProduct || !this.selectedProduct.stock_combinations || !this.selectedSizeId || !this
                        .selectedColorId) {
                        this.maxStock = 0;
                    } else {
                        const sizeId = parseInt(this.selectedSizeId);
                        const colorId = parseInt(this.selectedColorId);
                        const combination = this.selectedProduct.stock_combinations.find(
                            c => c.size_id === sizeId && c.color_id === colorId
                        );
                        // Set maxStock to 0 if combo not found or stock is explicitly 0 or less
                        this.maxStock = (combination && combination.stock > 0) ? combination.stock : 0;
                    }
                    console.log(
                        `Max stock updated to: ${this.maxStock} for Size ${this.selectedSizeId}, Color ${this.selectedColorId}`
                    );

                    // Re-validate quantity whenever max stock changes
                    this.validateQuantity();
                },

                validateQuantity() {
                    // Use $nextTick to ensure maxStock has been updated in Alpine's reactive state
                    this.$nextTick(() => {
                        let qty = parseInt(this.quantity);

                        // Ensure quantity is at least 1 if input is enabled
                        if (isNaN(qty) || qty < 1) {
                            if (this.maxStock > 0) { // Only force to 1 if stock allows
                                qty = 1;
                            } else {
                                // If stock is 0, leave quantity as whatever user typed (or default 1)
                                // The input field should be disabled anyway.
                                // We don't want to force it to 1 if maxStock is 0.
                                // Let's keep it simple: if invalid, default to 1 for logic,
                                // but the disabled state handles the user interaction.
                                if (isNaN(qty) || qty < 1) qty = 1;
                            }
                        }

                        // Cap quantity at maxStock only if maxStock is determined and positive
                        if (this.maxStock > 0 && qty > this.maxStock) {
                            console.log(`Quantity ${qty} exceeds max stock ${this.maxStock}. Capping.`);
                            qty = this.maxStock;
                        }

                        // If calculated quantity differs from model, update model
                        if (this.quantity !== qty) {
                            // console.log("Updating quantity model to:", qty);
                            this.quantity = qty;
                        }
                    });
                },
            };
        }
    </script>

    {{-- >>> START: Skrip untuk Skeleton Loader <<< --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const frameId = 'products_list_frame';
            const frameElement = document.getElementById(frameId);
            const skeletonContainerId = 'product-list-skeleton';
            const contentContainerId = 'product-list-content';

            let isActualNavigation = false; // Flag untuk menandai klik navigasi

            if (!frameElement) {
                console.warn(`Turbo Frame with ID #${frameId} not found.`);
                return;
            }

            const getSkeletonContainer = () => frameElement.querySelector(`#${skeletonContainerId}`);
            const getContentContainer = () => frameElement.querySelector(`#${contentContainerId}`);

            // Fungsi untuk menampilkan skeleton dan menyembunyikan konten
            const showSkeleton = () => {
                const contentContainer = getContentContainer();
                const skeletonContainer = getSkeletonContainer();
                if (contentContainer) contentContainer.style.display = 'none';
                if (skeletonContainer) skeletonContainer.style.display = 'block';
                console.log('Skeleton Shown (Actual Navigation)');
            };

            // Fungsi untuk menyembunyikan skeleton dan menampilkan konten (yang baru dimuat)
            const hideSkeleton = () => {
                const skeletonContainer = getSkeletonContainer();
                const contentContainer = getContentContainer(); // Dapatkan lagi kalau-kalau dirender ulang
                if (skeletonContainer) skeletonContainer.style.display = 'none';
                // Pastikan konten terlihat setelah skeleton hilang (terutama jika terjadi error)
                if (contentContainer && contentContainer.style.display === 'none') {
                    contentContainer.style.display = 'block'; // Atau 'grid', 'flex' sesuai layout Anda
                }
                console.log('Skeleton Hidden');
            };

            // 1. Listener untuk klik link Turbo di dalam frame
            frameElement.addEventListener('click', (event) => {
                // Pastikan yang diklik adalah link anchor (<a>) yang akan diproses Turbo
                // dan bukan link yang sengaja dinonaktifkan dari Turbo ([data-turbo="false"])
                const link = event.target.closest('a[href]:not([data-turbo="false"])');
                if (link && frameElement.contains(link)) {
                    isActualNavigation = true;
                    console.log('turbo:click detected inside frame, setting isActualNavigation = true');
                }
            });

            // 2. Listener sebelum request fetch oleh Turbo
            document.addEventListener('turbo:before-fetch-request', (event) => {
                // Periksa apakah request ini berasal dari klik navigasi aktual
                // Kita tidak perlu memeriksa `frameElement.contains(event.target)` lagi
                // karena flag hanya diset oleh klik di dalam frame.
                if (isActualNavigation) {
                    console.log('Fetch request starting AND isActualNavigation=true, showing skeleton.');
                    showSkeleton();
                } else {
                    console.log(
                        'Fetch request starting BUT isActualNavigation=false (likely prefetch), skipping skeleton.'
                    );
                }
            });

            // 3. Listener setelah frame berhasil dirender (konten baru masuk)
            document.addEventListener('turbo:frame-render', (event) => {
                // Periksa apakah event ini untuk frame target kita
                if (event.target.id === frameId) {
                    console.log('Turbo frame rendered, hiding skeleton and resetting flag.');
                    hideSkeleton();
                    isActualNavigation = false; // Reset flag setelah render selesai
                }
            });

            // 4. Listener jika terjadi error saat fetch
            document.addEventListener('turbo:fetch-request-error', (event) => {
                // Selalu sembunyikan skeleton jika terjadi error, terlepas dari flag
                // karena navigasi yang di-flag mungkin gagal.
                const skeletonContainer = getSkeletonContainer();
                if (skeletonContainer && skeletonContainer.style.display !== 'none') {
                    console.error('Turbo fetch error occurred, hiding skeleton and resetting flag.');
                    hideSkeleton();
                    isActualNavigation = false; // Reset flag pada error juga
                }
            });

            // 5. Sembunyikan skeleton jika user kembali menggunakan cache (Browser back button)
            document.addEventListener("turbo:load", function() {
                console.log(
                    'Turbo full page load/cache restore, ensuring skeleton is hidden and flag reset.');
                // Pastikan skeleton tersembunyi dan flag direset saat halaman dimuat penuh
                // (termasuk dari cache Turbo)
                const skeletonContainer = getSkeletonContainer();
                if (skeletonContainer && skeletonContainer.style.display === 'block') {
                    hideSkeleton();
                }
                isActualNavigation = false;
            });

        });
    </script>
    {{-- >>> END: Skrip untuk Skeleton Loader <<< --}}

    {{-- Optional: Add scrollbar styling if using tailwindcss-scrollbar --}}
    <style>
        /* Optional: Slim scrollbar for stock details */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #d1d5db transparent;
        }

        /* thumb track */
        .dark .scrollbar-thin {
            scrollbar-color: #4b5563 transparent;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 10px;
            border: 3px solid transparent;
        }

        /* Lighter thumb */
        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #4b5563;
        }

        /* Darker thumb */

        /* Tailwind prose adjustments if needed */
        .prose-sm :where(p):where([class~="lead"]):not(:where([class~="not-prose"] *)) {
            margin-top: 0.8em;
            /* Adjust spacing if needed */
            margin-bottom: 0.8em;
        }

        /* Aturan untuk skeleton agar tidak terlihat saat inspect element sebelum JS jalan */
        #product-list-skeleton:not([style*="display: block"]) {
            display: none !important;
        }

        /*Darkmode*/
    </style>
    <style>
         /* Re-define or ensure presence of animations used */
        @keyframes subtle-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.03); }
        }

        /* Ensure these animation classes exist */
        .animate-subtle-pulse { animation: subtle-pulse 2.5s ease-in-out infinite; }

        /* --- Custom Shadow/Glow Examples --- */
        /* Anda mungkin perlu menambahkan ini di tailwind.config.js untuk reusability penuh */
        /* atau biarkan di sini jika hanya digunakan sekali */

        /* Pink/Red Glow (dari wishlist) */
        .shadow-pink-glow {
             box-shadow: 0 0 15px 5px rgba(236, 72, 153, 0.5); /* Pink-500-ish */
         }
         .shadow-pink-glow-strong {
             box-shadow: 0 0 25px 8px rgba(225, 29, 72, 0.5); /* Rose-600-ish */
         }
         .dark .shadow-pink-glow {
             box-shadow: 0 0 15px 5px rgba(190, 24, 93, 0.6); /* Pink-700-ish */
         }
         .dark .shadow-pink-glow-strong {
             box-shadow: 0 0 25px 8px rgba(225, 29, 72, 0.6); /* Rose-600-ish */
         }

        /* Teal/Cyan/Blue Glow (Untuk Cart Button) */
        .shadow-teal-glow {
             box-shadow: 0 0 15px 5px rgba(20, 184, 166, 0.5); /* Teal-500-ish */
         }
        .shadow-teal-glow-strong {
             box-shadow: 0 0 25px 8px rgba(6, 182, 212, 0.5); /* Cyan-600-ish */
         }
         .dark .shadow-blue-glow {
             box-shadow: 0 0 15px 5px rgba(59, 130, 246, 0.6); /* Blue-500-ish */
         }
         .dark .shadow-blue-glow-strong {
             box-shadow: 0 0 25px 8px rgba(37, 99, 235, 0.6); /* Blue-600-ish */
         }


    </style>
    @stack('scripts')
</body>

</html>
