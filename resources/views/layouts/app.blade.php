@props(['header'])

@php
    $isAdmin = auth()->check() && auth()->user()->is_admin;
    $onAdminPage = request()->is('products*') || request()->is('admin*');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('dark-mode') === 'true',
    sidebarOpen: window.innerWidth >= 768 // Buka sidebar default di desktop
}" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
window.addEventListener('resize', () => {
    sidebarOpen = window.innerWidth >= 768
})"
    x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- @livewireStyles --}}

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
                class="bg-white dark:bg-[#1a1a1a] shadow-lg transition-all duration-300 mt-14 overflow-hidden z-50 fixed md:relative min-h-screen md:w-64">
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
        <div class="flex-1 transition-all duration-300 ">
            <!-- Top Navigation -->
            @if (request()->path() !== '/')
                @include('layouts.navigation')
            @endif

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
            <button @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode)"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-sm rounded-lg text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200 shadow-lg">
                <span x-text="darkMode ? '☀️ Light Mode' : '🌙 Dark Mode'"></span>
            </button>
        </div>

    </div>
    {{-- @livewireScripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

</body>

</html>
