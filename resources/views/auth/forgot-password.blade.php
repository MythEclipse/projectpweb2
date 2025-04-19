<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">
            Lupa Password?
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 text-center">
            Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password Anda.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 dark:text-green-400 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-[#3E3E3A] rounded-lg bg-gray-50 dark:bg-[#2d2d2d] text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    Kirim Link Reset
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
