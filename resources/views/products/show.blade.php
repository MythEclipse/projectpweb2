{{-- File: resources/views/products/show.blade.php --}}
<x-app-layout>
    {{-- Header dengan Breadcrumbs --}}
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    {{-- Link ke Homepage --}}
                    <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-pink-brand dark:text-gray-400 dark:hover:text-white transition-colors duration-150">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Home
                    </a>
                </li>
                {{-- Nama Produk (Current Page) --}}
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-text-light/80">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </x-slot>

    <div class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses/Error/Validasi --}}
            @if (session('success') || session('error') || $errors->any())
            <div class="mb-6">
                 {{-- Menampilkan error validasi server-side --}}
                 @if ($errors->any())
                    <div x-data="{ showNotif: true }" x-show="showNotif" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="rounded-xl p-4 shadow-md bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700/50 text-red-800 dark:text-red-200 mb-4" role="alert">
                         <div class="flex">
                             <div class="flex-shrink-0">
                                 <svg class="h-5 w-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="ml-3 flex-grow">
                                <h3 class="text-sm font-semibold">Terdapat {{ $errors->count() }} {{ Str::plural('kesalahan', $errors->count()) }}:</h3>
                                <div class="mt-2 text-sm">
                                    <ul role="list" class="list-disc space-y-1 pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" @click="showNotif = false" class="ml-auto -mr-1 -mt-1 p-1 rounded-md focus:outline-none focus:ring-2 text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 focus:ring-red-500">
                                <span class="sr-only">Tutup</span> <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                         </div>
                    </div>
                 @else
                    {{-- Menampilkan notifikasi sukses/error biasa dari session --}}
                    <div x-data="{ showNotif: true }" x-show="showNotif" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="rounded-xl p-4 shadow-md {{ session('success') ? 'bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700/50 text-red-800 dark:text-red-200' }}"
                        role="alert">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ session('success') ? 'Berhasil!' : 'Gagal!' }}</span>
                            <span class="text-sm ml-3 flex-grow">{{ session('success') ?? session('error') }}</span>
                            <button type="button" @click="showNotif = false" class="ml-auto -mr-1 p-1 rounded-md focus:outline-none focus:ring-2 {{ session('success') ? 'text-green-600 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800/50 focus:ring-green-500' : 'text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 focus:ring-red-500' }}">
                               <span class="sr-only">Tutup</span> <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- Card Utama Detail Produk --}}
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border">
                {{-- Inisialisasi Alpine.js --}}
                <div class="p-6 md:p-8 lg:flex lg:gap-10"
                     x-data="productForm({ {{-- Renamed from productPurchaseForm to productForm to be more general --}}
                         stockCombinations: {{ json_encode($product->stockCombinations->map(fn($c) => ['size_id' => $c->size_id, 'color_id' => $c->color_id, 'stock' => $c->stock])) }},
                         initialSizeId: '{{ old('size_id') }}',
                         initialColorId: '{{ old('color_id') }}',
                         initialQuantity: {{ old('quantity', '1') }} // Default quantity to 1 for Add to Cart
                     })">

                    {{-- Kolom Kiri: Gambar --}}
                    <div class="lg:w-5/12 xl:w-4/12 mb-6 lg:mb-0 flex-shrink-0">
                        <div class="aspect-square bg-gray-100 dark:bg-dark-subcard rounded-xl flex items-center justify-center overflow-hidden shadow-lg border dark:border-dark-border">
                             @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" onerror="this.onerror=null; this.src='https://via.placeholder.com/400/EEEEEE/AAAAAA?text=Image+Error';">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-dark-subcard dark:to-dark-border"> <svg class="w-20 h-20 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kolom Kanan: Info & Form Add to Cart --}}
                    <div class="lg:w-7/12 xl:w-8/12 text-text-dark dark:text-text-light">
                        {{-- Nama & Harga --}}
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-2 tracking-tight">{{ $product->name }}</h1>
                        <p class="text-3xl font-bold text-pink-brand dark:text-pink-brand-dark mb-5">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Tombol Wishlist --}}
                        @auth
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-6">
                                @csrf
                                @php $isInWishlist = Auth::user()->hasInWishlist($product); @endphp
                                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-card {{ $isInWishlist ? 'bg-red-100 border-red-300 text-red-700 hover:bg-red-200 dark:bg-red-900/50 dark:border-dark-border dark:text-red-300 hover:dark:bg-red-800/60 focus:ring-red-500' : 'bg-pink-100 border-pink-300 text-pink-700 hover:bg-pink-200 dark:bg-pink-900/50 dark:border-dark-border dark:text-pink-300 hover:dark:bg-pink-800/60 focus:ring-pink-500' }}">
                                     @if ($isInWishlist) <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg> Hapus dari Wishlist
                                     @else <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg> Tambah ke Wishlist
                                     @endif
                                </button>
                            </form>
                        @endauth

                        {{-- Form Add to Cart dengan Alpine --}}
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-6 border-t border-gray-200 dark:border-dark-border pt-6 space-y-4"
                              x-data="productForm({
                                 stockCombinations: {{ json_encode($product->stockCombinations->map(fn($c) => ['size_id' => $c->size_id, 'color_id' => $c->color_id, 'stock' => $c->stock])) }},
                                 initialSizeId: '{{ old('size_id') }}',
                                 initialColorId: '{{ old('color_id') }}',
                                 initialQuantity: {{ old('quantity', '1') }} // Default quantity to 1 for Add to Cart
                              })">
                            @csrf
                             <h2 class="text-xl font-semibold mb-1">Tambahkan ke Keranjang</h2>
                             {{-- Pesan instruksi/error dari Alpine atau server --}}
                              <p class="text-sm text-gray-500 dark:text-text-light/70 mb-4 min-h-[20px]"
                                 x-text="formInstruction" {{-- Menggunakan getter formInstruction --}}
                                 :class="{ 'text-red-500 dark:text-red-400 font-medium': clientError }"> {{-- Class applied if clientError exists --}}
                                 Pilih ukuran dan warna yang Anda inginkan dan masukkan jumlah.
                              </p>

                             {{-- Pilihan Ukuran & Warna --}}
                             {{-- Hanya tampilkan dropdown jika produk punya variasi ukuran/warna --}}
                            @php
                                // Determine if the product has size or color variations (i.e., not just one null/null combination)
                                $hasVariations = $product->stockCombinations->count() > 1 ||
                                                 ($product->stockCombinations->count() === 1 && ($product->stockCombinations->first()->size_id !== null || $product->stockCombinations->first()->color_id !== null));
                            @endphp

                            @if($hasVariations)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Pilih Ukuran --}}
                                    @if($availableSizes->count() > 0)
                                        <div>
                                            <label for="size_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Ukuran</label>
                                            <select id="size_id" name="size_id"
                                                    x-model="selectedSizeId" @change="updateMaxStock()"
                                                    class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('size_id') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                                <option value="">-- Pilih Ukuran --</option>
                                                @foreach ($availableSizes as $size) <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('size_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                    {{-- Pilih Warna --}}
                                    @if($availableColors->count() > 0)
                                        <div>
                                            <label for="color_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Warna</label>
                                            <select id="color_id" name="color_id"
                                                    x-model="selectedColorId" @change="updateMaxStock()"
                                                    class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('color_id') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                                <option value="">-- Pilih Warna --</option>
                                                @foreach ($availableColors as $color) <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('color_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                </div>
                                {{-- Hidden inputs for null values if only one type of variation exists (e.g., only sizes, no colors) --}}
                                @if($availableSizes->count() > 0 && $availableColors->count() === 0)
                                    <input type="hidden" name="color_id" value=""> {{-- Ensure color_id is submitted as null if no color options --}}
                                @endif
                                @if($availableColors->count() > 0 && $availableSizes->count() === 0)
                                     <input type="hidden" name="size_id" value=""> {{-- Ensure size_id is submitted as null if no size options --}}
                                @endif
                            @else
                                {{-- Produk tanpa variasi, kirim size_id dan color_id null secara otomatis --}}
                                <input type="hidden" name="size_id" value="">
                                <input type="hidden" name="color_id" value="">
                            @endif {{-- End if product has variations --}}


                            {{-- Jumlah --}}
                            <div class="pt-2">
                                <label for="quantity" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="quantity" type="number" name="quantity"
                                            :max="maxStock > 0 ? maxStock : undefined"
                                            x-model.number="quantity"
                                            @input="validateQuantity()"
                                            placeholder="Jumlah"
                                            {{-- Tetap disable jika combo belum dipilih ATAU tidak tersedia --}}
                                            :disabled="!isCombinationSelected || !isCombinationAvailable"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('quantity') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }} disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-dark-border">
                                    {{-- Info Stok / Habis --}}
                                    <span x-show="isCombinationSelected && isCombinationAvailable && maxStock > 0" class="ml-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap"> Stok: <span x-text="maxStock"></span> </span>
                                     <span x-show="isCombinationSelected && !isCombinationAvailable" class="ml-3 text-xs text-red-500 dark:text-red-400 whitespace-nowrap font-medium"> Stok Habis </span>
                                </div>
                                {{-- Client side error untuk kuantitas --}}
                                {{-- Tampilkan error kuantitas hanya jika clientError terkait kuantitas --}}
                                <p x-show="clientError && (clientError.includes('Jumlah') || clientError.includes('minimal') || clientError.includes('melebihi'))" class="text-red-500 dark:text-red-400 text-xs mt-1.5" x-text="clientError"></p>
                                @error('quantity') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                             </div>

                             {{-- Catatan (Opsional) - DIHAPUS karena catatan di level Transaksi, bukan CartItem --}}

                             {{-- Tombol Submit (Add to Cart) --}}
                            <div class="pt-4">
                                @guest
                                    {{-- Jika belum login, arahkan ke halaman login --}}
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg> {{-- Icon login --}}
                                        Login untuk Tambah ke Keranjang
                                    </a>
                                @else
                                    {{-- Jika sudah login, tampilkan tombol Add to Cart --}}
                                    <button type="submit" id="add-to-cart-button"
                                        {{-- Kondisi disable:
                                           - Ada clientError (error kombinasi atau kuantitas)
                                           - Kombinasi belum dipilih (hanya berlaku jika produk punya variasi)
                                           - Kombinasi tidak tersedia (stok 0)
                                           - Kuantitas kosong atau < 1 (hanya relevan jika combo sudah dipilih & tersedia)
                                        --}}
                                        :disabled="!!clientError || !isCombinationSelected || !isCombinationAvailable || !quantity || quantity < 1"
                                        class="w-full flex items-center justify-center px-8 py-3 bg-pink-brand text-base font-medium text-white rounded-lg shadow-lg hover:bg-pink-brand-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{-- Icon plus circle --}}
                                        Tambah ke Keranjang
                                    </button>
                                @endguest
                            </div>
                        </form>

                        {{-- Link ke Halaman Keranjang --}}
                        <div class="mt-4 text-center text-sm">
                             @guest
                                 <span class="text-gray-500 dark:text-gray-400">Belum punya akun? <a href="{{ route('register') }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand-dark dark:hover:text-pink-brand underline transition-colors duration-150">Daftar di sini</a></span>
                             @else
                                <a href="{{ route('cart.index') }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand-dark dark:hover:text-pink-brand underline transition-colors duration-150">Lihat Keranjang Anda</a>
                            @endguest
                        </div>


                         {{-- Deskripsi Produk --}}
                         <div class="mt-10 border-t border-gray-200 dark:border-dark-border pt-6">
                             <h2 class="text-lg font-semibold mb-3 text-text-dark dark:text-text-light">Deskripsi Produk</h2>
                             <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-text-light/80 leading-relaxed space-y-3">
                                 {!! nl2br(e($product->description ?: 'Tidak ada deskripsi untuk produk ini.')) !!}
                             </div>
                         </div>
                    </div> {{-- End Kolom Kanan --}}
                </div> {{-- End Alpine container --}}
            </div> {{-- End Card Utama --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- Script Alpine.js untuk Form Add to Cart --}}
    @push('scripts')
    <script>
        function productForm(config) {
            return {
                // Data
                stockCombinations: config.stockCombinations || [],
                // State
                selectedSizeId: config.initialSizeId || '', // Use '' for initial empty dropdown state
                selectedColorId: config.initialColorId || '', // Use '' for initial empty dropdown state
                quantity: config.initialQuantity === 'null' ? null : (parseInt(config.initialQuantity) || null),
                maxStock: 0,
                isCombinationSelected: false, // True when size/color selected OR product is simple
                isCombinationAvailable: false, // True when selected combo exists and stock > 0
                clientError: '',
                defaultInstruction: 'Pilih ukuran dan warna yang Anda inginkan dan masukkan jumlah.',

                // Determine if product has variations based on stockCombinations
                get hasVariations() {
                    return this.stockCombinations.length > 0 &&
                           (this.stockCombinations[0].size_id !== null || this.stockCombinations[0].color_id !== null);
                },

                // Init
                init() {
                    this.$nextTick(() => {
                        // If product has no variations, treat the single combo as selected initially
                        if (!this.hasVariations) {
                             // Explicitly set internal state to nulls matching the single combo
                             this.selectedSizeId = null;
                             this.selectedColorId = null;
                        } else {
                             // For products *with* variations, ensure old() values are parsed correctly
                             // and dropdowns start with '' if old() was empty/invalid
                             if (config.initialSizeId === '') this.selectedSizeId = ''; // Keep empty string for dropdown
                             if (config.initialColorId === '') this.selectedColorId = ''; // Keep empty string for dropdown
                        }
                        // Now update state based on initial values
                        this.updateMaxStock();
                    });
                     // DEBUG: Initial state
                     console.log('[INIT] Alpine Initial State:', {
                        size: this.selectedSizeId,
                        color: this.selectedColorId,
                        qty: this.quantity,
                        combos: this.stockCombinations,
                        hasVariations: this.hasVariations,
                        initialConfig: config
                     });
                },

                // Computed Property (Getter) - Mencari kombinasi stok berdasarkan size & color ID terpilih
                get selectedCombination() {
                    // Handle simple product case (no variations)
                    if (!this.hasVariations && this.stockCombinations.length === 1) {
                         return this.stockCombinations[0];
                    }

                    // For products with variations, require both size and color ID to be selected and valid
                    // Convert '' to null for lookup if needed, but lookup uses parsed ints anyway
                    const sizeId = this.selectedSizeId === '' ? null : parseInt(this.selectedSizeId);
                    const colorId = this.selectedColorId === '' ? null : parseInt(this.selectedColorId);

                    // Return null if variations are required but not both selected
                     if (this.hasVariations && (this.selectedSizeId === '' || this.selectedColorId === '')) {
                         return null;
                     }

                    // Find the matching combo in the array based on parsed IDs
                    const combo = this.stockCombinations.find(
                        c => c.size_id === sizeId && c.color_id === colorId
                    );

                    return combo || null; // Return the combo or null if not found
                },

                // Computed property for displaying instructions or errors
                get formInstruction() {
                    if (this.clientError) { // Show clientError if it exists
                        return this.clientError;
                    }
                     // Show default instruction if no error
                    return this.defaultInstruction;
                },

                // Methods
                // Method utama untuk update state berdasarkan pilihan size/color
                updateMaxStock() {
                    // Clear previous error at the start of updating state
                    this.clientError = '';

                    // Determine if a combination is "selected" based on product type
                    if (this.hasVariations) {
                        // For products with variations, both dropdowns must have a non-empty value
                        this.isCombinationSelected = !!(this.selectedSizeId && this.selectedColorId);
                    } else {
                        // For simple products (no variations), the combination is always considered selected if there's a combo
                         this.isCombinationSelected = this.stockCombinations.length > 0;
                         // Ensure internal model reflects null IDs for simple products if dropdowns are hidden
                         this.selectedSizeId = null;
                         this.selectedColorId = null;
                    }

                    this.isCombinationAvailable = false; // Default to false
                    this.maxStock = 0; // Default stock to 0

                    // Get the selected/relevant combination
                    const combination = this.selectedCombination;

                    // Update state based on the combination found
                    if (this.isCombinationSelected) { // If a combo *could* be selected (either variaitonless or both selected)
                        if (combination) { // If the combination (based on current selections or simple product) is found
                            this.maxStock = parseInt(combination.stock) || 0;
                            this.isCombinationAvailable = this.maxStock > 0; // Available if stock > 0
                            if (!this.isCombinationAvailable) {
                                // Specific error if combination found but stock is zero
                                this.clientError = 'Stok untuk kombinasi ini habis.';
                                this.quantity = null; // Reset quantity if stock is zero for the selected combo
                            }
                            // If available and has stock, clientError remains cleared (will be set by validateQuantity if qty is bad)
                        } else { // If the selected combo (for variated products) is NOT found (invalid selection)
                            this.clientError = 'Kombinasi ukuran dan warna ini tidak tersedia.';
                            this.quantity = null; // Reset quantity for invalid combo
                            this.isCombinationAvailable = false; // Ensure availability is false
                        }
                    } else { // If one or both dropdowns are NOT selected (and product has variations)
                         // Instruct user to select (only for variated products where selection is incomplete)
                         if (this.hasVariations) {
                             this.clientError = 'Pilih ' + (!this.selectedSizeId ? 'ukuran' : '') + ((this.selectedSizeId === '' && this.selectedColorId === '') ? ' ukuran dan warna' : ((this.selectedSizeId !== '' && this.selectedColorId === '') ? 'warna' : 'ukuran')) + '.';
                         } else {
                             // For simple product that has no combinations at all (very rare, should be handled server-side too)
                              this.clientError = 'Produk tidak tersedia.';
                         }
                         this.quantity = null; // Reset quantity if combo is incomplete/invalid
                         this.isCombinationAvailable = false; // Ensure availability is false
                    }

                    // Always call validateQuantity to check quantity state AFTER combo state is updated
                    this.validateQuantity();

                    // DEBUG: Log state after updateMaxStock
                     console.log('[updateMaxStock] State updated:', {
                        size: this.selectedSizeId, color: this.selectedColorId,
                        hasVariations: this.hasVariations,
                        comboSelected: this.isCombinationSelected, comboAvailable: this.isCombinationAvailable,
                        maxStock: this.maxStock, error: this.clientError, qty: this.quantity
                     });
                },

                // Method untuk validasi input kuantitas secara real-time
                validateQuantity() {
                     // This validation is only relevant if a valid and available combination is selected
                     if (!this.isCombinationSelected || !this.isCombinationAvailable || this.maxStock <= 0) {
                         // If combo is not valid/available, quantity input is disabled.
                         // No quantity-specific error should be set here.
                         // The clientError will already reflect the combo issue set by updateMaxStock.
                         // console.log('[validateQuantity] Skipping quantity validation: Combo not selected/available or stock is zero.');
                         return;
                     }

                     // If combo IS valid and available, proceed to validate quantity.
                     // Clear any *previous* quantity-specific errors as we are re-validating.
                     // IMPORTANT: Do NOT clear combination errors here. updateMaxStock handles that.
                     if (this.clientError.includes('Jumlah') || this.clientError.includes('minimal') || this.clientError.includes('melebihi')) {
                         this.clientError = ''; // Clear previous quantity errors
                     }


                    // Check if quantity input is empty, null, or undefined AFTER combo is selected
                    if (this.quantity === null || this.quantity === undefined || this.quantity === '') {
                        this.clientError = 'Jumlah harus diisi.';
                        // console.log('[validateQuantity] Quantity is empty/null/"" after combo selected.');
                        return;
                    }

                    // Check if it's a valid number
                    if (isNaN(this.quantity)) {
                        this.clientError = 'Jumlah harus berupa angka.';
                        // console.log('[validateQuantity] Quantity is NaN.');
                        return;
                    }

                    // Parse as integer
                    let qty = parseInt(this.quantity);

                    // Check minimum quantity
                    if (qty < 1) {
                        this.clientError = 'Jumlah minimal 1.';
                        // console.log(`[validateQuantity] Quantity ${qty} < 1.`);
                        return;
                    }

                    // Check against max stock
                    if (this.maxStock > 0 && qty > this.maxStock) {
                        this.clientError = `Jumlah melebihi stok tersedia (${this.maxStock} pcs).`;
                        // console.log(`[validateQuantity] Quantity ${qty} > maxStock ${this.maxStock}.`);
                        // Do not auto-correct, let user fix it
                        return;
                    }

                    // If all quantity validations pass, ensure no quantity-specific error remains
                    // If there was a non-quantity error (like a combo error), updateMaxStock should have cleared it
                    // when the combo became valid, or it shouldn't be cleared here anyway.
                    // So, if we reach here, the quantity is valid, and any existing clientError is either related to a combo (which shouldn't happen if we're in this branch) or should be cleared.
                    // The updateMaxStock handles clearing combination errors when a valid combo is found.
                    // So simply clearing specific quantity errors at the start of THIS method is sufficient.

                    // DEBUG: Log state after valid quantity validation
                    // console.log(`[validateQuantity] Final: Qty=${this.quantity}, Max=${this.maxStock}, Available=${this.isCombinationAvailable}, Error='${this.clientError}'`);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
