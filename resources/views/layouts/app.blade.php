@props(['header'])

@php
    $isAdmin = auth()->check() && auth()->user()->is_admin;
    $onAdminPage = request()->is('products*') || request()->is('admin*');
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
                            <a href="{{ route('products.create') }}"
                               class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                                Create Product
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
        @endif

        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300">
            <!-- Top Navigation -->
            @if (request()->path() !== '/')
                @include('layouts.navigation')
            @endif

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
            @click="darkMode = !darkMode"
            x-data="{
                hover: false,
                press: false,
                cosmicPulse: false
            }"
            @mouseenter="hover = true"
            @mouseleave="hover = false"
            @mousedown="press = true"
            @mouseup="press = false"
            class="relative w-10 h-10 md:w-20 md:h-20 rounded-3xl shadow-stellar transition-all duration-1000 ease-[cubic-bezier(0.25,0.1,0.25,1)] overflow-hidden"
            :class="{
                'bg-gradient-to-br from-[#001220] to-[#003049]': darkMode,
                'bg-gradient-to-br from-[#ffd89b] to-[#ff6b6b]': !darkMode,
                'scale-90': press,
                'shadow-galaxy': hover && darkMode,
                'shadow-sunburst': hover && !darkMode
            }"
            style="transform-style: preserve-3d; perspective: 1000px;">

            <!-- Cosmic Particles -->
            <div class="absolute inset-0 pointer-events-none">
                <template x-for="i in 30">
                <div class="absolute w-0.5 h-0.5 animate-twinkle opacity-0"
                     :class="{
                    'bg-yellow-400': !darkMode,
                    'bg-blue-400': darkMode
                     }"
                     :style="`
                    left: ${Math.random()*100}%;
                    top: ${Math.random()*100}%;
                    animation-delay: ${Math.random()*3000}ms;
                     `">
                </div>
                </template>
            </div>

            <!-- Celestial Body Container -->
            <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                <!-- Sun Core -->
                <div class="absolute w-6 h-6 md:w-12 md:h-12 transition-all duration-1000 origin-center"
                 :class="{
                    'scale-0 opacity-0': darkMode,
                    'scale-100 opacity-100': !darkMode
                 }">
                <div class="absolute inset-0 bg-gradient-to-br from-[#ffd700] to-[#ff8c00] rounded-full
                       shadow-[0_0_40px_10px_rgba(255,215,0,0.3)] animate-pulse-slow"></div>

                <!-- Solar Flares -->
                <div class="absolute inset-0 animate-rotate-slow">
                    <template x-for="i in 8">
                    <div class="absolute w-2 h-0.5 md:w-4 md:h-1.5 bg-yellow-500/40 origin-left"
                         :style="`transform: rotate(${i*45}deg) translateX(${window.innerWidth < 768 ? 14 : 28}px);`">
                    </div>
                    </template>
                </div>
                </div>

                <!-- Moon Core -->
                <div class="absolute w-6 h-6 md:w-12 md:h-12 transition-all duration-1000 origin-center"
                 :class="{
                    'scale-0 opacity-0': !darkMode,
                    'scale-100 opacity-100': darkMode
                 }">
                <div class="absolute inset-0 bg-gradient-to-br from-[#e2e8f0] to-[#94a3b8] rounded-full
                       shadow-[0_0_40px_10px_rgba(148,163,184,0.2)]"></div>

                <!-- Moon Craters -->
                <div class="absolute inset-0 animate-float">
                    <div class="absolute w-2 h-2 md:w-4 md:h-4 bg-gray-500/30 rounded-full top-1/4 left-1/4"></div>
                    <div class="absolute w-1.5 h-1.5 md:w-3 md:h-3 bg-gray-600/40 rounded-full top-3/4 left-1/3"></div>
                    <div class="absolute w-2.5 h-2.5 md:w-5 md:h-5 bg-gray-400/20 rounded-full top-1/3 right-1/4"></div>
                </div>
                </div>

                <!-- Orbital Particles -->
                <div class="absolute w-12 h-12 md:w-24 md:h-24 animate-rotate-slow-reverse">
                <template x-for="i in 6">
                    <div class="absolute w-1 h-1 md:w-2 md:h-2 rounded-full origin-[50%_200%]"
                     :class="{
                        'bg-amber-500/50': !darkMode,
                        'bg-blue-400/50': darkMode
                     }"
                     :style="`
                        transform: rotate(${i*60}deg) translateY(${window.innerWidth < 768 ? 20 : 40}px);
                        ${!darkMode ? 'animation: pulse-orbit 2s infinite' : 'animation: sparkle-orbit 3s infinite'};
                        animation-delay: ${i*200}ms;
                     `">
                    </div>
                </template>
                </div>
            </div>

                <!-- Atmospheric Distortion -->
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/10 to-transparent
                           opacity-0 transition-opacity duration-500 mix-blend-overlay"
                     :class="{'opacity-30': hover}">
                </div>

                <!-- Transition Burst -->
                <div class="absolute inset-0 bg-white/10 animate-ripple opacity-0"
                     :class="{'animate-ripple': press}">
                </div>
            </button>
        </div>

    </div>
    {{-- @livewireScripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
