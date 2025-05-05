<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            {{ __('Daftar Keinginan Saya') }} {{-- Mengubah teks judul header --}}
        </h2>
    </x-slot>

    {{-- Hapus x-data="productList()" dari sini --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card utama halaman --}}
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-lg rounded-2xl border border-gray-200 dark:border-dark-border">
                <div class="p-6 text-text-dark dark:text-text-light">

                    {{-- Notifikasi Sukses (Tetap Ada) --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg relative dark:bg-green-900/30 dark:border-green-700/50 dark:text-green-300" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <button type="button" @click="$event.target.closest('div[role=alert]').remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-green-800/70 dark:text-green-300/70 hover:text-green-900 dark:hover:text-green-100">
                                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </button>
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold mb-6 border-b border-gray-200 dark:border-dark-border pb-3">{{ __("Item di Daftar Keinginan Anda") }}</h3> {{-- Mengubah teks judul bagian --}}

                    {{-- Kondisi Wishlist Kosong (Tidak Berubah) --}}
                    @if ($wishlistItems->isEmpty())
                        <div class="text-center py-16">
                             <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                             </svg>
                             <h4 class="mt-4 text-lg font-medium text-text-dark dark:text-text-light">{{ __("Daftar Keinginan Anda Kosong") }}</h4>
                            <p class="mt-2 text-sm text-gray-500 dark:text-text-light/70">{{ __("Sepertinya Anda belum menambahkan item favorit.") }}</p>
                            <a href="{{ route('homepage') }}" class="mt-6 inline-flex items-center px-6 py-2.5 bg-pink-brand border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card transition ease-in-out duration-150">
                                 <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                                {{ __('Mulai Belanja') }}
                            </a>
                        </div>
                    @else
                        {{-- Grid untuk item wishlist --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($wishlistItems as $item)
                                <div class="bg-white dark:bg-dark-subcard rounded-xl shadow-md overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200 dark:border-dark-border hover:border-pink-300 dark:hover:border-pink-brand/50">
                                    {{-- Link ke Detail Page pada Gambar --}}
                                    <a href="{{ route('products.show', $item) }}" class="aspect-square w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden relative group">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';">
                                        @else
                                             <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </a>

                                    {{-- Detail Teks & Tombol --}}
                                    <div class="p-4 flex flex-col flex-grow">
                                         {{-- Link ke Detail Page pada Nama --}}
                                        <h4 class="font-semibold text-base mb-1 text-text-dark dark:text-text-light group-hover:text-pink-brand dark:group-hover:text-pink-brand-dark transition-colors truncate" title="{{ $item->name }}">
                                            <a href="{{ route('products.show', $item) }}" class="hover:underline">
                                                {{ $item->name }}
                                            </a>
                                        </h4>
                                        <p class="font-bold text-lg text-pink-brand dark:text-pink-brand-dark mb-3">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Grup Tombol Aksi --}}
                                        <div class="mt-auto space-y-2">
                                            {{-- Tombol "Beli" Menjadi Link ke Detail --}}
                                             <a href="{{ route('products.show', $item) }}"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white border border-transparent rounded-lg font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard transition ease-in-out duration-150">
                                                 <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                 </svg>
                                                {{ __('Lihat Detail') }}
                                            </a>

                                            {{-- Tombol Hapus dari Wishlist (Tetap Form Server-Side) --}}
                                            <form action="{{ route('wishlist.toggle', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                   class="w-full flex items-center justify-center px-4 py-2 bg-transparent border border-gray-300 dark:border-dark-border text-gray-600 dark:text-text-light/70 hover:bg-gray-100 hover:border-gray-400 dark:hover:bg-dark-border dark:hover:text-text-light rounded-lg font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard transition ease-in-out duration-150">
                                                     <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                    {{ __('Hapus') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div> {{-- End grid --}}
                    @endif

                </div> {{-- End p-6 --}}
            </div> {{-- End bg-white/dark:bg-dark-card --}}

            {{-- >>> MODAL BELI DIHAPUS DARI SINI <<< --}}

        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- Script Alpine.js untuk productList() DIHAPUS DARI SINI --}}
    {{-- @push('scripts') ... @endpush --}}

</x-app-layout>
