<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-dark-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-text-dark dark:text-text-light">

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div>
                            <x-input-label for="name" value="Nama" class="text-text-dark dark:text-text-light"/>
                            <x-text-input id="name" name="name" type="text"
                                          class="mt-1 block w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                          value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" value="Email" class="text-text-dark dark:text-text-light"/>
                            <x-text-input id="email" name="email" type="email"
                                          class="mt-1 block w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                          value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <hr class="border-gray-300 dark:border-dark-border">

                        {{-- Password Section --}}
                        <div>
                             <p class="text-sm text-text-dark dark:text-text-light mb-4">
                                Kosongkan password jika tidak ingin mengubahnya.
                             </p>

                             {{-- Password Baru --}}
                             <div class="mb-4">
                                 <x-input-label for="password" value="Password Baru (Opsional)" class="text-text-dark dark:text-text-light"/>
                                 <x-text-input id="password" name="password" type="password"
                                               class="mt-1 block w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                               autocomplete="new-password" />
                                 <x-input-error :messages="$errors->get('password')" class="mt-2" />
                             </div>

                             {{-- Konfirmasi Password Baru --}}
                             <div>
                                 <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" class="text-text-dark dark:text-text-light"/>
                                 <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                               class="mt-1 block w-full dark:bg-dark-subcard dark:border-dark-border dark:text-text-light"
                                               autocomplete="new-password" />
                                 <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                             </div>
                        </div>

                        <hr class="border-gray-300 dark:border-dark-border">

                        {{-- Status Admin --}}
                        <div class="block">
                             <input type="hidden" name="is_admin" value="0">
                             <label for="is_admin" class="inline-flex items-center">
                                 <input id="is_admin" type="checkbox" name="is_admin" value="1"
                                        class="rounded dark:bg-dark-subcard border-gray-300 dark:border-dark-border text-pink-brand shadow-sm focus:ring-pink-brand dark:focus:ring-pink-brand-dark dark:focus:ring-offset-dark-bg"
                                        {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                        {{ Auth::user()->id == $user->id ? 'disabled' : '' }}>
                                 <span class="ml-2 text-sm text-text-dark dark:text-text-light">Jadikan Admin?</span>
                             </label>
                             <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />

                             @if(Auth::user()->id == $user->id)
                                 <p class="mt-2 text-xs text-text-dark dark:text-text-light">
                                     Anda tidak dapat mengubah status admin diri sendiri.
                                 </p>
                             @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.users.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-dark-subcard border border-gray-300 dark:border-dark-border rounded-md font-semibold text-xs text-text-dark dark:text-text-light uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-dark-card focus:outline-none focus:ring-2 focus:ring-pink-brand focus:ring-offset-2 dark:focus:ring-offset-dark-bg transition ease-in-out duration-150">
                                Batal
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-pink-brand hover:bg-pink-brand-dark text-white text-sm font-medium rounded-lg shadow-md transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-bg focus:ring-pink-brand">
                                Update User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
