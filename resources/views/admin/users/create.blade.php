{{-- resources/views/admin/users/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-dark-bg">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg">

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6 p-6">
                    @csrf

                    {{-- Name Field --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama')" class="text-text-dark dark:text-text-light"/>
                        <x-text-input id="name" class="block mt-1 w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Email Field --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-text-dark dark:text-text-light"/>
                        <x-text-input id="email" class="block mt-1 w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-text-dark dark:text-text-light"/>
                        <x-text-input id="password" class="block mt-1 w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Confirm Password Field --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-text-dark dark:text-text-light"/>
                        <x-text-input id="password_confirmation" class="block mt-1 w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Is Admin Checkbox --}}
                    <div class="block">
                        <label for="is_admin" class="inline-flex items-center">
                            <input type="hidden" name="is_admin" value="0">
                            <input id="is_admin" type="checkbox" class="rounded dark:bg-dark-subcard border-gray-300 dark:border-dark-border text-pink-brand shadow-sm focus:ring-pink-brand dark:focus:ring-pink-brand-dark dark:focus:ring-offset-dark-bg" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-text-dark dark:text-text-light">{{ __('Jadikan Admin?') }}</span>
                        </label>
                        <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-subcard border border-gray-300 dark:border-dark-border rounded-md font-semibold text-xs text-text-dark dark:text-text-light uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-pink-brand focus:ring-offset-2 dark:focus:ring-offset-dark-bg disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Batal') }}
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-pink-brand hover:bg-pink-brand-dark text-white text-sm font-medium rounded-lg shadow-md transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-bg focus:ring-pink-brand">
                            {{ __('Simpan User') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
