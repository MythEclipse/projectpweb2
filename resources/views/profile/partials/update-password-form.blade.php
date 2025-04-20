<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-2 text-[#1b1b18]/90 dark:text-[#EDEDEC]/80">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password Field -->
        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[#1b1b18] dark:text-[#EDEDEC]" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-pink-600 dark:text-pink-400" />
        </div>

        <!-- New Password Field -->
        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-[#1b1b18] dark:text-[#EDEDEC]" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-pink-600 dark:text-pink-400" />
        </div>

        <!-- Password Confirmation Field -->
        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[#1b1b18] dark:text-[#EDEDEC]" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-pink-600 dark:text-pink-400" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors">
                {{ __('Save') }}
            </button>

            <!-- Success Message -->
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-pink-600 dark:text-pink-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
