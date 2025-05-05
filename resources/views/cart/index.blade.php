{{-- resources/views/cart/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Keranjang Belanja Anda
        </h2>
    </x-slot>

    <div class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success') || session('error') || $errors->any())
                {{-- Logic notifikasi dari products.show bisa disalin ke sini --}}
                 {{-- Contoh simple: --}}
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
                 {{-- Handle validation errors from update/destroy if needed --}}
            @endif

            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border p-6 md:p-8">

                @if ($cartItems->isEmpty())
                    <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Keranjang Anda Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tambah item dari halaman produk untuk mulai berbelanja.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('homepage') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-150">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($cartItems as $item)
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-dark-border pb-4">
                                <div class="flex items-center flex-grow">
                                    {{-- Gambar Produk (Opsional) --}}
                                     <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden mr-4 border dark:border-dark-border">
                                         @if ($item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                             <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-dark-subcard text-gray-300 dark:text-gray-500">
                                                  <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                     </div>

                                    <div class="flex-grow">
                                        <h3 class="text-lg font-medium text-text-dark dark:text-text-light">
                                            <a href="{{ route('products.show', $item->product) }}" class="hover:text-pink-brand transition-colors duration-150">{{ $item->product->name }}</a>
                                        </h3>
                                        {{-- Variasi (Size/Color) --}}
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->size ? $item->size->name : '' }}{{ $item->size && $item->color ? ' / ' : '' }}{{ $item->color ? $item->color->name : '' }}
                                        </p>
                                        {{-- Harga Per Item --}}
                                        <p class="text-sm font-medium text-gray-900 dark:text-text-light mt-1">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4 ml-4 flex-shrink-0">
                                    {{-- Form Update Kuantitas --}}
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                               class="w-16 p-1 text-center border rounded-md shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm border-gray-300 dark:border-dark-border">
                                        <button type="submit" class="ml-2 text-sm font-medium text-pink-brand hover:text-pink-brand-dark transition-colors duration-150">Update</button>
                                    </form>

                                    {{-- Form Hapus Item --}}
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total dan Tombol Checkout --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-dark-border flex justify-end items-center">
                        <div class="text-right">
                            <p class="text-lg font-medium text-gray-900 dark:text-text-light">Total: <span class="font-bold text-pink-brand dark:text-pink-brand-dark">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span></p>
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Pengiriman dan pajak dihitung saat checkout.</p>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('checkout.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition-colors duration-150">
                                Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                @endif

            </div> {{-- End Card Utama --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}
</x-app-layout>
