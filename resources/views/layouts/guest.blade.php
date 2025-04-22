<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-50 dark:from-[#1a1a1a] dark:to-[#2d2d2d] flex items-center justify-center p-4">
        <div
            class="w-full max-w-6xl mx-4 bg-white dark:bg-[#1a1a1a] rounded-[2rem] shadow-2xl overflow-hidden flex flex-col min-h-[80vh]">

            <div class="bg-gradient-to-r from-pink-600 to-purple-600 dark:from-[#2d2d2d] dark:to-[#3E3E3A] px-12 py-8">
                <div class="flex items-center justify-between">
                    <a href="/"
                        class="flex items-center gap-4 group transform transition-all duration-300 hover:-translate-y-1">
                        <x-application-logo
                            class="w-16 h-16 text-white dark:text-pink-400 transition-all group-hover:rotate-12" />
                        <div>
                            <h1 class="text-3xl font-bold text-white dark:text-pink-200">{{ config('app.name') }}</h1>
                            <p class="text-white/90 dark:text-pink-100 text-sm mt-1">
                                Elevating Your Style Experience
                            </p>
                        </div>
                    </a>
                    <div class="flex items-center gap-6">
                        <div class="text-white/90 dark:text-pink-100 text-sm bg-black/10 px-4 py-2 rounded-full">
                            📍 Kuningan, ID
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 p-12 flex justify-center items-center">
                <!-- <div class="max-w-md mx-auto w-full"> -->
                {{ $slot }}
                <!-- </div> -->
            </div>

            <div class="border-t border-gray-200 dark:border-[#3E3E3A] px-12 py-6">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex gap-6">
                        <a href="/aboutus" class="hover:text-pink-600 dark:hover:text-pink-400 transition-all">Tentang
                            Kami</a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span>🌐 Bahasa Indonesia</span>
                        <div class="w-px h-4 bg-gray-300"></div>
                        <span>v2.1.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
