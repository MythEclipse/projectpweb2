<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">
            Konfirmasi Password
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 text-center">
            Area ini aman. Silakan konfirmasi password Anda sebelum melanjutkan.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-[#3E3E3A] rounded-lg bg-gray-50 dark:bg-[#2d2d2d] text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
