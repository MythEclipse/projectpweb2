@props(['header'])

@php
    $isAdmin = auth()->check() && auth()->user()->is_admin;
    $onAdminPage = request()->is('products*') || request()->is('admin*') || request()->is('transactions*');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: false,
          sidebarOpen: window.innerWidth >= 768
      }"
      x-init="
          // Set dark mode from localStorage
          darkMode = localStorage.getItem('dark-mode') === 'true';

          // Responsive sidebar
          window.addEventListener('resize', () => {
              sidebarOpen = window.innerWidth >= 768;
          });

          // Sync dark mode state
          $watch('darkMode', value => {
              localStorage.setItem('dark-mode', value);
              document.documentElement.classList.toggle('dark', value);
          });
      ">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased min-h-screen">
    <div class="flex min-h-screen">

        @if ($isAdmin && $onAdminPage)
            <!-- Overlay for Mobile -->
            <div x-show="sidebarOpen && window.innerWidth < 768" x-transition.opacity
                class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'w-64' : '-translate-x-full md:translate-x-0'"
                class="bg-white dark:bg-[#1a1a1a] shadow-lg transition-all duration-300 mt-14 lg:mt-0 overflow-hidden z-40 fixed md:relative min-h-screen md:w-64">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-pink-600 dark:text-pink-400 mb-6">Menu Admin</h2>
                    <ul class="space-y-4 text-gray-700 dark:text-gray-300">
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                               Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('transactions.index') }}"
                               class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                               transactions
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
        @endif

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300">
            <!-- Top Navigation -->
            {{-- @if (request()->path() !== '/') --}}
                @include('layouts.navigation')
            {{-- @endif --}}

            <!-- Header -->
            @isset($header)
                <header class="bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-sm shadow border-b border-gray-100 dark:border-[#3E3E3A]">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-4 sm:p-6 lg:p-8">
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

        <!-- Dark Mode Toggle -->
        <div class="fixed bottom-6 right-6 z-50">
            <button
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); window.dispatchEvent(new CustomEvent('dark-mode-toggled', { detail: darkMode }))"
                x-data="{ hover: false, press: false }"
                @mouseenter="hover = true"
                @mouseleave="hover = false"
                @mousedown="press = true; setTimeout(() => press = false, 200)"
                @mouseup="press = false"
                class="relative w-16 h-16 rounded-full overflow-hidden transition-all duration-500 ease-out transform focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-[#0a0a0a]"
                :class="{
                    'bg-gradient-to-br from-pink-100 to-purple-100 dark:from-gray-800 dark:to-purple-900': true,
                    'scale-110 shadow-pink-glow-strong': hover && !press,
                    'scale-90': press,
                    'shadow-lg shadow-pink-300/40 dark:shadow-purple-900/40': !hover && !press,
                    'shadow-pink-glow': hover || press /* Terapkan glow saat hover atau press */
                }"
                aria-label="Toggle Dark Mode">

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
                     :class="{ 'opacity-0 -translate-y-full scale-50 rotate-90': darkMode, 'opacity-100 translate-y-0 scale-100 rotate-0': !darkMode }">
                    <!-- Sun Core -->
                    <div class="w-6 h-6 bg-gradient-to-br from-yellow-300 to-orange-400 rounded-full shadow-md animate-subtle-pulse"></div>
                    <!-- Sun Rays (subtle) -->
                     <div class="absolute w-10 h-10 animate-spin-slow opacity-60">
                        <div class="absolute top-0 left-1/2 -ml-px w-px h-2 bg-orange-300 transform origin-bottom"></div>
                        <div class="absolute bottom-0 left-1/2 -ml-px w-px h-2 bg-orange-300 transform origin-top"></div>
                        <div class="absolute left-0 top-1/2 -mt-px h-px w-2 bg-orange-300 transform origin-right"></div>
                        <div class="absolute right-0 top-1/2 -mt-px h-px w-2 bg-orange-300 transform origin-left"></div>
                    </div>
                </div>

                <!-- Moon Icon Container -->
                <div class="absolute inset-0 flex items-center justify-center transition-all duration-700 ease-in-out"
                     :class="{ 'opacity-100 translate-y-0 scale-100 rotate-0': darkMode, 'opacity-0 translate-y-full scale-50 -rotate-90': !darkMode }">
                    <!-- Moon Body -->
                    <div class="w-6 h-6 bg-gradient-to-br from-slate-300 to-slate-500 rounded-full shadow-inner shadow-slate-700/50 animate-subtle-float">
                        <!-- Moon Craters (simple) -->
                        <div class="absolute w-2 h-2 rounded-full bg-slate-400/50 top-2 left-3 opacity-70"></div>
                        <div class="absolute w-1 h-1 rounded-full bg-slate-600/50 bottom-3 right-3 opacity-60"></div>
                    </div>
                     <!-- Subtle Stars/Sparkle around Moon -->
                     <div class="absolute w-10 h-10 animate-spin-reverse-slow opacity-50">
                        <div class="absolute top-1 left-2 w-0.5 h-0.5 bg-purple-300 rounded-full animate-subtle-pulse animation-delay-200"></div>
                        <div class="absolute bottom-2 right-1 w-px h-px bg-pink-200 rounded-full animate-subtle-pulse animation-delay-500"></div>
                         <div class="absolute top-3 right-4 w-0.5 h-0.5 bg-purple-300 rounded-full animate-subtle-pulse animation-delay-800"></div>
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
    @stack('scripts')
</body>
</html>
