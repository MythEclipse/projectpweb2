<x-guest-layout>
    <div class="w-full max-w-md space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-pink-100">Create Account 🎉</h2>
            <p class="mt-2 text-gray-600 dark:text-pink-200">Start your style journey with us</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="space-y-5">
                <!-- Name Input -->
                <div>
                    <x-input-label for="name" class="text-gray-700 dark:text-pink-200" :value="__('Full Name')" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <x-text-input id="name" name="name" type="text"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#2d2d2d] border-0 rounded-xl focus:ring-2 focus:ring-pink-500 dark:focus:ring-pink-400"
                            placeholder="John Doe"
                            :value="old('name')" required autofocus />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

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
                            :value="old('email')" required />
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
                            required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" class="text-gray-700 dark:text-pink-200" :value="__('Confirm Password')" />
                    <div class="relative mt-2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 dark:text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-[#2d2d2d] border-0 rounded-xl focus:ring-2 focus:ring-pink-500 dark:focus:ring-pink-400"
                            placeholder="••••••••"
                            required />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <x-primary-button class="w-full justify-center bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-500 hover:to-purple-500 text-lg py-3 rounded-xl">
                {{ __('Create Account') }}
            </x-primary-button>

            <p class="mt-8 text-center text-sm text-gray-600 dark:text-pink-300">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500 dark:text-pink-400 dark:hover:text-pink-300">
                    Sign in here
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
