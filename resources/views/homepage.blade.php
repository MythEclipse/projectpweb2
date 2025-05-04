<x-app-layout>
    {{-- Gunakan ID yang konsisten untuk frame --}}
    <turbo-frame id="products_list_frame">
        <!-- Modal Notifikasi (Sukses/Error) -->
        @if (session('success') || session('error'))
            <div x-data="{
                showModal: true,
                closeModal() {
                    this.showModal = false;
                    // Optional: Clear flash session via AJAX if needed
                }
            }" x-init="$nextTick(() => showModal = true)" x-show="showModal"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black/70" style="display: none;">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 w-80 max-w-full shadow-xl dark:border dark:border-dark-border"
                    @click.away="closeModal">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-text-dark dark:text-text-light">
                            @if (session('success'))
                                <svg class="inline-block w-5 h-5 text-green-500 mr-1.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Sukses
                            @elseif (session('error'))
                                <svg class="inline-block w-5 h-5 text-red-500 mr-1.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Gagal
                            @endif
                        </h2>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-text-dark dark:text-text-light mb-6">
                        {{ session('success') ?? session('error') }}
                    </p>
                    <div class="flex justify-end">
                        <button @click="closeModal"
                            class="px-4 py-2 bg-pink-brand text-white rounded-lg hover:bg-pink-brand-dark transition-colors focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Kontainer Utama Produk --}}
        {{-- Tambahkan x-data="productList()" di sini jika belum ada di layout utama --}}
        {{-- Jika productList() sudah ada di layout, pastikan Alpine terinisialisasi sebelum skrip skeleton --}}
        <div class="p-4 sm:p-6 bg-white dark:bg-dark-card rounded-2xl shadow-sm" x-data="productList()">

            {{-- >>> START: Skeleton Loader Area <<< --}}
            <div id="product-list-skeleton" class="hidden">
                {{-- Skeleton Search Bar --}}
                <div class="relative mb-6 animate-pulse">
                    <div class="w-full h-[46px] bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                </div>
                {{-- Skeleton Grid --}}
                <div
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6 animate-pulse">
                    @php
                        // Sesuaikan jumlah skeleton item dengan $products->perPage() atau jumlah default
                        $skeletonCount = $products->perPage() ?: 12;
                    @endphp
                    @for ($i = 0; $i < $skeletonCount; $i++)
                        <div
                            class="bg-gray-200 dark:bg-dark-subcard/50 rounded-xl shadow-md overflow-hidden flex flex-col h-[270px] sm:h-[290px]">
                            {{-- Sesuaikan tinggi perkiraan --}}
                            <div class="aspect-square w-full bg-gray-300 dark:bg-gray-600"></div>
                            <div class="p-3 sm:p-4 flex flex-col flex-grow space-y-2">
                                <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
                                <div class="h-5 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
                                <div class="h-3 bg-gray-300 dark:bg-gray-600 rounded w-1/4 mb-2"></div>
                                <div class="mt-auto h-8 bg-gray-300 dark:bg-gray-600 rounded w-full"></div>
                            </div>
                        </div>
                    @endfor
                </div>
                {{-- Skeleton Pagination --}}
                @if ($products->hasPages())
                    <div class="mt-8 animate-pulse flex justify-between items-center">
                        {{-- Left Group: Pagination Buttons Placeholder --}}
                        <div class="flex items-center space-x-1 sm:space-x-1.5">
                            {{-- Prev Button Placeholder --}}
                            <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                            {{-- Page Number Placeholders (adjust count as needed) --}}
                            <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                            {{-- Active Page Placeholder --}}
                            <div class="h-8 w-8 sm:h-9 sm:w-9 bg-pink-300 dark:bg-pink-800/50 rounded-md"></div>
                            <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                            {{-- Ellipsis Placeholder (Optional) --}}
                            {{-- <div class="h-8 w-8 sm:h-9 sm:w-9 flex items-center justify-center text-gray-400">...</div> --}}
                            {{-- Next Button Placeholder --}}
                            <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                        </div>

                        {{-- Right Group: Results Text Placeholder --}}
                        <div class="h-4 bg-gray-300 dark:bg-dark-border rounded w-36 sm:w-48"></div>
                    </div>
                @endif
            </div>
            {{-- >>> END: Skeleton Loader Area <<< --}}

            {{-- Kontainer untuk Konten Asli (Akan disembunyikan saat loading) --}}
            <div id="product-list-content">
                <!-- Pencarian Server-Side -->
                <form method="GET" action="{{ url()->current() }}" class="relative mb-6"
                    data-turbo-frame="products_list_frame" {{-- Optional: Make search use Turbo too --}}>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-gray-300 dark:border-dark-border rounded-lg py-2.5 pl-4 pr-12 focus:ring-2 focus:ring-pink-brand/50 focus:border-pink-brand dark:bg-dark-subcard dark:text-text-light placeholder-gray-400 dark:placeholder-gray-500"
                        placeholder="Cari produk...">
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-brand dark:hover:text-pink-brand-dark transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
                        </svg>
                    </button>
                </form>
                <!-- /Pencarian Server-Side -->

                <!-- Daftar Produk -->
                <div id="product-grid"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                    @forelse ($products as $product)
                        <div
                            class="bg-white dark:bg-dark-subcard rounded-xl shadow-md overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            {{-- Gambar Produk (Klik untuk Detail) --}}
                            <div class="aspect-square w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden relative group cursor-pointer"
                                @click="openModal({{ json_encode($product->load('stockCombinations.size', 'stockCombinations.color')) }})">
                                {{-- Gunakan accessor $product->image_url dari model --}}
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="object-cover h-full w-full group-hover:scale-105 transition-transform duration-300 ease-in-out"
                                        loading="lazy" {{-- Add lazy loading --}}
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';">
                                @else
                                    {{-- Placeholder Image SVG --}}
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        {{-- SVG Path --}}
                                    </svg>
                                @endif
                            </div>

                            {{-- Konten Detail Produk --}}
                            <div class="p-3 sm:p-4 flex flex-col flex-grow">
                                <h3 class="text-sm sm:text-base font-semibold text-text-dark dark:text-text-light mb-1 truncate"
                                    title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                                <p
                                    class="text-pink-brand dark:text-pink-brand-dark font-bold text-base sm:text-lg mb-2">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                {{-- Simple stock indicator (optional) --}}
                                @php
                                    $totalStock = $product->stockCombinations->sum('stock');
                                @endphp
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                    Stok:
                                    @if ($totalStock > 0)
                                        <span class="text-green-600 font-medium">Tersedia</span>
                                    @else
                                        <span class="text-red-500 font-medium">Habis</span>
                                    @endif
                                </p>

                                {{-- >>> START: TOMBOL WISHLIST <<< --}}
                                @auth
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
                                        class="mb-2"> {{-- Beri jarak bawah --}}
                                        @csrf
                                        @php
                                            // Cek apakah produk sudah ada di wishlist user
                                            // Pastikan Auth::user() mengembalikan instance User yang punya relasi wishlistProducts
                                            $isInWishlist = Auth::user()->hasInWishlist($product);
                                        @endphp

                                        <button type="submit"
                                            class="w-full flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard
                                    {{ $isInWishlist
                                        ? 'bg-red-100 border-red-300 text-red-700 hover:bg-red-200 dark:bg-red-900/50 dark:border-red-700/50 dark:text-red-300 dark:hover:bg-red-800/60 focus:ring-red-400'
                                        : 'bg-pink-100 border-pink-300 text-pink-700 hover:bg-pink-200 dark:bg-pink-900/50 dark:border-pink-700/50 dark:text-pink-300 dark:hover:bg-pink-800/60 focus:ring-pink-400' }}"
                                            title="{{ $isInWishlist ? __('Remove from Wishlist') : __('Add to Wishlist') }}">

                                            @if ($isInWishlist)
                                                {{-- Icon: Solid Heart --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4 mr-1.5 flex-shrink-0">
                                                    <path
                                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                                </svg>
                                                <span>{{ __('Remove') }}</span> {{-- Teks lebih pendek --}}
                                            @else
                                                {{-- Icon: Outline Heart --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 mr-1.5 flex-shrink-0">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                </svg>
                                                <span>{{ __('Wishlist') }}</span> {{-- Teks lebih pendek --}}
                                            @endif
                                        </button>
                                    </form>
                                @endauth
                                {{-- >>> END: TOMBOL WISHLIST <<< --}}

                                {{-- Tombol Aksi Beli --}}
                                <div class="mt-auto"> {{-- mt-auto memastikan tombol ini tetap di bawah --}}
                                    <button
                                        @click="openBuyModal({{ json_encode($product->load('stockCombinations.size', 'stockCombinations.color')) }})"
                                        {{-- Disable buy button directly if total stock is 0 --}} :disabled="{{ $totalStock <= 0 }}"
                                        class="w-full bg-pink-brand hover:bg-pink-brand-dark text-white text-xs font-medium rounded-lg px-3 py-1.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard disabled:opacity-50 disabled:cursor-not-allowed">
                                        Beli
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Tampilan jika tidak ada produk --}}
                        {{-- ... kode fallback ... --}}
                    @endforelse
                </div>
                <!-- /Daftar Produk -->

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="mt-8" id="product-pagination"> {{-- ID untuk pagination --}}
                        {{ $products->links() }}
                    </div>
                @endif
                <!-- /Pagination -->
            </div> {{-- End #product-list-content --}}


            {{-- MODAL BELI DAN DETAIL TETAP SAMA DI BAWAH INI --}}
            <!-- =================================== -->
            <!--          MODAL BELI SECTION         -->
            <!-- =================================== -->
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
                        {{-- Action uses slug, needs controller setup for route model binding --}}
                        <form method="POST"
                            :action="selectedProduct && selectedProduct.slug ?
                                `{{ url('/products') }}/${selectedProduct.slug}/purchase` : '#'"
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
                                    @change="updateAvailableColors(); updateMaxStock();" {{-- Added updateMaxStock here too --}}
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required :disabled="availableSizes.length === 0">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <template x-for="size in availableSizes" :key="size.id">
                                        <option :value="size.id" x-text="size.name"></option>
                                    </template>
                                </select>
                                @error('size_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                                <template
                                    x-if="availableSizes.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                    <span class="text-orange-500 text-xs mt-1">Tidak ada ukuran tersedia untuk produk
                                        ini.</span>
                                </template>
                            </div>

                            {{-- Pilih Warna --}}
                            <div class="mb-4">
                                <label for="buy-color"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih
                                    Warna</label>
                                <select id="buy-color" name="color_id" x-model="selectedColorId"
                                    @change="updateAvailableSizes(); updateMaxStock();" {{-- Added updateMaxStock here too --}}
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required :disabled="availableColors.length === 0 || !selectedSizeId">
                                    <option value="">-- Pilih Warna --</option>
                                    <template x-for="color in availableColors" :key="color.id">
                                        <option :value="color.id" x-text="color.name"></option>
                                    </template>
                                </select>
                                @error('color_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                                <template
                                    x-if="selectedSizeId && availableColors.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                    <span class="text-orange-500 text-xs mt-1">Tidak ada warna tersedia untuk ukuran
                                        ini.</span>
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
                                @error('quantity')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tombol Aksi Modal --}}
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="closeBuyModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-dark-border rounded-lg hover:bg-gray-200 dark:hover:bg-dark-card transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-card">
                                    Batal
                                </button>
                                <button type="submit"
                                    :disabled="!selectedSizeId || !selectedColorId || !quantity || quantity < 1 || maxStock <= 0 ||
                                        quantity > maxStock"
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
            <!-- /Modal Beli -->


            <!-- =================================== -->
            <!--         MODAL DETAIL SECTION        -->
            <!-- =================================== -->
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-[70] p-4"
                {{-- Higher z-index than buy modal --}} style="display: none;">
                {{-- Removed @click.away here - clicking outside the content box below will close it --}}

                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white dark:bg-dark-card rounded-2xl p-5 sm:p-6 w-full max-w-lg shadow-xl dark:border dark:border-dark-border relative"
                    {{-- Added relative for potential absolute positioning inside if needed --}} @click.away="closeModal"> {{-- <<< CHANGED: Moved @click.away here --}}

                    <template x-if="selectedProduct">
                        <div>
                            {{-- Close button moved to top right corner for better standard UX --}}
                            <button @click="closeModal"
                                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 p-1 bg-white/50 dark:bg-dark-card/50 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            {{-- Title now below the close button area --}}
                            <h3 class="text-xl font-semibold text-text-dark dark:text-text-light mb-4 pr-8"
                                {{-- Added pr-8 to avoid overlap with close button --}} x-text="selectedProduct.name">
                            </h3>

                            <div class="md:flex md:space-x-6">
                                {{-- Kolom Gambar --}}
                                <div class="md:w-1/2 mb-4 md:mb-0 flex-shrink-0">
                                    <div
                                        class="aspect-square bg-gray-100 dark:bg-dark-border rounded-xl flex items-center justify-center text-gray-400 overflow-hidden">
                                        <img :src="getProductImageUrl(selectedProduct)"
                                            :alt="selectedProduct ? selectedProduct.name : 'Product Image'"
                                            class="w-full h-full object-cover"
                                            onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';" />
                                    </div>
                                </div>

                                {{-- Kolom Detail Teks --}}
                                <div class="md:w-1/2 space-y-3 text-sm">
                                    <p class="text-2xl font-bold text-pink-brand dark:text-pink-brand-dark">
                                        Rp <span x-text="Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                                    </p>

                                    {{-- Stock Details in Detail Modal --}}
                                    <div>
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Ukuran, Warna &
                                            Stok:</p>
                                        <div
                                            class="text-xs space-y-1.5 max-h-32 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
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
                                                <span class="text-gray-400 italic">Informasi stok tidak
                                                    tersedia.</span>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div>
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Deskripsi:</p>
                                        <div class="text-gray-600 dark:text-gray-400 leading-relaxed prose prose-sm dark:prose-invert max-w-none max-h-40 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent"
                                            x-html="selectedProduct.description || 'Tidak ada deskripsi.'">
                                        </div>
                                        {{-- Use x-html if description might contain basic HTML. Added max-h and overflow. --}}
                                    </div>
                                </div>
                            </div>
                            {{-- Tombol Tutup tidak diperlukan karena @click.away dan tombol close di sudut --}}
                        </div>
                    </template>
                    {{-- Fallback jika selectedProduct null --}}
                    <template x-if="!selectedProduct && modalOpen"> {{-- Show only if modal intended to be open --}}
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Memuat detail produk...</p>
                            {{-- Optional: Add a spinner here --}}
                            <button @click="closeModal"
                                class="mt-4 px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-dark-border rounded-lg hover:bg-gray-200 dark:hover:bg-dark-card transition-colors">Tutup</button>
                        </div>
                    </template>
                </div>
            </div>
            <!-- /Modal Detail -->

        </div> {{-- End Kontainer Utama Produk --}}
    </turbo-frame>


</x-app-layout>
