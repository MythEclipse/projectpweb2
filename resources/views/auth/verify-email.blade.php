<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">
            Verifikasi Email Kamu
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 text-center leading-relaxed">
            Terima kasih telah mendaftar! Sebelum mulai, silakan verifikasi alamat email kamu
            dengan mengklik link yang baru saja kami kirimkan. Jika belum menerima email tersebut, kami akan kirim ulang untukmu.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400 text-center">
                Link verifikasi baru telah dikirim ke alamat email yang kamu daftarkan.
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="inline-block bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-gray-800">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
