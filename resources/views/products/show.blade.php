{{-- File: resources/views/products/show.blade.php --}}
<x-app-layout>
    {{-- Header dengan Breadcrumbs --}}
    <x-slot name="header">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('homepage') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-pink-brand dark:text-gray-400 dark:hover:text-white transition-colors duration-150">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Home
                    </a>
                </li>
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
            {{-- Notifikasi --}}
            @if (session('success') || session('error') || $errors->any())
            <div class="mb-6">
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

            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border">
                <div class="p-6 md:p-8 lg:flex lg:gap-10"
                     x-data="productForm({
                         stockCombinations: {{ json_encode($product->stockCombinations->map(fn($c) => ['size_id' => $c->size_id, 'color_id' => $c->color_id, 'stock' => $c->stock])) }},
                         productAttributeLists: {
                             sizes: {{ json_encode($availableSizes->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()) }},
                             colors: {{ json_encode($availableColors->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()) }}
                         },
                         initialSizeId: '{{ old('size_id') }}',
                         initialColorId: '{{ old('color_id') }}',
                         initialQuantity: {{ old('quantity') ? old('quantity') : 'null' }}
                     })">

                    <div class="lg:w-5/12 xl:w-4/12 mb-6 lg:mb-0 flex-shrink-0">
                        <div class="aspect-square bg-gray-100 dark:bg-dark-subcard rounded-xl flex items-center justify-center overflow-hidden shadow-lg border dark:border-dark-border">
                             @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" onerror="this.onerror=null; this.src='https://via.placeholder.com/400/EEEEEE/AAAAAA?text=Image+Error';">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-dark-subcard dark:to-dark-border"> <svg class="w-20 h-20 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> </div>
                            @endif
                        </div>
                    </div>

                    <div class="lg:w-7/12 xl:w-8/12 text-text-dark dark:text-text-light">
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-2 tracking-tight">{{ $product->name }}</h1>
                        <p class="text-3xl font-bold text-pink-brand dark:text-pink-brand-dark mb-5">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

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

                        <form method="POST" action="{{ route('cart.store', $product) }}" class="mt-6 border-t border-gray-200 dark:border-dark-border pt-6 space-y-4">
                            @csrf
                             <h2 class="text-xl font-semibold mb-1">Tambahkan ke Keranjang</h2>
                              <p class="text-sm text-gray-500 dark:text-text-light/70 mb-4 min-h-[20px]"
                                 x-text="formInstruction"
                                 :class="{ 'text-red-500 dark:text-red-400 font-medium': clientError }">
                              </p>

                            @if($hasVariations) {{-- True jika ada $availableSizes atau $availableColors dari controller --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Pilih Ukuran --}}
                                    @if($availableSizes->count() > 0)
                                        <div>
                                            <label for="size_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Ukuran</label>
                                            <select id="size_id" name="size_id"
                                                    x-model="selectedSizeId" @change="updateMaxStock()"
                                                    class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('size_id') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                                <option value="">-- Pilih Ukuran --</option>
                                                <template x-for="size in dynamicAvailableSizes" :key="size.id">
                                                    <option :value="size.id" x-text="size.name"></option>
                                                </template>
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
                                                <template x-for="color in dynamicAvailableColors" :key="color.id">
                                                    <option :value="color.id" x-text="color.name"></option>
                                                </template>
                                            </select>
                                            @error('color_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                </div>
                            @else
                                <input type="hidden" name="size_id" x-bind:value="selectedSizeId">
                                <input type="hidden" name="color_id" x-bind:value="selectedColorId">
                            @endif


                            <div class="pt-2">
                                <label for="quantity" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="quantity" type="number" name="quantity" min="1"
                                            :max="maxStock > 0 ? maxStock : undefined"
                                            x-model.number="quantity"
                                            @input="validateQuantity()"
                                            placeholder="Jumlah"
                                            :disabled="((hasSizeVariations || hasColorVariations) && !isSelectionComplete) || !isCombinationAvailable"
                                            class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('quantity') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }} disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-dark-border">

                                    <span x-show="isSelectionComplete && isCombinationAvailable && maxStock > 0" class="ml-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap"> Stok: <span x-text="maxStock"></span> </span>
                                     <span x-show="isSelectionComplete && !isCombinationAvailable && selectedCombination" class="ml-3 text-xs text-red-500 dark:text-red-400 whitespace-nowrap font-medium"> Stok Habis </span>
                                </div>
                                <p x-show="clientError && (clientError.includes('Jumlah') || clientError.includes('minimal') || clientError.includes('melebihi') || clientError.includes('harus diisi'))" class="text-red-500 dark:text-red-400 text-xs mt-1.5" x-text="clientError"></p>
                                @error('quantity') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                             </div>

                            <div class="pt-4">
                                @guest
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                        Login untuk Tambah ke Keranjang
                                    </a>
                                @else
                                    <button type="submit" id="add-to-cart-button"
                                        :disabled="!!clientError || ((hasSizeVariations || hasColorVariations) && !isSelectionComplete) || !isCombinationAvailable || !quantity || quantity < 1"
                                        class="w-full flex items-center justify-center px-8 py-3 bg-pink-brand text-base font-medium text-white rounded-lg shadow-lg hover:bg-pink-brand-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Tambah ke Keranjang
                                    </button>
                                @endguest
                            </div>
                        </form>

                        <div class="mt-4 text-center text-sm">
                             @guest
                                 <span class="text-gray-500 dark:text-gray-400">Belum punya akun? <a href="{{ route('register') }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand-dark dark:hover:text-pink-brand underline transition-colors duration-150">Daftar di sini</a></span>
                             @else
                                <a href="{{ route('cart.index') }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand-dark dark:hover:text-pink-brand underline transition-colors duration-150">Lihat Keranjang Anda</a>
                            @endguest
                        </div>

                         <div class="mt-10 border-t border-gray-200 dark:border-dark-border pt-6">
                             <h2 class="text-lg font-semibold mb-3 text-text-dark dark:text-text-light">Deskripsi Produk</h2>
                             <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-text-light/80 leading-relaxed space-y-3">
                                 {!! nl2br(e($product->description ?: 'Tidak ada deskripsi untuk produk ini.')) !!}
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function productForm(config) {
            return {
                stockCombinations: config.stockCombinations || [],
                productAttributeLists: config.productAttributeLists || { sizes: [], colors: [] },

                selectedSizeId: '',
                selectedColorId: '',
                quantity: null,
                maxStock: 0,

                hasSizeVariations: false,
                hasColorVariations: false,

                isSelectionComplete: false,
                isCombinationAvailable: false,
                clientError: '',

                init() {
                    this.hasSizeVariations = this.productAttributeLists.sizes.length > 0 && this.stockCombinations.some(c => c.size_id !== null);
                    this.hasColorVariations = this.productAttributeLists.colors.length > 0 && this.stockCombinations.some(c => c.color_id !== null);

                    this.selectedSizeId = this.hasSizeVariations ? (config.initialSizeId || '') : null;
                    this.selectedColorId = this.hasColorVariations ? (config.initialColorId || '') : null;

                    const initialQty = parseInt(config.initialQuantity);
                    this.quantity = (initialQty > 0) ? initialQty : null;

                    this.$nextTick(() => {
                        this.updateMaxStock(); // Initial update based on old input or defaults
                    });

                    // Watchers to auto-reset other dropdown if selection becomes invalid
                    this.$watch('selectedSizeId', (newValue, oldValue) => {
                        if (newValue !== oldValue && this.hasColorVariations && this.selectedColorId) {
                            const currentTargetSizeId = newValue === '' ? null : parseInt(newValue);
                            const currentTargetColorId = this.selectedColorId === '' ? null : parseInt(this.selectedColorId);

                            if (currentTargetSizeId !== null && currentTargetColorId !== null) {
                                const isValid = this.stockCombinations.some(c =>
                                    c.size_id === currentTargetSizeId &&
                                    c.color_id === currentTargetColorId &&
                                    c.stock > 0 // Check against stock as well for validity in this context
                                );
                                if (!isValid) {
                                    this.selectedColorId = ''; // Reset color if current one is no longer valid with new size
                                    // updateMaxStock() will be called by @change on color select if it was bound,
                                    // but since we change it programmatically, we might need to call it if not.
                                    // However, the primary @change is on the element user interacts with.
                                    // The effect of this reset will be picked up by updateMaxStock when it's called.
                                }
                            }
                        }
                        // updateMaxStock() is already called by @change on the select element itself
                    });

                    this.$watch('selectedColorId', (newValue, oldValue) => {
                        if (newValue !== oldValue && this.hasSizeVariations && this.selectedSizeId) {
                             const currentTargetColorId = newValue === '' ? null : parseInt(newValue);
                             const currentTargetSizeId = this.selectedSizeId === '' ? null : parseInt(this.selectedSizeId);

                            if (currentTargetColorId !== null && currentTargetSizeId !== null) {
                                const isValid = this.stockCombinations.some(c =>
                                    c.color_id === currentTargetColorId &&
                                    c.size_id === currentTargetSizeId &&
                                    c.stock > 0
                                );
                                if (!isValid) {
                                    this.selectedSizeId = ''; // Reset size
                                }
                            }
                        }
                    });
                },

                get dynamicAvailableSizes() {
                    if (!this.hasSizeVariations) return [];
                    const baseSizes = this.productAttributeLists.sizes;

                    if (this.hasColorVariations && this.selectedColorId && this.selectedColorId !== '') {
                        const colorId = parseInt(this.selectedColorId);
                        const validSizeIds = this.stockCombinations
                            .filter(c => c.color_id === colorId && c.size_id !== null && c.stock > 0)
                            .map(c => c.size_id);
                        return baseSizes.filter(s => validSizeIds.includes(s.id));
                    } else {
                        // No color selected or no color variations: show all sizes that are in any stocked combination
                        const allStockedSizeIds = this.stockCombinations
                            .filter(c => c.size_id !== null && c.stock > 0)
                            .map(c => c.size_id)
                            .filter((value, index, self) => self.indexOf(value) === index); // Unique
                        return baseSizes.filter(s => allStockedSizeIds.includes(s.id));
                    }
                },

                get dynamicAvailableColors() {
                    if (!this.hasColorVariations) return [];
                    const baseColors = this.productAttributeLists.colors;

                    if (this.hasSizeVariations && this.selectedSizeId && this.selectedSizeId !== '') {
                        const sizeId = parseInt(this.selectedSizeId);
                        const validColorIds = this.stockCombinations
                            .filter(c => c.size_id === sizeId && c.color_id !== null && c.stock > 0)
                            .map(c => c.color_id);
                        return baseColors.filter(c => validColorIds.includes(c.id));
                    } else {
                        const allStockedColorIds = this.stockCombinations
                            .filter(c => c.color_id !== null && c.stock > 0)
                            .map(c => c.color_id)
                            .filter((value, index, self) => self.indexOf(value) === index); // Unique
                        return baseColors.filter(c => allStockedColorIds.includes(c.id));
                    }
                },

                get selectedCombination() {
                    const targetSizeId = this.hasSizeVariations ? (this.selectedSizeId === '' ? null : parseInt(this.selectedSizeId)) : null;
                    const targetColorId = this.hasColorVariations ? (this.selectedColorId === '' ? null : parseInt(this.selectedColorId)) : null;
                    return this.stockCombinations.find(c => c.size_id === targetSizeId && c.color_id === targetColorId) || null;
                },

                get formInstruction() {
                    if (this.clientError) return this.clientError;
                    if (this.hasSizeVariations && this.selectedSizeId === '' && (this.dynamicAvailableSizes.length > 0 || !this.selectedColorId)) return 'Pilih ukuran.';
                    if (this.hasColorVariations && this.selectedColorId === '' && (this.dynamicAvailableColors.length > 0 || !this.selectedSizeId)) return 'Pilih warna.';
                    if (this.isSelectionComplete && !this.isCombinationAvailable && this.selectedCombination) return 'Stok untuk kombinasi ini habis.';
                    if (this.isSelectionComplete && !this.isCombinationAvailable && !this.selectedCombination) return 'Kombinasi pilihan tidak tersedia.';
                    return 'Masukkan jumlah yang Anda inginkan.';
                },

                updateMaxStock() {
                    this.clientError = '';
                    let selectionActuallyComplete = true;
                    if (this.hasSizeVariations && (this.selectedSizeId === '' || this.selectedSizeId === null)) selectionActuallyComplete = false;
                    if (this.hasColorVariations && (this.selectedColorId === '' || this.selectedColorId === null)) selectionActuallyComplete = false;
                    this.isSelectionComplete = selectionActuallyComplete;

                    this.isCombinationAvailable = false;
                    this.maxStock = 0;

                    if (!this.isSelectionComplete && (this.hasSizeVariations || this.hasColorVariations)) {
                        // Error handled by formInstruction
                        if (this.quantity !== null) this.quantity = null; // Reset quantity if selection becomes incomplete
                    } else {
                        const combination = this.selectedCombination;
                        if (combination) {
                            this.maxStock = parseInt(combination.stock) || 0;
                            this.isCombinationAvailable = this.maxStock > 0;
                            if (!this.isCombinationAvailable) {
                                // clientError set by formInstruction
                                if (this.quantity !== null) this.quantity = null;
                            }
                        } else {
                            // Combination not found, clientError set by formInstruction
                           if (this.quantity !== null) this.quantity = null;
                        }
                    }
                    this.validateQuantity();
                },

                validateQuantity() {
                    if (!this.isSelectionComplete || !this.isCombinationAvailable || this.maxStock <= 0) {
                        if (this.clientError && (this.clientError.includes('Jumlah') || this.clientError.includes('minimal') || this.clientError.includes('melebihi') || this.clientError.includes('harus diisi'))) {
                             this.clientError = ''; // Clear only quantity errors if combo is issue
                        }
                        return;
                    }
                    if (this.clientError && (this.clientError.includes('Jumlah') || this.clientError.includes('minimal') || this.clientError.includes('melebihi') || this.clientError.includes('harus diisi'))) {
                        this.clientError = '';
                    }

                    if (this.quantity === null || this.quantity === undefined || this.quantity === '') {
                        this.clientError = 'Jumlah harus diisi.'; return;
                    }
                    if (isNaN(this.quantity)) {
                        this.clientError = 'Jumlah harus berupa angka.'; return;
                    }
                    let qty = parseInt(this.quantity);
                    if (qty < 1) {
                        this.clientError = 'Jumlah minimal 1.'; return;
                    }
                    if (this.maxStock > 0 && qty > this.maxStock) {
                        this.clientError = `Jumlah melebihi stok (${this.maxStock} pcs).`; return;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
