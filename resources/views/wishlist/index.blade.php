<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-600 dark:text-green-300" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold mb-6">{{ __("Your Wishlisted Items") }}</h3>

                    @if ($wishlistItems->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Your wishlist is currently empty.") }}</p>
                        <a href="{{ route('homepage') }}" class="mt-4 inline-block text-pink-600 dark:text-pink-400 hover:underline">
                            {{ __('Browse products') }}
                        </a>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($wishlistItems as $item)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex flex-col">
                                    {{-- Tampilkan Gambar Produk (jika ada) --}}
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                            {{ __('No Image') }}
                                        </div>
                                    @endif

                                    <div class="p-4 flex flex-col flex-grow">
                                        <h4 class="font-semibold text-lg mb-1">{{ $item->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2 flex-grow">{{ Str::limit($item->description, 50) }}</p>
                                        <p class="font-bold text-pink-600 dark:text-pink-400 mb-3">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

                                        {{-- Tombol Hapus dari Wishlist --}}
                                        <form action="{{ route('wishlist.toggle', $item) }}" method="POST" class="mt-auto">
                                            @csrf
                                            {{-- Method tidak perlu dispoof karena route pakai POST --}}
                                            <button type="submit" class="w-full text-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
