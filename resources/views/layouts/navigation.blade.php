<nav x-data="{ open: false, isOpen: false, scrolled: false }"
     @scroll.window="scrolled = (window.scrollY > 10)"
     class="sticky top-0 backdrop-blur-lg border-b border-gray-100/80 dark:border-[#3E3E3A]/80 z-50 transition-all duration-300 ease-in-out"
     :class="{ 'bg-white/95 dark:bg-[#0a0a0a]/95 shadow-md dark:shadow-gray-800': scrolled, 'bg-white/80 dark:bg-[#0a0a0a]/80': !scrolled }">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4 lg:px-8 z-50">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('homepage') }}" class="flex items-center group">
                        <x-application-logo
                            class="block h-9 w-auto text-pink-600 dark:text-pink-400 transition-transform duration-300 ease-out group-hover:scale-110" />
                        <span
                            class="ml-3 text-2xl font-bold text-pink-600 dark:text-pink-400 hidden md:block transition-colors duration-300 group-hover:text-pink-700 dark:group-hover:text-pink-300">Fashionku</span>
                    </a>
                </div>

                <!-- Navigation Links (Hanya untuk yang sudah login) -->
                <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        {{-- Ganti x-nav-link biasa dengan versi kustom ini atau modifikasi x-nav-link --}}
                        <a href="{{ route('dashboard') }}"
                           class="relative inline-flex items-center px-4 pt-1 text-sm font-medium leading-5 text-gray-700 dark:text-[#EDEDEC] focus:outline-none transition duration-150 ease-in-out group {{ request()->routeIs('dashboard') ? 'border-b-2 border-pink-500 dark:border-pink-400 text-pink-600 dark:text-pink-400 font-semibold' : 'border-b-2 border-transparent hover:text-pink-600 dark:hover:text-pink-400' }}">
                            {{ __('Dashboard') }}
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-pink-600 dark:bg-pink-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out origin-left {{ request()->routeIs('dashboard') ? 'scale-x-100' : '' }}"></span>
                        </a>

                        @if (Auth::user()->is_admin)
                        <a href="{{ route('admin.index') }}"
                           class="relative inline-flex items-center px-4 pt-1 text-sm font-medium leading-5 text-gray-700 dark:text-[#EDEDEC] focus:outline-none transition duration-150 ease-in-out group {{ request()->routeIs('admin') ? 'border-b-2 border-pink-500 dark:border-pink-400 text-pink-600 dark:text-pink-400 font-semibold' : 'border-b-2 border-transparent hover:text-pink-600 dark:hover:text-pink-400' }}">
                            {{ __('Admin') }}
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-pink-600 dark:bg-pink-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out origin-left {{ request()->routeIs('admin') ? 'scale-x-100' : '' }}"></span>
                        </a>
                        @endif
                    @endauth
                    {{-- Anda bisa menambahkan link publik lainnya di sini jika perlu --}}
                </div>
            </div>

            <!-- Bagian Kanan: Settings Dropdown (Login) atau Link Login/Register (Guest) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Settings Dropdown -->
                    <div class="relative ml-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button @click="isOpen = !isOpen"
                                    class="flex items-center space-x-3 hover:bg-gray-100 dark:hover:bg-[#2a2a2a] px-3 py-1.5 rounded-lg transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-[#0a0a0a]">

                                    <!-- Profile -->
                                    @php
                                        $avatarUrl = route('avatar', ['userId' => Auth::id()]);
                                        $fallbackAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=FFB6C1&color=fff&size=40'; // Sesuaikan size jika perlu
                                    @endphp

                                    <div id="user-avatar" data-turbo-permanent wire:ignore
                                        class="relative w-9 h-9 rounded-full overflow-hidden border-2 border-pink-100 dark:border-[#3E3E3A]">
                                        <img src="{{ $fallbackAvatar }}" alt="Avatar placeholder" class="w-full h-full object-cover absolute top-0 left-0" />
                                        <img src="{{ $avatarUrl }}" alt="Avatar {{ Auth::user()->name }}" class="w-full h-full object-cover relative z-10 transition-opacity duration-300" loading="lazy" onerror="this.style.opacity='0'; this.onerror=null;" />
                                    </div>
                                    <!-- End Profile -->

                                    <div class="flex flex-col items-start">
                                        <span class="text-sm font-medium text-black dark:text-[#EDEDEC]">{{ Auth::user()->name }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500 dark:text-[#a0a095] transition-transform duration-300 ease-in-out"
                                        :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content" class="dark:bg-[#1a1a1a] rounded-md shadow-xl ring-1 ring-black ring-opacity-5 py-1">
                                <div x-show="isOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95">

                                    <x-dropdown-link :href="route('profile.edit')" class="group hover:bg-pink-50 dark:hover:bg-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] hover:text-pink-700 dark:hover:text-pink-300 transition-colors duration-150">
                                        <i class="fas fa-user-circle w-5 text-center mr-3 text-pink-500 group-hover:text-pink-600 dark:group-hover:text-pink-300"></i>
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="group hover:bg-pink-50 dark:hover:bg-[#3E3E3A] text-gray-700 dark:text-[#EDEDEC] hover:text-pink-700 dark:hover:text-pink-300 transition-colors duration-150">
                                            <i class="fas fa-sign-out-alt w-5 text-center mr-3 text-pink-500 group-hover:text-pink-600 dark:group-hover:text-pink-300"></i>
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <!-- Link Login & Register (Hanya untuk tamu) -->
                     <div class="space-x-2 flex items-center">
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 text-sm font-medium transition-all duration-200 hover:bg-gray-100 dark:hover:bg-[#3E3E3A] rounded-lg text-gray-700 dark:text-[#EDEDEC] hover:text-pink-600 dark:hover:text-pink-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-[#0a0a0a]">
                            {{ __('Log in') }}
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 text-sm font-medium transition-all duration-200 ease-in-out bg-pink-600 hover:bg-pink-700 text-white rounded-lg dark:bg-pink-400 dark:hover:bg-pink-500 dark:text-black transform hover:scale-105 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-[#0a0a0a]">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A] focus:outline-none focus:ring-2 focus:ring-pink-500 transition duration-150 ease-in-out"
                    :aria-expanded="open" aria-label="Toggle navigation">
                    <svg class="h-6 w-6 transition-transform duration-300 ease-in-out" :class="{ 'hidden': open, 'block': !open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6 transition-transform duration-300 ease-in-out" :class="{ 'block': open, 'hidden': !open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-collapse class="sm:hidden border-t border-gray-200 dark:border-[#3E3E3A]" @click.away="open = false" style="display: none;">
        <div class="pt-2 pb-3 space-y-1">
             @auth
                <!-- Link Navigasi Responsif (Hanya untuk yang sudah login) -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-pink-400 text-pink-700 bg-pink-50 dark:bg-[#3E3E3A] dark:text-pink-300' : 'border-transparent text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 dark:hover:text-gray-200' }} focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out block font-medium">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                @if (Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin')"
                     class="pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin') ? 'border-pink-400 text-pink-700 bg-pink-50 dark:bg-[#3E3E3A] dark:text-pink-300' : 'border-transparent text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 dark:hover:text-gray-200' }} focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out block font-medium">
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                @endif
             @else
                 <!-- Link Navigasi Responsif (Hanya untuk tamu) -->
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')"
                     class="pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('login') ? 'border-pink-400 text-pink-700 bg-pink-50 dark:bg-[#3E3E3A] dark:text-pink-300' : 'border-transparent text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 dark:hover:text-gray-200' }} focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out block font-medium">
                     {{ __('Log in') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                     <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')"
                        class="pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('register') ? 'border-pink-400 text-pink-700 bg-pink-50 dark:bg-[#3E3E3A] dark:text-pink-300' : 'border-transparent text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 dark:hover:text-gray-200' }} focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out block font-medium">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
             @endauth
              {{-- Tambahkan link publik responsif lainnya di sini jika perlu --}}
        </div>

        <!-- Responsive Settings Options (Hanya untuk yang sudah login) -->
        @auth
            <div class="pt-4 pb-2 border-t border-gray-200 dark:border-[#3E3E3A]">
                <div class="flex items-center px-4 mb-3">
                     @php
                        $avatarUrl = route('avatar', ['userId' => Auth::id()]);
                        $fallbackAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=FFB6C1&color=fff&size=40';
                     @endphp
                    <div id="user-avatar-mobile" data-turbo-permanent wire:ignore
                        class="shrink-0 relative w-10 h-10 rounded-full overflow-hidden border-2 border-pink-100 dark:border-[#3E3E3A] mr-3">
                        <img src="{{ $fallbackAvatar }}" alt="Avatar placeholder" class="w-full h-full object-cover absolute top-0 left-0" />
                        <img src="{{ $avatarUrl }}" alt="Avatar {{ Auth::user()->name }}" class="w-full h-full object-cover relative z-10 transition-opacity duration-300" loading="lazy" onerror="this.style.opacity='0'; this.onerror=null;" />
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-[#EDEDEC]">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500 dark:text-[#a0a095]">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                     <x-responsive-nav-link :href="route('profile.edit')"
                        class="group flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        <i class="fas fa-user-circle w-5 text-center mr-3 text-pink-500 group-hover:text-pink-600 dark:group-hover:text-pink-300"></i>{{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="group flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-[#EDEDEC] hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-[#2a2a2a] hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                           <i class="fas fa-sign-out-alt w-5 text-center mr-3 text-pink-500 group-hover:text-pink-600 dark:group-hover:text-pink-300"></i> {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
