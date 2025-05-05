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
                     x-data="productPurchaseForm({
                         stockCombinations: {{ json_encode($product->stockCombinations->map(fn($c) => ['size_id' => $c->size_id, 'color_id' => $c->color_id, 'stock' => $c->stock])) }},
                         initialSizeId: '{{ old('size_id') }}',
                         initialColorId: '{{ old('color_id') }}',
                         initialQuantity: {{ old('quantity', 'null') }} // Default null agar bisa kosong
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

                    {{-- Kolom Kanan: Info & Form Pembelian --}}
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

                        {{-- Form Pembelian dengan Alpine --}}
                        <form method="POST" action="{{ route('products.purchase', $product) }}" class="mt-6 border-t border-gray-200 dark:border-dark-border pt-6 space-y-4">
                            @csrf
                             <h2 class="text-xl font-semibold mb-1">Pesan Sekarang</h2>
                             <p class="text-sm text-gray-500 dark:text-text-light/70 mb-4 min-h-[20px]"
                                x-text="formInstruction"
                                :class="{ 'text-red-500 dark:text-red-400 font-medium': clientError }">
                             </p>

                             {{-- Pilihan Ukuran & Warna --}}
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                 {{-- Pilih Ukuran --}}
                                 <div>
                                    <label for="size_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Ukuran</label>
                                    <select id="size_id" name="size_id" {{-- required dihapus --}}
                                            x-model="selectedSizeId" @change="updateMaxStock()"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('size_id') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                        <option value="">-- Pilih Ukuran --</option>
                                        @forelse ($availableSizes as $size) <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @empty <option value="" disabled>Ukuran tidak tersedia</option>
                                        @endforelse
                                    </select>
                                    @error('size_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                 {{-- Pilih Warna --}}
                                 <div>
                                    <label for="color_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Warna</label>
                                    <select id="color_id" name="color_id" {{-- required dihapus --}}
                                            x-model="selectedColorId" @change="updateMaxStock()"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('color_id') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                         <option value="">-- Pilih Warna --</option>
                                        @forelse ($availableColors as $color) <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @empty <option value="" disabled>Warna tidak tersedia</option>
                                        @endforelse
                                    </select>
                                    @error('color_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                 </div>
                             </div>

                            {{-- Jumlah --}}
                            <div class="pt-2">
                                <label for="quantity" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Jumlah</label>
                                <div class="flex items-center">
                                    {{-- required & min="1" dihapus --}}
                                    <input id="quantity" type="number" name="quantity"
                                            :max="maxStock > 0 ? maxStock : undefined"
                                            x-model.number="quantity"
                                            @input="validateQuantity()"
                                            placeholder="Jumlah"
                                            {{-- Tetap disable jika combo belum dipilih / tidak tersedia --}}
                                            :disabled="!isCombinationSelected || !isCombinationAvailable"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('quantity') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }} disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-dark-border">
                                    {{-- Info Stok / Habis --}}
                                    <span x-show="isCombinationSelected && isCombinationAvailable && maxStock > 0" class="ml-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap"> Stok: <span x-text="maxStock"></span> </span>
                                     <span x-show="isCombinationSelected && !isCombinationAvailable" class="ml-3 text-xs text-red-500 dark:text-red-400 whitespace-nowrap font-medium"> Stok Habis </span>
                                </div>
                                {{-- Client & Server side error --}}
                                <p x-show="clientError && clientError !== formInstruction" class="text-red-500 dark:text-red-400 text-xs mt-1.5" x-text="clientError"></p>
                                @error('quantity') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                             </div>

                             {{-- Tombol Submit --}}
                            <div class="pt-4">
                                <button type="submit" id="buy-now-button"
                                    {{-- Kondisi disable: combo belum dipilih ATAU tidak tersedia ATAU quantity kosong/invalid (<1) ATAU ada error client --}}
                                    :disabled="!isCombinationSelected || !isCombinationAvailable || !quantity || quantity < 1 || !!clientError"
                                    class="w-full flex items-center justify-center px-8 py-3 bg-pink-brand text-base font-medium text-white rounded-lg shadow-lg hover:bg-pink-brand-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    Beli Sekarang
                                </button>
                            </div>
                        </form>

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

    {{-- Script Alpine.js untuk Form Pembelian --}}
    @push('scripts')
    <script>
        function productPurchaseForm(config) {
            return {
                // Data
                stockCombinations: config.stockCombinations || [],
                // State
                selectedSizeId: config.initialSizeId || '',
                selectedColorId: config.initialColorId || '',
                quantity: config.initialQuantity === 'null' ? null : (parseInt(config.initialQuantity) || null), // Handle 'null' string from PHP
                maxStock: 0,
                isCombinationSelected: false,
                isCombinationAvailable: false,
                clientError: '',
                defaultInstruction: 'Pilih ukuran dan warna yang Anda inginkan.',

                // Init
                init() {
                    this.$nextTick(() => {
                        // Initial check based on old() values passed from PHP
                        this.updateMaxStock();
                    });
                     // DEBUG: Initial state
                     console.log('[INIT] Alpine Initial State:', {
                        size: this.selectedSizeId,
                        color: this.selectedColorId,
                        qty: this.quantity,
                        combos: this.stockCombinations,
                        initialConfig: config // Log the raw config data
                    });
                },

                // Computed Property (Getter) - Mencari kombinasi stok berdasarkan size & color ID terpilih
                get selectedCombination() {
                    // Memastikan kedua ID terpilih dan valid
                    if (!this.selectedSizeId || !this.selectedColorId) return null;
                    const sizeId = parseInt(this.selectedSizeId);
                    const colorId = parseInt(this.selectedColorId);
                    if (isNaN(sizeId) || isNaN(colorId)) return null;

                    // Cari dalam array stockCombinations
                    const combo = this.stockCombinations.find(
                        c => c.size_id === sizeId && c.color_id === colorId
                    );
                    // DEBUG: Log combo yang ditemukan
                    // console.log(`[Getter selectedCombination] Size: ${sizeId}, Color: ${colorId}, Found:`, combo);
                    return combo || null; // Kembalikan null jika tidak ditemukan
                },

                // Computed property untuk menampilkan instruksi atau error
                get formInstruction() {
                    if (this.clientError && this.clientError !== this.defaultInstruction) {
                        return this.clientError;
                    }
                    return this.defaultInstruction;
                },

                // Computed Property (Getter) - Menentukan apakah tombol submit bisa diaktifkan
                get canSubmit() {
                    const qtyValid = this.quantity !== null && !isNaN(this.quantity) && this.quantity >= 1;
                    const comboValidAndAvailable = this.isCombinationSelected && this.isCombinationAvailable;
                    const noClientError = !this.clientError || this.clientError === this.defaultInstruction; // Allow default instruction

                    // DEBUG: Log kondisi canSubmit
                    // console.log('[Getter canSubmit] Conditions:', {
                    //     comboValidAndAvailable,
                    //     qtyValid,
                    //     noClientError,
                    //     qty: this.quantity,
                    //     maxStock: this.maxStock
                    // });

                    return comboValidAndAvailable && qtyValid && noClientError;
                },


                // Methods
                // Method utama untuk update state berdasarkan pilihan size/color
                updateMaxStock() {
                    // 1. Reset error & status ketersediaan
                    this.clientError = '';
                    this.isCombinationSelected = !!(this.selectedSizeId && this.selectedColorId);
                    this.isCombinationAvailable = false; // Default tidak tersedia
                    this.maxStock = 0; // Default stok 0

                    // 2. Dapatkan kombinasi terpilih menggunakan getter
                    const combination = this.selectedCombination;

                    // 3. Update state berdasarkan hasil pencarian kombinasi
                    if (this.isCombinationSelected) { // Hanya jika KEDUA size & color dipilih
                        if (combination) { // Jika kombinasi ditemukan di data
                            this.maxStock = parseInt(combination.stock) || 0;
                            this.isCombinationAvailable = this.maxStock > 0; // Tersedia jika stok > 0
                            if (!this.isCombinationAvailable) {
                                this.clientError = 'Stok untuk kombinasi ini habis.';
                            }
                            // Jika available, clientError tetap kosong (atau diisi oleh validateQuantity nanti)
                        } else { // Jika kombinasi TIDAK ditemukan (size/color dipilih tapi tidak cocok)
                            this.clientError = 'Kombinasi ukuran dan warna ini tidak tersedia.';
                        }
                    } else { // Jika salah satu atau kedua dropdown belum dipilih
                        // Jangan set error spesifik, biarkan formInstruction menampilkan default
                         if (this.selectedSizeId || this.selectedColorId) {
                           // Beri tahu user apa yg kurang
                           this.clientError = 'Pilih ' + (!this.selectedSizeId ? 'ukuran' : '') + (!this.selectedColorId ? 'warna' : '') + '.';
                        } else {
                           // Jika keduanya kosong, tidak perlu error, hanya instruksi default
                           this.clientError = '';
                        }
                    }

                    // 4. Validasi ulang kuantitas setelah maxStock diupdate
                    this.validateQuantity();

                    // DEBUG: Log state setelah updateMaxStock
                    console.log('[updateMaxStock] State updated:', {
                        size: this.selectedSizeId, color: this.selectedColorId,
                        comboSelected: this.isCombinationSelected, comboAvailable: this.isCombinationAvailable,
                        maxStock: this.maxStock, error: this.clientError, qty: this.quantity
                     });
                },

                // Method untuk validasi input kuantitas secara real-time
                validateQuantity() {
                    // Hapus error *kuantitas* sebelumnya, tapi pertahankan error *kombinasi* jika ada
                     if (this.clientError.includes('Jumlah') || this.clientError.includes('melebihi')) {
                        // Jika error sebelumnya BUKAN tentang kuantitas, jangan dihapus
                        if(!(this.clientError.includes('habis') || this.clientError.includes('tersedia') || this.clientError.includes('Pilih'))) {
                           this.clientError = '';
                        }
                     }


                    // Jika input KOSONG (null dari .number), biarkan kosong & hapus error kuantitas
                    if (this.quantity === null || this.quantity === undefined) {
                         // Hapus error spesifik kuantitas jika ada
                         if (this.clientError.includes('Jumlah') || this.clientError.includes('melebihi')) {
                             this.clientError = ''; // Kosong = valid sementara, tombol akan disable
                         }
                        console.log('[validateQuantity] Quantity is empty/null.');
                        return; // Stop validasi
                    }

                    // Jika BUKAN ANGKA valid
                    if (isNaN(this.quantity)) {
                        this.clientError = 'Jumlah harus berupa angka.';
                         console.log('[validateQuantity] Quantity is NaN.');
                        return;
                    }

                    // Konversi ke integer
                    let qty = parseInt(this.quantity);

                    // Jika < 1 (setelah dipastikan angka)
                    if (qty < 1) {
                        this.clientError = 'Jumlah minimal 1.';
                         console.log(`[validateQuantity] Quantity ${qty} < 1.`);
                        // Jangan auto-correct
                        return;
                    }

                    // Batasi agar TIDAK MELEBIHI maxStock (jika combo tersedia)
                    if (this.isCombinationAvailable && this.maxStock > 0) {
                        if (qty > this.maxStock) {
                            this.quantity = this.maxStock; // Koreksi otomatis ke maxStock
                             console.log(`[validateQuantity] Quantity corrected to maxStock: ${this.maxStock}.`);
                            // Hapus error spesifik kuantitas karena sudah dikoreksi
                             if (this.clientError.includes('Jumlah') || this.clientError.includes('melebihi')) {
                                this.clientError = '';
                             }
                        }
                    } else if (this.isCombinationSelected && !this.isCombinationAvailable) {
                        // Jika combo dipilih tapi stok habis, set qty ke null/kosong
                        // agar user harus input lagi jika combo lain dipilih
                         console.log(`[validateQuantity] Combo not available, resetting quantity.`);
                         this.quantity = null; // Reset quantity
                    }


                    // DEBUG: Log state akhir validasi kuantitas
                    // console.log(`[validateQuantity] Final: Qty=${this.quantity}, Max=${this.maxStock}, Available=${this.isCombinationAvailable}, Error='${this.clientError}'`);
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
