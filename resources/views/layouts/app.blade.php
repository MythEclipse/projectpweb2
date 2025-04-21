<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }"
      x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')"
      x-bind:class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased min-h-screen">

        <div class="min-h-screen">
            @if (request()->path() !== '/')
                @include('layouts.navigation')
            @endif

            <!-- Optional Toggle Dark Mode Button -->
            <div class="fixed bottom-6 right-6 z-50">
                <button @click="darkMode = !darkMode; localStorage.setItem('dark-mode', darkMode)"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-sm rounded-lg text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <span x-text="darkMode ? '☀️ Light Mode' : '🌙 Dark Mode'"></span>
                </button>
            </div>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-sm shadow border-b border-gray-100 dark:border-[#3E3E3A]">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
