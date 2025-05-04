<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-dark dark:text-text-light leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    {{-- Tambahkan x-data di sini agar state & method Alpine tersedia --}}
    <div class="py-12" x-data="productList()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card utama halaman --}}
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-lg rounded-2xl">
                <div class="p-6 text-text-dark dark:text-text-light">

                    {{-- Notifikasi Sukses --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg relative dark:bg-green-900/30 dark:border-green-700/50 dark:text-green-300" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <button type="button" @click="$event.target.closest('div[role=alert]').remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-green-800/70 dark:text-green-300/70 hover:text-green-900 dark:hover:text-green-100">
                                <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </button>
                        </div>
                    @endif

                    <h3 class="text-2xl font-semibold mb-6 border-b border-gray-200 dark:border-dark-border pb-3">{{ __("Your Wishlisted Items") }}</h3>

                    {{-- Kondisi Wishlist Kosong --}}
                    @if ($wishlistItems->isEmpty())
                        <div class="text-center py-16">
                             <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                             </svg>
                             <h4 class="mt-4 text-lg font-medium text-text-dark dark:text-text-light">{{ __("Wishlist Anda Kosong") }}</h4>
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
                                @php
                                    // Hitung total stok (PASTIKAN CONTROLLER SUDAH EAGER LOAD!)
                                    $totalStock = $item->stockCombinations ? $item->stockCombinations->sum('stock') : 0;
                                @endphp
                                <div class="bg-white dark:bg-dark-subcard rounded-xl shadow-md overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-transparent dark:hover:border-dark-border">
                                    {{-- Gambar Produk --}}
                                    <div class="aspect-square w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';">
                                        @else
                                             <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>

                                    {{-- Detail Teks & Tombol --}}
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h4 class="font-semibold text-base mb-1 text-text-dark dark:text-text-light group-hover:text-pink-brand dark:group-hover:text-pink-brand-dark transition-colors truncate" title="{{ $item->name }}">
                                            {{ $item->name }}
                                        </h4>
                                        <p class="font-bold text-lg text-pink-brand dark:text-pink-brand-dark mb-3">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Grup Tombol Aksi --}}
                                        <div class="mt-auto space-y-2">
                                            {{-- Tombol Beli --}}
                                             <button
                                                {{-- PENTING: Pastikan $item memiliki relasi stockCombinations yg di-load --}}
                                                @click="openBuyModal({{ json_encode($item) }})"
                                                :disabled="{{ $totalStock <= 0 }}"
                                                class="w-full flex items-center justify-center px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white border border-transparent rounded-lg font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                                 <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                </svg>
                                                {{ __('Beli') }}
                                            </button>

                                            {{-- Tombol Hapus dari Wishlist --}}
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

            {{-- =================================== --}}
            {{--        MODAL BELI DIMASUKKAN DI SINI        --}}
            {{-- =================================== --}}
            <div x-show="showBuyModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4" style="display: none;">

                <div x-show="showBuyModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white dark:bg-dark-card rounded-2xl p-5 sm:p-6 w-full max-w-md shadow-xl border dark:border-dark-border"
                    @click.away="closeBuyModal">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-text-dark dark:text-text-light">Form Pembelian</h2>
                        <button @click="closeBuyModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Loading Spinner --}}
                    <template x-if="loading">
                        <div class="text-center py-10">
                            <svg class="animate-spin h-8 w-8 text-pink-brand mx-auto"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-text-light/70">Menyiapkan pilihan...</p>
                        </div>
                    </template>

                    {{-- Form Pembelian --}}
                    <template x-if="!loading && selectedProduct">
                         {{-- Action: Perlu disesuaikan jika slug tidak ada pada model Product --}}
                         {{-- Jika ID digunakan, route('products.purchase', selectedProduct.id) mungkin lebih cocok --}}
                        <form method="POST"
                            :action="selectedProduct && selectedProduct.id ? `{{ url('/products') }}/${selectedProduct.slug}/purchase` : '#'" {{-- Ganti slug dengan ID jika perlu --}}
                            x-ref="buyForm">
                            @csrf

                            {{-- Ringkasan Produk --}}
                            <div
                                class="mb-4 p-3 bg-gray-50 dark:bg-dark-subcard/50 rounded-lg border dark:border-dark-border">
                                <p class="font-medium text-text-dark dark:text-text-light mb-2 text-sm">Detail Produk:
                                </p>
                                <div class="flex items-center space-x-3">
                                    <img :src="getProductImageUrl(selectedProduct)" :alt="selectedProduct.name"
                                        class="w-12 h-12 rounded-md object-cover bg-gray-200 dark:bg-dark-border flex-shrink-0"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';" />
                                    <div>
                                        <p class="text-sm font-semibold text-text-dark dark:text-text-light"
                                            x-text="selectedProduct.name"></p>
                                        <p class="text-sm text-pink-brand dark:text-pink-brand-dark font-bold">Rp <span
                                                x-text="Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Stok Tersedia --}}
                            <details class="mb-4 group">
                                <summary
                                    class="cursor-pointer text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-text-dark dark:hover:text-text-light group-open:mb-2 list-none flex items-center justify-between">
                                    <span>Lihat Stok Tersedia</span>
                                    <svg class="w-4 h-4 transform transition-transform group-open:rotate-180 text-gray-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div
                                    class="text-xs space-y-1.5 max-h-28 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30 mt-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                                    <template
                                        x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                                        <template x-for="item in selectedProduct.stock_combinations"
                                            :key="item.id ?? `${item.size_id}-${item.color_id}`">
                                            <div class="flex items-center gap-2"
                                                :class="{ 'opacity-50': item.stock <= 0 }">
                                                <span
                                                    class="px-1.5 py-0.5 bg-gray-200 dark:bg-dark-border text-[10px] rounded font-medium whitespace-nowrap"
                                                    x-text="item.size?.name ? item.size.name.toUpperCase() : 'N/A'"></span>
                                                <template x-if="item.color?.code">
                                                    <span
                                                        class="w-3 h-3 rounded-full border border-gray-300 dark:border-dark-border inline-block flex-shrink-0"
                                                        :style="'background-color: ' + item.color.code"
                                                        :title="item.color?.name ?? 'Warna tidak diketahui'"></span>
                                                </template>
                                                <span
                                                    class="text-[10px] text-gray-600 dark:text-text-light/80 truncate"
                                                    x-text="item.color?.name ?? 'Tanpa Warna'"></span>
                                                <span
                                                    class="text-[10px] text-gray-500 dark:text-text-light/70 ml-auto font-medium whitespace-nowrap">
                                                    <span x-text="item.stock"></span> pcs
                                                    <template x-if="item.stock <= 0"><span
                                                            class="text-red-500 ml-1">(Habis)</span></template>
                                                </span>
                                            </div>
                                        </template>
                                    </template>
                                    <template
                                        x-if="!selectedProduct.stock_combinations || selectedProduct.stock_combinations.length === 0">
                                        <span class="text-gray-400 italic">Informasi stok tidak tersedia.</span>
                                    </template>
                                </div>
                            </details>

                            {{-- Pilih Ukuran --}}
                            <div class="mb-4">
                                <label for="buy-size"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih
                                    Ukuran</label>
                                <select id="buy-size" name="size_id" x-model="selectedSizeId"
                                    @change="updateAvailableColors(); updateMaxStock();"
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required :disabled="availableSizes.length === 0">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <template x-for="size in availableSizes" :key="size.id">
                                        <option :value="size.id" x-text="size.name"></option>
                                    </template>
                                </select>
                                {{-- Error handling jika ada validasi server-side --}}
                                @error('size_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <template x-if="availableSizes.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                    <span class="text-orange-500 text-xs mt-1">Tidak ada ukuran tersedia.</span>
                                </template>
                            </div>

                            {{-- Pilih Warna --}}
                            <div class="mb-4">
                                <label for="buy-color"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih
                                    Warna</label>
                                <select id="buy-color" name="color_id" x-model="selectedColorId"
                                    @change="updateAvailableSizes(); updateMaxStock();"
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required :disabled="availableColors.length === 0 || !selectedSizeId">
                                    <option value="">-- Pilih Warna --</option>
                                    <template x-for="color in availableColors" :key="color.id">
                                        <option :value="color.id" x-text="color.name"></option>
                                    </template>
                                </select>
                                 {{-- Error handling jika ada validasi server-side --}}
                                @error('color_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <template x-if="selectedSizeId && availableColors.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                    <span class="text-orange-500 text-xs mt-1">Tidak ada warna tersedia untuk ukuran ini.</span>
                                </template>
                            </div>

                            {{-- Jumlah --}}
                            <div class="mb-6">
                                <label for="buy-quantity"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="buy-quantity" type="number" name="quantity"
                                        class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                        min="1" :max="maxStock > 0 ? maxStock : 1" x-model.number="quantity"
                                        @input="validateQuantity()" required
                                        :disabled="!selectedSizeId || !selectedColorId || maxStock <= 0" />
                                    <span x-show="selectedSizeId && selectedColorId && maxStock > 0"
                                        class="ml-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        (Max: <span x-text="maxStock"></span>)
                                    </span>
                                    <span x-show="selectedSizeId && selectedColorId && maxStock <= 0"
                                        class="ml-2 text-xs text-red-500 whitespace-nowrap font-medium">
                                        (Stok Habis)
                                    </span>
                                </div>
                                <p x-show="!selectedSizeId || !selectedColorId" class="mt-1 text-xs text-orange-500">
                                    Pilih ukuran dan warna terlebih dahulu.
                                </p>
                                {{-- Error handling jika ada validasi server-side --}}
                                @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tombol Aksi Modal --}}
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="closeBuyModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-dark-border rounded-lg hover:bg-gray-200 dark:hover:bg-dark-card transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-card">
                                    Batal
                                </button>
                                <button type="submit"
                                    :disabled="!selectedSizeId || !selectedColorId || !quantity || quantity < 1 || maxStock <= 0 || quantity > maxStock"
                                    class="px-4 py-2 text-sm font-medium bg-pink-brand text-white rounded-lg hover:bg-pink-brand-dark transition-colors focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card disabled:opacity-50 disabled:cursor-not-allowed">
                                    Beli Sekarang
                                </button>
                            </div>
                        </form>
                    </template>

                    {{-- Pesan Fallback --}}
                    <template x-if="!loading && !selectedProduct">
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Gagal memuat detail produk.</p>
                    </template>
                </div>
            </div>
            {{-- /Modal Beli --}}

        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- PENTING: Pastikan script AlpineJS productList() dimuat --}}
    @push('scripts')
        {{-- Jika function productList() TIDAK dimuat secara global (misal di app.js), --}}
        {{-- Anda HARUS menyertakan definisinya di sini: --}}
        {{-- <script> function productList() { ... definisi lengkap ... } </script> --}}

        {{-- Contoh jika Anda memuatnya dari file terpisah --}}
        {{-- <script src="{{ asset('js/product-list-alpine.js') }}"></script> --}}
    @endpush

</x-app-layout>
