<x-app-layout>
    {{-- Gunakan ID yang konsisten untuk frame --}}
    <turbo-frame id="products_list_frame">
        <!-- Modal Notifikasi (Sukses/Error) -->
        @if (session('success') || session('error'))
            <div x-data="{
                showModal: true,
                closeModal() {
                    this.showModal = false;
                    // Optional: Clear flash session via AJAX if needed,
                    // otherwise let the next page load handle it.
                    // fetch('{{ route('session.clear.flash') }}', { // Ensure this route exists if used
                    //     method: 'POST',
                    //     headers: {
                    //         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    //         'Accept': 'application/json'
                    //     }
                    // });
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
                                <svg class="inline-block w-5 h-5 text-green-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Sukses
                            @elseif (session('error'))
                                <svg class="inline-block w-5 h-5 text-red-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
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
        <div class="p-4 sm:p-6 bg-white dark:bg-dark-card rounded-2xl shadow-sm" x-data="productList()">
            <!-- Pencarian Server-Side -->
            <form method="GET" action="{{ url()->current() }}" class="relative mb-6">
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
                        {{-- Gambar Produk --}}
                        <div class="aspect-square w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden relative">
                           {{-- Gunakan accessor $product->image_url dari model --}}
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="object-cover h-full w-full group-hover:scale-105 transition-transform duration-300 ease-in-out"
                                     loading="lazy" {{-- Add lazy loading --}}
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';">
                            @else
                                {{-- Placeholder Image SVG --}}
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @endif
                        </div>

                        {{-- Konten Detail Produk --}}
                        <div class="p-3 sm:p-4 flex flex-col flex-grow">
                            <h3 class="text-sm sm:text-base font-semibold text-text-dark dark:text-text-light mb-1 truncate"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            <p class="text-pink-brand dark:text-pink-brand-dark font-bold text-base sm:text-lg mb-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                             {{-- Simple stock indicator (optional) --}}
                             @php
                                 $totalStock = $product->stockCombinations->sum('stock');
                             @endphp
                             <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                 Stok:
                                 @if($totalStock > 0)
                                     <span class="text-green-600 font-medium">Tersedia</span>
                                 @else
                                     <span class="text-red-500 font-medium">Habis</span>
                                 @endif
                             </p>
                            {{-- <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3 flex-grow">
                                {{ $product->description ?? 'Tidak ada deskripsi.' }}
                            </p> --}}

                            {{-- Tombol Aksi --}}
                            <div class="mt-auto flex space-x-2">
                                {{-- Encode product data for Alpine, ensure stockCombinations are loaded --}}
                                <button @click="openModal({{ json_encode($product->load('stockCombinations.size', 'stockCombinations.color')) }})"
                                    class="flex-1 bg-gray-100 dark:bg-dark-border hover:bg-gray-200 dark:hover:bg-dark-card text-text-dark dark:text-text-light px-3 py-1.5 rounded-lg text-xs font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard">
                                    Detail
                                </button>
                                <button @click="openBuyModal({{ json_encode($product->load('stockCombinations.size', 'stockCombinations.color')) }})"
                                    {{-- Disable buy button directly if total stock is 0 --}}
                                    :disabled="{{ $totalStock <= 0 }}"
                                    class="bg-pink-brand hover:bg-pink-brand-dark text-white text-xs font-medium rounded-lg px-3 py-1.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard disabled:opacity-50 disabled:cursor-not-allowed">
                                    Beli
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada produk --}}
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">Produk tidak ditemukan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if (request('search'))
                                Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}".
                            @else
                                Belum ada produk yang tersedia.
                            @endif
                        </p>
                        @if (request('search'))
                            <div class="mt-6">
                                <a href="{{ url()->current() }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-main">
                                    Hapus Pencarian
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
            <!-- /Daftar Produk -->

            <!-- Pagination -->
            @if ($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @endif
            <!-- /Pagination -->

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
                            :action="selectedProduct && selectedProduct.slug ? `{{ url('/products') }}/${selectedProduct.slug}/purchase` : '#'"
                            x-ref="buyForm">
                            @csrf

                            {{-- Ringkasan Produk --}}
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-dark-subcard/50 rounded-lg border dark:border-dark-border">
                                <p class="font-medium text-text-dark dark:text-text-light mb-2 text-sm">Detail Produk:</p>
                                <div class="flex items-center space-x-3">
                                    <img :src="getProductImageUrl(selectedProduct)"
                                        :alt="selectedProduct.name"
                                        class="w-12 h-12 rounded-md object-cover bg-gray-200 dark:bg-dark-border flex-shrink-0"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';"/>
                                    <div>
                                        <p class="text-sm font-semibold text-text-dark dark:text-text-light" x-text="selectedProduct.name"></p>
                                        <p class="text-sm text-pink-brand dark:text-pink-brand-dark font-bold">Rp <span x-text="Number(selectedProduct.price).toLocaleString('id-ID')"></span></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Detail Stok Tersedia --}}
                            <details class="mb-4 group">
                                <summary class="cursor-pointer text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-text-dark dark:hover:text-text-light group-open:mb-2 list-none flex items-center justify-between">
                                    <span>Lihat Stok Tersedia</span>
                                    <svg class="w-4 h-4 transform transition-transform group-open:rotate-180 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </summary>
                                <div class="text-xs space-y-1.5 max-h-28 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30 mt-1 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                                    <template x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                                        <template x-for="item in selectedProduct.stock_combinations" :key="item.id ?? `${item.size_id}-${item.color_id}`">
                                            <div class="flex items-center gap-2" :class="{ 'opacity-50': item.stock <= 0 }">
                                                <span class="px-1.5 py-0.5 bg-gray-200 dark:bg-dark-border text-[10px] rounded font-medium whitespace-nowrap" x-text="item.size?.name ? item.size.name.toUpperCase() : 'N/A'"></span>
                                                <template x-if="item.color?.code">
                                                    <span class="w-3 h-3 rounded-full border border-gray-300 dark:border-dark-border inline-block flex-shrink-0" :style="'background-color: ' + item.color.code" :title="item.color?.name ?? 'Warna tidak diketahui'"></span>
                                                </template>
                                                <span class="text-[10px] text-gray-600 dark:text-text-light/80 truncate" x-text="item.color?.name ?? 'Tanpa Warna'"></span>
                                                <span class="text-[10px] text-gray-500 dark:text-text-light/70 ml-auto font-medium whitespace-nowrap">
                                                    <span x-text="item.stock"></span> pcs
                                                    <template x-if="item.stock <= 0"><span class="text-red-500 ml-1">(Habis)</span></template>
                                                </span>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="!selectedProduct.stock_combinations || selectedProduct.stock_combinations.length === 0">
                                        <span class="text-gray-400 italic">Informasi stok tidak tersedia.</span>
                                    </template>
                                </div>
                            </details>

                            {{-- Pilih Ukuran --}}
                            <div class="mb-4">
                                <label for="buy-size" class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih Ukuran</label>
                                <select id="buy-size" name="size_id" x-model="selectedSizeId"
                                    @change="updateAvailableColors();"
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required
                                    :disabled="availableSizes.length === 0"> {{-- Disable if no sizes available at all --}}
                                    <option value="">-- Pilih Ukuran --</option>
                                    <template x-for="size in availableSizes" :key="size.id">
                                        <option :value="size.id" x-text="size.name"></option>
                                    </template>
                                </select>
                                @error('size_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <template x-if="availableSizes.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                     <span class="text-orange-500 text-xs mt-1">Tidak ada ukuran tersedia untuk produk ini.</span>
                                </template>
                            </div>

                            {{-- Pilih Warna --}}
                            <div class="mb-4">
                                <label for="buy-color" class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih Warna</label>
                                <select id="buy-color" name="color_id" x-model="selectedColorId"
                                    @change="updateAvailableSizes();"
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                    required
                                    :disabled="availableColors.length === 0 || !selectedSizeId"> {{-- Disable if no colors or size not selected --}}
                                    <option value="">-- Pilih Warna --</option>
                                    <template x-for="color in availableColors" :key="color.id">
                                        <option :value="color.id" x-text="color.name"></option>
                                    </template>
                                </select>
                                @error('color_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <template x-if="selectedSizeId && availableColors.length === 0 && selectedProduct?.stock_combinations?.length > 0">
                                     <span class="text-orange-500 text-xs mt-1">Tidak ada warna tersedia untuk ukuran ini.</span>
                                </template>
                            </div>

                            {{-- Jumlah --}}
                            <div class="mb-6">
                                <label for="buy-quantity" class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="buy-quantity" type="number" name="quantity"
                                        class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-dark-border"
                                        min="1" :max="maxStock > 0 ? maxStock : 1" {{-- Set max to 1 if stock is 0, just as a fallback --}}
                                        x-model.number="quantity"
                                        @input="validateQuantity()"
                                        required
                                        {{-- Disable if size/color not chosen OR if maxStock is 0 or less --}}
                                        :disabled="!selectedSizeId || !selectedColorId || maxStock <= 0"
                                    />
                                    {{-- Show Max Stock only if selections made and stock > 0 --}}
                                    <span x-show="selectedSizeId && selectedColorId && maxStock > 0"
                                          class="ml-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        (Max: <span x-text="maxStock"></span>)
                                    </span>
                                    {{-- Show Out of Stock message if selections made and stock <= 0 --}}
                                    <span x-show="selectedSizeId && selectedColorId && maxStock <= 0"
                                          class="ml-2 text-xs text-red-500 whitespace-nowrap font-medium">
                                        (Stok Habis)
                                    </span>
                                </div>
                                {{-- Helper text if selections not made --}}
                                <p x-show="!selectedSizeId || !selectedColorId" class="mt-1 text-xs text-orange-500">
                                    Pilih ukuran dan warna terlebih dahulu.
                                </p>
                                @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tombol Aksi Modal --}}
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="closeBuyModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-dark-border rounded-lg hover:bg-gray-200 dark:hover:bg-dark-card transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-card">
                                    Batal
                                </button>
                                <button type="submit"
                                    {{-- Comprehensive disable condition --}}
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
            <!-- /Modal Beli -->


            <!-- =================================== -->
            <!--         MODAL DETAIL SECTION        -->
            <!-- =================================== -->
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-[70] p-4" {{-- Higher z-index than buy modal --}}
                @click.away="closeModal" style="display: none;">

                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white dark:bg-dark-card rounded-2xl p-5 sm:p-6 w-full max-w-lg shadow-xl dark:border dark:border-dark-border"
                    @click.stop> {{-- Prevent closing when clicking inside --}}

                    <template x-if="selectedProduct">
                        <div>
                            <div class="flex justify-between items-start mb-4"> {{-- items-start for long titles --}}
                                <h3 class="text-xl font-semibold text-text-dark dark:text-text-light pr-4" x-text="selectedProduct.name"></h3>
                                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="md:flex md:space-x-6">
                                {{-- Kolom Gambar --}}
                                <div class="md:w-1/2 mb-4 md:mb-0 flex-shrink-0">
                                    <div class="aspect-square bg-gray-100 dark:bg-dark-border rounded-xl flex items-center justify-center text-gray-400 overflow-hidden">
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
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Ukuran, Warna & Stok:</p>
                                        <div class="text-xs space-y-1.5 max-h-32 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent">
                                            <template x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                                                 <template x-for="item in selectedProduct.stock_combinations" :key="item.id ?? `${item.size_id}-${item.color_id}`">
                                                    <div class="flex items-center gap-2" :class="{ 'opacity-50': item.stock <= 0 }">
                                                        <span class="px-1.5 py-0.5 bg-gray-200 dark:bg-dark-border text-[10px] rounded font-medium whitespace-nowrap" x-text="item.size?.name ? item.size.name.toUpperCase() : 'N/A'"></span>
                                                        <template x-if="item.color?.code">
                                                            <span class="w-3 h-3 rounded-full border border-gray-300 dark:border-dark-border inline-block flex-shrink-0" :style="'background-color: ' + item.color.code" :title="item.color?.name ?? 'Warna tidak diketahui'"></span>
                                                        </template>
                                                        <span class="text-[10px] text-gray-600 dark:text-text-light/80 truncate" x-text="item.color?.name ?? 'Tanpa Warna'"></span>
                                                        <span class="text-[10px] text-gray-500 dark:text-text-light/70 ml-auto font-medium whitespace-nowrap">
                                                            <span x-text="item.stock"></span> pcs
                                                            <template x-if="item.stock <= 0"><span class="text-red-500 ml-1">(Habis)</span></template>
                                                        </span>
                                                    </div>
                                                </template>
                                            </template>
                                            <template x-if="!selectedProduct.stock_combinations || selectedProduct.stock_combinations.length === 0">
                                                <span class="text-gray-400 italic">Informasi stok tidak tersedia.</span>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div>
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Deskripsi:</p>
                                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed prose prose-sm dark:prose-invert max-w-none" x-html="selectedProduct.description || 'Tidak ada deskripsi.'"></p> {{-- Use x-html if description might contain basic HTML --}}
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Tutup --}}
                            <div class="mt-6 text-right">
                                <button @click="closeModal"
                                    class="px-5 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </template>
                    {{-- Fallback jika selectedProduct null --}}
                    <template x-if="!selectedProduct">
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Gagal memuat detail produk.</p>
                    </template>
                </div>
            </div>
            <!-- /Modal Detail -->

        </div> {{-- End Kontainer Utama Produk --}}
    </turbo-frame>

    @push('scripts')
        <script>
            function productList() {
                return {
                    // --- Modal Visibility ---
                    showBuyModal: false,
                    modalOpen: false, // For the detail modal

                    // --- Loading State ---
                    loading: false, // Primarily for loading options in the buy modal

                    // --- Selected Product Data ---
                    selectedProduct: null, // Holds the full product object when a modal is opened

                    // --- Buy Modal Form State ---
                    selectedSizeId: '',
                    selectedColorId: '',
                    quantity: 1,
                    maxStock: 0,
                    availableSizes: [],
                    availableColors: [],
                    storageBaseUrl: '{{ rtrim(asset('storage'), '/') }}', // Base URL for local images

                    // --- Helper ---
                    isExternalImage(url) {
                        return url && (url.startsWith('http://') || url.startsWith('https://'));
                    },

                    getProductImageUrl(product, placeholder = 'https://via.placeholder.com/150/EEEEEE/AAAAAA?text=No+Image') {
                        if (!product || !product.image_url) { // Use image_url from accessor
                             if (!product || !product.image) { // Fallback check for raw image path
                                 return placeholder;
                             }
                             // If image_url is missing but image path exists, construct it
                             const imagePath = product.image;
                              if (this.isExternalImage(imagePath)) {
                                 return imagePath; // Should have been image_url, but handle just in case
                             } else {
                                 return this.storageBaseUrl + '/' + imagePath.replace(/^\//, '');
                             }
                        }
                        return product.image_url; // Primary: use the accessor value
                    },

                    // --- Methods for Modals ---
                    openModal(product) { // Detail Modal
                        console.log("Opening Detail Modal for:", product);
                        this.selectedProduct = product;
                        this.modalOpen = true;
                    },

                    closeModal() { // Detail Modal
                        this.modalOpen = false;
                        setTimeout(() => { this.selectedProduct = null; }, 300); // Delay clear for transition
                    },

                    openBuyModal(product) { // Buy Modal
                        if (!product || !product.stockCombinations || product.stockCombinations.reduce((sum, c) => sum + c.stock, 0) <= 0) {
                            console.warn("Buy modal blocked: Product or stockCombinations missing, or total stock is zero.");
                            // Optionally show a notification to the user here
                            return; // Prevent opening if fundamentally out of stock
                        }
                        console.log("Opening Buy Modal for:", product);
                        this.selectedProduct = product;
                        this.loading = true;
                        this.resetBuyFormState();

                        this.$nextTick(() => { // Ensure DOM is ready if needed, though likely not critical here
                            this.populateInitialOptions();
                            this.loading = false;
                            this.showBuyModal = true;
                        });
                    },

                    closeBuyModal() { // Buy Modal
                        this.showBuyModal = false;
                        setTimeout(() => {
                            this.selectedProduct = null;
                            this.resetBuyFormState();
                        }, 300);
                    },

                    // --- Methods for Buy Modal Logic ---
                    resetBuyFormState() {
                        this.selectedSizeId = '';
                        this.selectedColorId = '';
                        this.quantity = 1;
                        this.maxStock = 0;
                        this.availableSizes = [];
                        this.availableColors = [];
                    },

                    populateInitialOptions() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) {
                            console.error("Stock combinations missing for populating options.");
                            this.availableSizes = [];
                            this.availableColors = [];
                            return;
                        }

                        const combinations = this.selectedProduct.stock_combinations.filter(c => c.stock > 0); // Only consider combinations with stock > 0 for initial options

                        // Use Maps to get unique sizes/colors *that have stock*
                         const uniqueSizesWithStock = [...new Map(combinations
                                .filter(c => c.size) // Ensure size object exists
                                .map(item => [item.size.id, { id: item.size.id, name: item.size.name }])) // Use direct size name
                            .values()
                        ];

                        // Populate all unique colors initially, filtering will happen on size change
                         const allUniqueColors = [...new Map(this.selectedProduct.stock_combinations // Use original list for all colors
                                .filter(c => c.color) // Ensure color object exists
                                .map(item => [item.color.id, { id: item.color.id, name: item.color.name }])) // Use direct color name
                            .values()
                        ];

                        this.availableSizes = uniqueSizesWithStock;
                        this.availableColors = allUniqueColors; // Start with all colors

                         // If only one size is available initially, auto-select it
                        if (this.availableSizes.length === 1) {
                            this.selectedSizeId = this.availableSizes[0].id;
                            this.updateAvailableColors(); // Trigger color filtering and stock update
                        } else {
                             this.selectedSizeId = ''; // Ensure reset if multiple options
                             this.selectedColorId = '';
                             this.maxStock = 0;
                             this.quantity = 1;
                        }
                    },

                    updateAvailableColors() {
                        console.log("Updating colors for size:", this.selectedSizeId);
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                        const combinations = this.selectedProduct.stock_combinations;
                        const sizeId = this.selectedSizeId ? parseInt(this.selectedSizeId) : null;

                        if (!sizeId) {
                             // If no size selected, show all unique colors again (from original list)
                             this.availableColors = [...new Map(combinations
                                .filter(c => c.color)
                                .map(item => [item.color.id, { id: item.color.id, name: item.color.name }]))
                                .values()];
                            this.selectedColorId = '';
                            this.maxStock = 0;
                            this.quantity = 1;
                            return;
                        }

                        // Find colors available *for the selected size* AND *have stock > 0*
                        const colorsForSizeWithStock = combinations
                            .filter(c => c.size_id === sizeId && c.color && c.stock > 0)
                            .map(c => ({ id: c.color.id, name: c.color.name }));

                        this.availableColors = [...new Map(colorsForSizeWithStock.map(item => [item.id, item])).values()];

                         // Auto-select color if only one is available for the chosen size
                        if (this.availableColors.length === 1) {
                             this.selectedColorId = this.availableColors[0].id;
                        } else {
                             // If the currently selected color is no longer valid, reset it
                            if (this.selectedColorId && !this.availableColors.some(c => c.id === parseInt(this.selectedColorId))) {
                                 this.selectedColorId = '';
                             }
                        }

                        // Always update max stock after potential color changes
                        this.updateMaxStock();
                    },

                    updateAvailableSizes() { // Optional: Filter sizes based on color (less common flow)
                        console.log("Updating sizes for color:", this.selectedColorId);
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                        const combinations = this.selectedProduct.stock_combinations;
                        const colorId = this.selectedColorId ? parseInt(this.selectedColorId) : null;

                         if (!colorId) {
                             // If no color selected, show all unique sizes *with stock*
                             this.availableSizes = [...new Map(combinations
                                .filter(c => c.size && c.stock > 0)
                                .map(item => [item.size.id, { id: item.size.id, name: item.size.name }]))
                                .values()];
                             this.selectedSizeId = '';
                             this.maxStock = 0;
                             this.quantity = 1;
                             return;
                         }

                        // Find sizes available *for the selected color* AND *have stock > 0*
                         const sizesForColorWithStock = combinations
                             .filter(c => c.color_id === colorId && c.size && c.stock > 0)
                             .map(c => ({ id: c.size.id, name: c.size.name }));

                         this.availableSizes = [...new Map(sizesForColorWithStock.map(item => [item.id, item])).values()];

                         // Auto-select size if only one is available for the chosen color
                         if (this.availableSizes.length === 1) {
                             this.selectedSizeId = this.availableSizes[0].id;
                         } else {
                             // If the currently selected size is no longer valid, reset it
                             if (this.selectedSizeId && !this.availableSizes.some(s => s.id === parseInt(this.selectedSizeId))) {
                                 this.selectedSizeId = '';
                             }
                         }

                        // Always update max stock after potential size changes
                        this.updateMaxStock();
                    },

                    updateMaxStock() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations || !this.selectedSizeId || !this.selectedColorId) {
                            this.maxStock = 0;
                        } else {
                            const sizeId = parseInt(this.selectedSizeId);
                            const colorId = parseInt(this.selectedColorId);
                            const combination = this.selectedProduct.stock_combinations.find(
                                c => c.size_id === sizeId && c.color_id === colorId
                            );
                            // Set maxStock to 0 if combo not found or stock is explicitly 0 or less
                            this.maxStock = (combination && combination.stock > 0) ? combination.stock : 0;
                        }
                        console.log(`Max stock updated to: ${this.maxStock} for Size ${this.selectedSizeId}, Color ${this.selectedColorId}`);
                        // Re-validate quantity after stock update
                        this.validateQuantity();
                    },

                    validateQuantity() {
                        // Need $nextTick to ensure maxStock value is updated in the DOM/Alpine state
                        // before comparing quantity against it, especially after async ops or complex state changes.
                        this.$nextTick(() => {
                             let currentQuantity = parseInt(this.quantity);
                             if (isNaN(currentQuantity) || currentQuantity < 1) {
                                 currentQuantity = 1;
                             }

                            // Only cap quantity if maxStock is determined and positive
                            if (this.maxStock > 0 && currentQuantity > this.maxStock) {
                                currentQuantity = this.maxStock;
                            }

                             // If stock is 0, input is disabled, but ensure quantity model is visually 1
                             if (this.maxStock <= 0) {
                                 currentQuantity = 1;
                             }

                            // Update the model only if the calculated value differs
                            if (this.quantity !== currentQuantity) {
                                this.quantity = currentQuantity;
                            }
                        });
                    },
                };
            }
        </script>

        {{-- Optional: Add scrollbar styling if using tailwindcss-scrollbar --}}
        <style>
            /* Optional: Slim scrollbar for stock details */
            .scrollbar-thin { scrollbar-width: thin; }
            .scrollbar-thumb-gray-300::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 10px;}
            .dark .scrollbar-thumb-gray-600::-webkit-scrollbar-thumb { background-color: #4b5563; border-radius: 10px;}
            .scrollbar-track-transparent::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar { width: 6px; height: 6px; }
        </style>
    @endpush

</x-app-layout>
