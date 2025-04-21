<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-4 lg:px-8 space-y-8">
            <!-- Update Profile Information Form -->
            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-[#3E3E3A] rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            <!-- Update Profile Image Form -->
            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-[#3E3E3A] rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-image')
                </div>
            </div>

            <!-- Update Password Form -->
            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-[#3E3E3A] rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Form -->
            <div class="p-6 sm:p-8 bg-white dark:bg-[#0a0a0a] border border-gray-100 dark:border-[#3E3E3A] rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
