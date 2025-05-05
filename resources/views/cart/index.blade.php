{{-- resources/views/cart/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Keranjang Belanja Anda
        </h2>
    </x-slot>

    {{-- Adjust padding for smaller screens --}}
    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi (tetap sama, tampilan sudah oke) --}}
            @if (session('success') || session('error') || $errors->any())
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
            @endif

            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border p-4 md:p-6 lg:p-8"> {{-- Smaller padding on mobile --}}
                @if ($cartItems->isEmpty())
                    <div class="text-center py-8 md:py-10 text-gray-500 dark:text-gray-400"> {{-- Smaller padding on mobile --}}
                        {{-- ✅ Menggunakan kembali SVG asli sebagai alternatif Heroicons ✅ --}}
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"> {{-- Tambah kelas responsif ukuran h-10/w-10 di mobile --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Keranjang Anda Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tambah item dari halaman produk untuk mulai berbelanja.
                        </p>
                        <div class="mt-4 md:mt-6"> {{-- Smaller margin on mobile --}}
                            <a href="{{ route('homepage') }}"
                               class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-150"> {{-- Smaller button on mobile --}}
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($cartItems as $item)
                            {{-- Flex layout changed to stack on mobile and row on desktop --}}
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-gray-200 dark:border-dark-border pb-4 space-y-4 sm:space-y-0"> {{-- Add space-y for stacking --}}
                                {{-- Product Info & Image --}}
                                <div class="flex items-center flex-grow w-full sm:w-auto"> {{-- Make inner flex take full width on mobile --}}
                                    <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-md overflow-hidden mr-4 border dark:border-dark-border"> {{-- Smaller image on mobile --}}
                                        @if ($item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-dark-subcard text-gray-300 dark:text-gray-500">
                                                {{-- ✅ Menggunakan kembali SVG asli placeholder gambar sebagai alternatif Heroicons ✅ --}}
                                                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"> {{-- Tambah kelas responsif ukuran w-8/h-8 di mobile --}}
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Product Details --}}
                                    <div class="flex-grow">
                                        <h3 class="text-base md:text-lg font-medium text-text-dark dark:text-text-light"> {{-- Adjust text size --}}
                                            <a href="{{ route('products.show', $item->product) }}" class="hover:text-pink-brand transition-colors duration-150">{{ $item->product->name }}</a>
                                        </h3>
                                        {{-- Add mb-1 to push details slightly above price on mobile --}}
                                        <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-1 sm:mb-0">
                                            {{ $item->size?->name }}{{ $item->size && $item->color ? ' / ' : '' }}{{ $item->color?->name }}
                                        </p>
                                        <p class="text-sm md:text-base font-medium text-gray-900 dark:text-text-light">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p> {{-- Adjust text size --}}
                                    </div>
                                </div>

                                {{-- Quantity and Remove Controls (Stack on mobile, row on desktop) --}}
                                <div class="flex flex-row sm:flex-row items-center space-x-3 sm:space-x-4 w-full sm:w-auto justify-between sm:justify-start mt-2 sm:mt-0 ml-0 sm:ml-4"> {{-- Adjust layout, spacing, margin, width, and alignment --}}
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center flex-shrink-0"> {{-- Ensure form does not wrap awkwardly --}}
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                               class="w-14 sm:w-16 p-1 text-center border rounded-md shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm border-gray-300 dark:border-dark-border"> {{-- Adjust width --}}
                                        <button type="submit" class="ml-2 text-xs sm:text-sm font-medium text-pink-brand hover:text-pink-brand-dark transition-colors duration-150 flex-shrink-0">Update</button> {{-- Add flex-shrink --}}
                                    </form>

                                    <form action="{{ route('cart.destroy', $item) }}" method="POST" class="flex-shrink-0"> {{-- Add flex-shrink --}}
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150 text-xs sm:text-sm">Hapus</button> {{-- Adjust text size --}}
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 md:mt-8 pt-6 border-t border-gray-200 dark:border-dark-border flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0"> {{-- Add vertical spacing on mobile --}}
                        {{-- Continue Shopping Button --}}
                        <a href="{{ route('homepage') }}"
                           class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 md:px-6 md:py-3 border border-transparent shadow-sm text-sm md:text-base font-medium rounded-md text-white bg-gray-500 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors duration-150"> {{-- Full width on mobile --}}
                            ← Lanjut Belanja
                        </a>

                        {{-- Total and Checkout Button (Stack on mobile, row on desktop) --}}
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full md:w-auto"> {{-- Stack on mobile, row on desktop, full width on mobile --}}
                            <div class="text-center sm:text-right w-full sm:w-auto"> {{-- Center text on mobile, right align on desktop --}}
                                <p class="text-lg font-medium text-gray-900 dark:text-text-light">
                                    Total: <span class="font-bold text-pink-brand dark:text-pink-brand-dark">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </p>
                                <p class="mt-0.5 text-xs md:text-sm text-gray-500 dark:text-gray-400"> {{-- Adjust text size --}}
                                    Pengiriman dan pajak dihitung saat checkout.
                                </p>
                            </div>
                            <a href="{{ route('checkout.index') }}"
                               class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 md:px-6 md:py-3 border border-transparent shadow-sm text-sm md:text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-150"> {{-- Full width on mobile --}}
                                Lanjut ke Checkout →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
