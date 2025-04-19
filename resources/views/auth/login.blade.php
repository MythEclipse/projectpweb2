<!-- Login Form -->
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="w-full max-w-md space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-pink-100">Welcome Back 👋</h2>
            <p class="mt-2 text-gray-600 dark:text-pink-200">Please sign in to continue</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-5">
                <!-- Email Input -->
                <div>
                    <x-input-label for="email" class="text-gray-700 dark:text-pink-200" :value="__('Email')" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <x-text-input id="email" name="email" type="email"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#2d2d2d] border-0 rounded-xl focus:ring-2 focus:ring-pink-500 dark:focus:ring-pink-400"
                            placeholder="you@example.com"
                            :value="old('email')" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password Input -->
                <div>
                    <x-input-label for="password" class="text-gray-700 dark:text-pink-200" :value="__('Password')" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <x-text-input id="password" name="password" type="password"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#2d2d2d] border-0 rounded-xl focus:ring-2 focus:ring-pink-500 dark:focus:ring-pink-400"
                            placeholder="••••••••"
                            required autocomplete="current-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 dark:border-pink-300 text-pink-600 shadow-sm focus:ring-pink-500 dark:bg-[#2d2d2d]">
                    <span class="ml-2 text-sm text-gray-600 dark:text-pink-200">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-pink-600 hover:text-pink-500 dark:text-pink-400 dark:hover:text-pink-300">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="w-full justify-center bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-500 hover:to-purple-500 text-lg py-3 rounded-xl">
                {{ __('Sign In') }}
            </x-primary-button>

            <!-- <div class="relative mt-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-pink-800"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-[#1a1a1a] text-gray-500 dark:text-pink-300">Or continue with</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="#" class="w-full inline-flex justify-center items-center space-x-3 border border-gray-300 dark:border-pink-800 rounded-xl px-4 py-3 hover:bg-gray-50 dark:hover:bg-[#2d2d2d] transition-colors">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5" alt="Google">
                    <span class="text-sm font-medium text-gray-700 dark:text-pink-200">Google</span>
                </a>
            </div> -->
        </form>
    </div>
</x-guest-layout>
