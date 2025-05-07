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
                     x-data="productForm({
                         stockCombinations: {{ json_encode($product->stockCombinations->map(fn($c) => ['size_id' => $c->size_id, 'color_id' => $c->color_id, 'stock' => $c->stock])) }},
                         initialSizeId: '{{ old('size_id') }}',
                         initialColorId: '{{ old('color_id') }}',
                         initialQuantity: {{ old('quantity') ? old('quantity') : 'null' }} {{-- Pass null if old('quantity') is empty --}}
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
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-6 border-t border-gray-200 dark:border-dark-border pt-6 space-y-4">
                            @csrf
                             <h2 class="text-xl font-semibold mb-1">Tambahkan ke Keranjang</h2>
                             {{-- Pesan instruksi/error dari Alpine atau server --}}
                              <p class="text-sm text-gray-500 dark:text-text-light/70 mb-4 min-h-[20px]"
                                 x-text="formInstruction"
                                 :class="{ 'text-red-500 dark:text-red-400 font-medium': clientError }">
                                 {{-- Placeholder text will be replaced by Alpine's formInstruction --}}
                              </p>

                             {{-- Pilihan Ukuran & Warna --}}
                            @if($hasVariations) {{-- Cek apakah produk ini punya variasi berdasarkan logic di controller --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Pilih Ukuran --}}
                                    @if($availableSizes->count() > 0) {{-- Hanya tampilkan dropdown ukuran jika ada ukuran tersedia --}}
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
                                    @if($availableColors->count() > 0) {{-- Hanya tampilkan dropdown warna jika ada warna tersedia --}}
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
                            @else
                                {{-- Produk tanpa variasi, kirim size_id dan color_id null secara otomatis --}}
                                {{-- Alpine script akan otomatis menyetel state internal selectedSizeId=null, selectedColorId=null jika tidak ada variasi --}}
                                <input type="hidden" name="size_id" x-bind:value="selectedSizeId"> {{-- Will be null --}}
                                <input type="hidden" name="color_id" x-bind:value="selectedColorId"> {{-- Will be null --}}
                            @endif {{-- End if product has variations --}}


                            {{-- Jumlah --}}
                            <div class="pt-2">
                                <label for="quantity" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="quantity" type="number" name="quantity" min="1"
                                            :max="maxStock > 0 ? maxStock : undefined" {{-- Allow undefined if maxStock is 0 to prevent invalid "max" attribute --}}
                                            x-model.number="quantity"
                                            @input="validateQuantity()"
                                            placeholder="Jumlah"
                                            {{-- Disable jika:
                                                - Ada variasi tapi belum dipilih lengkap (isSelectionComplete is false)
                                                - ATAU kombinasi terpilih tidak tersedia (isCombinationAvailable is false)
                                            --}}
                                            :disabled="((hasSizeVariations || hasColorVariations) && !isSelectionComplete) || !isCombinationAvailable"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('quantity') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }} disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-dark-border">

                                    {{-- Info Stok / Habis. Show only if selection is complete and relevant. --}}
                                    <span x-show="isSelectionComplete && isCombinationAvailable && maxStock > 0" class="ml-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap"> Stok: <span x-text="maxStock"></span> </span>
                                     <span x-show="isSelectionComplete && !isCombinationAvailable && selectedCombination" class="ml-3 text-xs text-red-500 dark:text-red-400 whitespace-nowrap font-medium"> Stok Habis </span>
                                     {{-- Don't show "Stok Habis" if the combination itself wasn't found (selectedCombination is null) --}}
                                </div>
                                {{-- Client side error untuk kuantitas --}}
                                <p x-show="clientError && (clientError.includes('Jumlah') || clientError.includes('minimal') || clientError.includes('melebihi') || clientError.includes('harus diisi'))" class="text-red-500 dark:text-red-400 text-xs mt-1.5" x-text="clientError"></p>
                                @error('quantity') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                             </div>

                             {{-- Tombol Submit (Add to Cart) --}}
                            <div class="pt-4">
                                @guest
                                    {{-- Jika belum login, arahkan ke halaman login --}}
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg> {{-- Icon login --}}
                                        Login untuk Tambah ke Keranjang
                                    </a>
                                @else
                                    {{-- Jika sudah login, tampilkan tombol Add to Cart --}}
                                    <button type="submit" id="add-to-cart-button"
                                        {{-- Kondisi disable:
                                           - Ada clientError
                                           - Jika ada variasi, tapi pilihan belum lengkap (isSelectionComplete is false)
                                           - Kombinasi tidak tersedia (stok 0 atau kombinasi tidak ada) (isCombinationAvailable is false)
                                           - Kuantitas kosong atau < 1 (setelah kombinasi dipilih & tersedia)
                                        --}}
                                        :disabled="!!clientError || ((hasSizeVariations || hasColorVariations) && !isSelectionComplete) || !isCombinationAvailable || !quantity || quantity < 1"
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
                // Data from Blade
                stockCombinations: config.stockCombinations || [],

                // State based on user interaction and config
                selectedSizeId: '',   // Will be set in init()
                selectedColorId: '',  // Will be set in init()
                quantity: null,       // Will be set in init()
                maxStock: 0,

                // Flags derived from stockCombinations data or config
                hasSizeVariations: false,    // Determined in init()
                hasColorVariations: false,   // Determined in init()

                // State flags derived from selections
                isSelectionComplete: false,  // True when all relevant dropdowns have a value
                isCombinationAvailable: false, // True when selected combo exists AND stock > 0
                clientError: '',

                // Init method to set up initial state
                init() {
                    // Determine if product has size/color variations based on actual combinations data
                    this.hasSizeVariations = this.stockCombinations.some(c => c.size_id !== null);
                    this.hasColorVariations = this.stockCombinations.some(c => c.color_id !== null);

                    // Set initial selectedSizeId:
                    // If product has size variations, use initialSizeId from config (old input) or default to ''.
                    // If no size variations, force selectedSizeId to null.
                    this.selectedSizeId = this.hasSizeVariations ? (config.initialSizeId || '') : null;

                    // Set initial selectedColorId (similar logic as size)
                    this.selectedColorId = this.hasColorVariations ? (config.initialColorId || '') : null;

                    // Set initial quantity:
                    // Parse initialQuantity from config. If it's a positive number, use it. Otherwise, null.
                    const initialQty = parseInt(config.initialQuantity);
                    this.quantity = (initialQty > 0) ? initialQty : null;

                    // Trigger initial state update after Alpine has initialized everything
                    this.$nextTick(() => {
                        this.updateMaxStock();
                    });

                    // DEBUG: Initial state
                    // console.log('[INIT] Alpine Initial State:', {
                    //     size: this.selectedSizeId, color: this.selectedColorId, qty: this.quantity,
                    //     hasSize: this.hasSizeVariations, hasColor: this.hasColorVariations,
                    //     stockCombs: this.stockCombinations.length
                    // });
                },

                // Computed Property: Get the currently selected stock combination object
                get selectedCombination() {
                    // Target IDs for lookup. Default to null.
                    // If a variation type exists, use the selected value (mapping '' from dropdown to null for lookup).
                    const targetSizeId = this.hasSizeVariations
                        ? (this.selectedSizeId === '' ? null : parseInt(this.selectedSizeId))
                        : null;
                    const targetColorId = this.hasColorVariations
                        ? (this.selectedColorId === '' ? null : parseInt(this.selectedColorId))
                        : null;

                    return this.stockCombinations.find(
                        c => c.size_id === targetSizeId && c.color_id === targetColorId
                    ) || null;
                },

                // Computed property: Instruction/Error message for the user
                get formInstruction() {
                    if (this.clientError) {
                        return this.clientError; // Prioritize explicit client-side errors
                    }
                    // Provide guidance based on what needs selecting
                    if (this.hasSizeVariations && this.hasColorVariations) {
                        if (this.selectedSizeId === '' && this.selectedColorId === '') return 'Pilih ukuran dan warna.';
                        if (this.selectedSizeId === '') return 'Pilih ukuran.';
                        if (this.selectedColorId === '') return 'Pilih warna.';
                        return 'Masukkan jumlah yang Anda inginkan.';
                    } else if (this.hasSizeVariations) {
                        if (this.selectedSizeId === '') return 'Pilih ukuran.';
                        return 'Masukkan jumlah yang Anda inginkan.';
                    } else if (this.hasColorVariations) {
                        if (this.selectedColorId === '') return 'Pilih warna.';
                        return 'Masukkan jumlah yang Anda inginkan.';
                    }
                    return 'Masukkan jumlah yang Anda inginkan.'; // Simple product
                },

                // Main method to update state when size/color changes
                updateMaxStock() {
                    this.clientError = ''; // Clear previous client error

                    // 1. Determine if selection is complete
                    // Selection is complete if all *existing* variation types have a non-empty value
                    let selectionIsActuallyComplete = true;
                    if (this.hasSizeVariations && this.selectedSizeId === '') selectionIsActuallyComplete = false;
                    if (this.hasColorVariations && this.selectedColorId === '') selectionIsActuallyComplete = false;
                    this.isSelectionComplete = selectionIsActuallyComplete;

                    // Reset availability and stock before re-evaluating
                    this.isCombinationAvailable = false;
                    this.maxStock = 0;

                    // 2. Logic based on selection completeness
                    if (!this.isSelectionComplete && (this.hasSizeVariations || this.hasColorVariations)) {
                        // Case: Product has variations, but selection is incomplete
                        // Error message is handled by formInstruction getter.
                        // No need to set clientError here unless more specific message is needed.
                        this.quantity = null; // Reset quantity if selection is now incomplete
                    } else {
                        // Case: Selection is complete (or product is simple)
                        const combination = this.selectedCombination; // Find the combo based on current state

                        if (combination) {
                            // Combo found in stockCombinations
                            this.maxStock = parseInt(combination.stock) || 0;
                            this.isCombinationAvailable = this.maxStock > 0;

                            if (!this.isCombinationAvailable) {
                                this.clientError = 'Stok untuk kombinasi ini habis.';
                                this.quantity = null; // Reset quantity
                            }
                        } else {
                            // Combo NOT found in stockCombinations (for the current selections)
                            // This occurs if product has variations and the specific user selection doesn't exist
                            // OR if a simple product somehow has no stockCombinations entry (shouldn't happen).
                            if (this.hasSizeVariations || this.hasColorVariations) {
                                 // Only show this if variations exist and were selected, but the combo is invalid
                                 this.clientError = 'Kombinasi pilihan tidak tersedia.';
                            } else if (this.stockCombinations.length === 0) {
                                // Edge case: simple product with no stock data at all
                                this.clientError = 'Produk tidak memiliki data stok.';
                            }
                            // If it's a simple product and stockCombinations[0] exists but was somehow not matched,
                            // it implies an issue with selectedCombination logic for simple products or bad data.
                            // For now, isCombinationAvailable remains false, maxStock 0.
                            this.quantity = null; // Reset quantity
                        }
                    }

                    // 3. Validate quantity after stock and availability are updated
                    this.validateQuantity();

                    // DEBUG: Log state after updateMaxStock
                    // console.log('[updateMaxStock]', {
                    //     size: this.selectedSizeId, color: this.selectedColorId, qty: this.quantity,
                    //     isSelComp: this.isSelectionComplete, isComboAvail: this.isCombinationAvailable,
                    //     maxStock: this.maxStock, error: this.clientError, foundCombo: !!this.selectedCombination
                    // });
                },

                // Method to validate quantity input
                validateQuantity() {
                    // Only validate quantity if the input is supposed to be active and relevant
                    if (!this.isSelectionComplete || !this.isCombinationAvailable || this.maxStock <= 0) {
                        // If combo isn't fully selected, or not available, or no stock,
                        // quantity validation is deferred or quantity input is disabled.
                        // Errors related to combo selection/availability take precedence.
                        // However, if there's an existing quantity-specific error, clear it.
                        if (this.clientError && (this.clientError.includes('Jumlah') || this.clientError.includes('minimal') || this.clientError.includes('melebihi'))) {
                            this.clientError = '';
                        }
                        return;
                    }

                    // Clear previous quantity-specific errors if we are re-validating
                    if (this.clientError && (this.clientError.includes('Jumlah') || this.clientError.includes('minimal') || this.clientError.includes('melebihi') || this.clientError.includes('harus diisi'))) {
                        this.clientError = '';
                    }

                    if (this.quantity === null || this.quantity === undefined || this.quantity === '') {
                        this.clientError = 'Jumlah harus diisi.';
                        return;
                    }
                    if (isNaN(this.quantity)) {
                        this.clientError = 'Jumlah harus berupa angka.';
                        return;
                    }

                    let qty = parseInt(this.quantity);
                    if (qty < 1) {
                        this.clientError = 'Jumlah minimal 1.';
                        return;
                    }
                    if (this.maxStock > 0 && qty > this.maxStock) {
                        this.clientError = `Jumlah melebihi stok tersedia (${this.maxStock} pcs).`;
                        // Do not auto-correct here, let user fix. Button will be disabled.
                        return;
                    }
                    // If all checks pass, clientError (for quantity) remains empty or is cleared.
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
