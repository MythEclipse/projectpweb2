<nav x-data="{ open: false, isOpen: false }"
    class="sticky top-0 bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-sm border-b border-gray-100 dark:border-[#3E3E3A] z-50">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <x-application-logo
                            class="block h-9 w-auto text-pink-600 dark:text-pink-400 transition duration-300 group-hover:scale-105" />
                        <span
                            class="ml-3 text-2xl font-bold text-pink-600 dark:text-pink-400 hidden md:block">Fashionku</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="px-4 py-2 text-sm font-medium transition-all hover:bg-gray-100 dark:hover:bg-[#3E3E3A] rounded-lg dark:text-[#EDEDEC]">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if (Auth::user()->is_admin)
                            <x-nav-link :href="route('admin')" :active="request()->routeIs('admin')"
                                class="px-4 py-2 text-sm font-medium transition-all hover:bg-gray-100 dark:hover:bg-[#3E3E3A] rounded-lg dark:text-[#EDEDEC]">
                                {{ __('Admin') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center space-x-3 hover:bg-gray-100 dark:hover:bg-[#3E3E3A] px-4 py-2 rounded-lg transition-all duration-200">

                            <!--Profile-->
                            @php
                                // URL avatar dari route controller
                                $avatarUrl = route('avatar', ['userId' => Auth::id()]);

                                // Fallback avatar dari UI Avatars
                                $fallbackAvatar =
                                    'https://ui-avatars.com/api/?name=' .
                                    urlencode(Auth::user()->name) .
                                    '&background=FFB6C1&color=fff';
                            @endphp

                            <div id="user-avatar" data-turbo-permanent wire:ignore
                                class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-pink-100 dark:border-[#3E3E3A]">

                                {{-- Placeholder/fallback avatar (di bawah) --}}
                                <img src="{{ $fallbackAvatar }}" alt="Avatar placeholder"
                                    class="w-full h-full object-cover absolute top-0 left-0" />

                                {{-- Avatar utama dari storage / proxy --}}
                                <img src="{{ $avatarUrl }}" alt="Avatar {{ Auth::user()->name }}"
                                    class="w-full h-full object-cover relative z-10" loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ $fallbackAvatar }}';" />
                            </div>

                            <!--Profile-->


                            <div class="flex flex-col items-start">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-[#EDEDEC]">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-[#3E3E3A]">{{ Auth::user()->email }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-500 dark:text-[#EDEDEC] transform transition-transform"
                                :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content" class="dark:bg-[#1a1a1a]">
                        <x-dropdown-link :href="route('profile.edit')"
                            class="group hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                            <i class="fas fa-user-circle mr-3 text-pink-600 group-hover:text-pink-700"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                class="group hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                                <i class="fas fa-sign-out-alt mr-3 text-pink-600 group-hover:text-pink-700"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-600 dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A] focus:outline-none transition duration-150"
                    :aria-expanded="open" aria-label="Toggle navigation">
                    <svg class="h-6 w-6 transform transition-transform" :class="{ 'hidden': open, 'block': !open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6 transform transition-transform" :class="{ 'block': open, 'hidden': !open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-collapse class="sm:hidden bg-white dark:bg-[#1a1a1a] shadow-xl" @click.away="open = false"
        style="display: none;">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @auth
                @if (Auth::user()->is_admin)
                    <x-responsive-nav-link :href="route('admin')" :active="request()->routeIs('admin')"
                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                        {{ __('Admin') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-gray-100 dark:border-[#3E3E3A]">
            <div class="flex items-center px-4 mb-3">
                @php
                // URL avatar dari route controller
                $avatarUrl = route('avatar', ['userId' => Auth::id()]);

                // Fallback avatar dari UI Avatars
                $fallbackAvatar =
                    'https://ui-avatars.com/api/?name=' .
                    urlencode(Auth::user()->name) .
                    '&background=FFB6C1&color=fff';
            @endphp

            <div id="user-avatar" data-turbo-permanent wire:ignore
                class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-pink-100 dark:border-[#3E3E3A]">

                {{-- Placeholder/fallback avatar (di bawah) --}}
                <img src="{{ $fallbackAvatar }}" alt="Avatar placeholder"
                    class="w-full h-full object-cover absolute top-0 left-0" />

                {{-- Avatar utama dari storage / proxy --}}
                <img src="{{ $avatarUrl }}" alt="Avatar {{ Auth::user()->name }}"
                    class="w-full h-full object-cover relative z-10" loading="lazy"
                    onerror="this.onerror=null; this.src='{{ $fallbackAvatar }}';" />
            </div>
                <div>
                    <div class="font-medium text-gray-800 dark:text-[#EDEDEC]">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 dark:text-[#3E3E3A]">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                    <i class="fas fa-user-circle mr-3 text-pink-600"></i>{{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">
                        <i class="fas fa-sign-out-alt mr-3 text-pink-600"></i>{{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
