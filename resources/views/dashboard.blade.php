<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-[#EDEDEC] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="bg-white dark:bg-[#1a1a1a] overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-[#3E3E3A]">
                <div class="p-6 text-gray-900 dark:text-[#EDEDEC]">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __("You're logged in!") }}
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-[#2d2d2d] p-4 rounded-lg border border-gray-200 dark:border-[#3E3E3A]">
                            <h3 class="text-pink-600 dark:text-pink-400 font-medium mb-2">Profile</h3>
                            <p class="text-sm text-gray-600 dark:text-[#EDEDEC]">Update your profile information</p>
                            <a href="{{ route('profile.edit') }}" class="mt-3 inline-block text-sm text-pink-600 dark:text-pink-400 hover:underline">
                                Edit Profile →
                            </a>
                        </div>

                        <div class="bg-gray-50 dark:bg-[#2d2d2d] p-4 rounded-lg border border-gray-200 dark:border-[#3E3E3A]">
                            <h3 class="text-pink-600 dark:text-pink-400 font-medium mb-2">Notifications</h3>
                            <p class="text-sm text-gray-600 dark:text-[#EDEDEC]">Manage your notifications</p>
                        </div>

                        <div class="bg-gray-50 dark:bg-[#2d2d2d] p-4 rounded-lg border border-gray-200 dark:border-[#3E3E3A]">
                            <h3 class="text-pink-600 dark:text-pink-400 font-medium mb-2">Security</h3>
                            <p class="text-sm text-gray-600 dark:text-[#EDEDEC]">Update your password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
