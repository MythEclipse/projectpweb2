<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-[#1b1b18]/90 dark:text-[#EDEDEC]/80">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <!-- Delete Account Button -->
    <button
        x-data="{}"
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg"
    >
        {{ __('Delete Account') }}
    </button>

    <!-- Modal for Confirming Deletion -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white dark:bg-[#0a0a0a] rounded-xl border border-gray-100 dark:border-[#3E3E3A]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-[#1b1b18]/90 dark:text-[#EDEDEC]/80 mb-6">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="space-y-4">
                <div>
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg bg-[#FDFDFC] dark:bg-[#1a1a1a] focus:ring-pink-500 focus:border-pink-500 text-[#1b1b18] dark:text-[#EDEDEC]"
                        placeholder="{{ __('Password') }}"
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-pink-600 dark:text-pink-400" />
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button
                        type="button"
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 border border-gray-200 dark:border-[#3E3E3A] rounded-lg text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition-colors"
                    >
                        {{ __('Cancel') }}
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-colors"
                    >
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</section>
