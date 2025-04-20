<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-[#1b1b18]/90 dark:text-[#EDEDEC]/80">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Name')" class="text-[#1b1b18] dark:text-[#EDEDEC]" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2 text-pink-600 dark:text-pink-400" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-[#1b1b18] dark:text-[#EDEDEC]" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2 text-pink-600 dark:text-pink-400" :messages="$errors->get('email')" />

            <!-- Email Verification -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-pink-50 dark:bg-[#3E3E3A]/30 rounded-lg">
                    <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ __('Your email address is unverified.') }}

                        <button
                            form="send-verification"
                            class="underline text-sm text-pink-600 dark:text-pink-400 hover:text-pink-700 dark:hover:text-pink-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-pink-600 dark:text-pink-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors">
                {{ __('Save') }}
            </button>

            <!-- Success Message -->
            @if (session('status') === 'profile-updated')
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
