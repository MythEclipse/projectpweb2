<x-app-layout>
    {{-- Gunakan ID yang konsisten untuk frame --}}
    <turbo-frame id="products_list_frame">
        <!-- Modal Notifikasi (Sukses/Error) -->
        @if (session('success') || session('error'))
            <div x-data="{
                showModal: true,
                closeModal() {
                    this.showModal = false;
                    fetch('{{ route('session.clear.flash') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
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
                                <i class="fas fa-check-circle text-green-500 mr-2"></i> Sukses
                            @elseif (session('error'))
                                <i class="fas fa-times-circle text-red-500 mr-2"></i> Gagal
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
                <input type="text" name="search" value="{{ request('search') }}" {{-- Tampilkan query search yg aktif --}}
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
                    {{-- Pastikan $product->slug ada dari controller --}}
                    <div
                        class="bg-white dark:bg-dark-subcard rounded-xl shadow-md overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        {{-- Gambar Produk --}}
                        <div
                            class="h-40 w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden">
                            @if ($product->image)
                                <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                    class="object-cover h-full w-full group-hover:scale-105 transition-transform duration-300 ease-in-out">
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
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-sm sm:text-md font-semibold text-text-dark dark:text-text-light mb-1 truncate"
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            <p class="text-pink-brand dark:text-pink-brand-dark font-bold text-md mb-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mb-3 flex-grow">
                                {{ $product->description ?? 'Tidak ada deskripsi.' }}
                            </p>

                            {{-- Tombol Aksi --}}
                            <div class="mt-auto flex space-x-2">
                                <button @click="openModal({{ json_encode($product) }})"
                                    class="flex-1 bg-gray-100 dark:bg-dark-border hover:bg-gray-200 dark:hover:bg-dark-card text-text-dark dark:text-text-light px-3 py-1.5 rounded-lg text-xs font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard">
                                    Detail
                                </button>
                                <button @click="openBuyModal({{ json_encode($product) }})"
                                    class="bg-pink-brand hover:bg-pink-brand-dark text-white text-xs font-medium rounded-lg px-3 py-1.5 transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard">
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
                        {{-- Tombol untuk clear search jika ada --}}
                        @if (request('search'))
                            <div class="mt-6">
                                <a href="{{ url()->current() }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-main">
                                    Lihat Semua Produk
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
            <!-- /Daftar Produk -->

            <!-- Pagination -->
            <div class="mt-8">
                {{-- Gunakan view pagination default Laravel (akan menggunakan Tailwind jika terinstal) --}}
                {{ $products->links() }}
            </div>
            <!-- /Pagination -->

            <!-- Modal Beli -->
            <div x-show="showBuyModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;">

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
                        {{-- Pastikan selectedProduct.slug tersedia --}}
                        <form method="POST"
                            :action="selectedProduct && selectedProduct.slug ? `/products/${selectedProduct.slug}/purchase` : '#'"
                            x-ref="buyForm">
                            @csrf
                            {{-- product_id tidak lagi diperlukan di form jika menggunakan route model binding dengan slug --}}
                            {{-- <input type="hidden" name="product_id" :value="selectedProduct.id"> --}}

                            {{-- Ringkasan Produk --}}
                            <div
                                class="mb-4 p-3 bg-gray-50 dark:bg-dark-subcard/50 rounded-lg border dark:border-dark-border">
                                <p class="font-medium text-text-dark dark:text-text-light mb-2 text-sm">Detail Produk:
                                </p>
                                <div class="flex items-center space-x-3">
                                    {{-- Gunakan placeholder jika image null --}}
                                    <img {{-- Bind the src attribute dynamically --}}
                                        :src="selectedProduct.image ? {{-- 1. Check if selectedProduct.image exists --}}(selectedProduct.image.startsWith(
                                                    'http') ? {{-- 2. If it exists, check if it starts with 'http' (covers http:// and https://) --}} selectedProduct.image :
                                                {{-- 3. If yes (it's a full URL), use the image path directly --}} '{{ asset('storage') }}/' + selectedProduct
                                                .image {{-- 4. If no (it's a local path), prepend the storage asset path --}}
                                            ) :
                                            'https://via.placeholder.com/150/EEEEEE/AAAAAA?text=No+Image'
                                        {{-- 5. If selectedProduct.image doesn't exist, use the placeholder --}}"
                                        :alt="selectedProduct.name"
                                        class="w-12 h-12 rounded-md object-cover bg-gray-200 dark:bg-dark-border flex-shrink-0"
                                        {{-- Added flex-shrink-0 for safety in flex containers --}}
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';"
                                        {{-- Optional: Fallback if the image fails to load --}} />

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
                                    class="cursor-pointer text-sm font-medium text-gray-600 dark:text-gray-400 group-open:mb-2 list-none flex items-center justify-between">
                                    <span>Lihat Stok Tersedia</span>
                                    <svg class="w-4 h-4 transform transition-transform group-open:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div
                                    class="text-xs space-y-1.5 max-h-28 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30 mt-1">
                                    {{-- Gunakan properti yg ditambahkan di controller: size_name, color_name, color_code --}}
                                    <template
                                        x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                                        <template x-for="item in selectedProduct.stock_combinations"
                                            :key="item.id">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="px-1.5 py-0.5 bg-gray-200 dark:bg-dark-border text-[10px] rounded font-medium"
                                                    x-text="item.size_name ? item.size_name.toUpperCase() : 'N/A'"></span>
                                                <template x-if="item.color_code">
                                                    <span
                                                        class="w-3 h-3 rounded-full border dark:border-dark-border inline-block"
                                                        :style="'background-color: ' + item.color_code"
                                                        :title="item.color_name"></span>
                                                </template>
                                                <template x-if="!item.color_code">
                                                    <span
                                                        class="text-[10px] text-gray-400 dark:text-text-light/50 italic">(-)</span>
                                                </template>
                                                <span class="text-[10px] text-gray-500 dark:text-text-light/70 ml-auto"
                                                    x-text="item.stock + ' pcs'"></span>
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
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand"
                                    required>
                                    <option value="">-- Pilih Ukuran --</option>
                                    <template x-for="size in availableSizes" :key="size.id">
                                        <option :value="size.id" x-text="size.name"></option>
                                    </template>
                                </select>
                                @error('size_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Pilih Warna --}}
                            <div class="mb-4">
                                <label for="buy-color"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Pilih
                                    Warna</label>
                                <select id="buy-color" name="color_id" x-model="selectedColorId"
                                    @change="updateAvailableSizes(); updateMaxStock();"
                                    class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand"
                                    required>
                                    <option value="">-- Pilih Warna --</option>
                                    <template x-for="color in availableColors" :key="color.id">
                                        <option :value="color.id" x-text="color.name"></option>
                                    </template>
                                </select>
                                @error('color_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Jumlah --}}
                            <div class="mb-6">
                                <label for="buy-quantity"
                                    class="block text-sm font-medium mb-1 text-text-dark dark:text-text-light">Jumlah</label>
                                <div class="flex items-center">
                                    <input id="buy-quantity" type="number" name="quantity"
                                        class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand"
                                        min="1" :max="maxStock > 0 ? maxStock : 1" {{-- Set max ke 1 jika maxStock 0 --}}
                                        x-model.number="quantity" @input="validateQuantity()" {{-- Panggil fungsi validasi JS --}}
                                        required :disabled="!selectedSizeId || !selectedColorId || maxStock <= 0"
                                        {{-- Disable jika size/color belum dipilih atau stok 0 --}} />
                                    <span x-show="selectedSizeId && selectedColorId && maxStock > 0"
                                        class="ml-2 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        (Max: <span x-text="maxStock"></span>)
                                    </span>
                                    <span x-show="selectedSizeId && selectedColorId && maxStock <= 0"
                                        class="ml-2 text-xs text-red-500 whitespace-nowrap">
                                        (Stok Habis)
                                    </span>
                                </div>
                                <p x-show="!selectedSizeId || !selectedColorId" class="mt-1 text-xs text-orange-500">
                                    Pilih ukuran dan warna terlebih dahulu.</p>
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
                                    :disabled="!selectedSizeId || !selectedColorId || !quantity || quantity < 1 || quantity >
                                        maxStock || maxStock <= 0"
                                    {{-- Disable jika tidak valid --}}
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

            <!-- Modal Detail -->
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50 p-4"
                @click.away="closeModal" style="display: none;">

                <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white dark:bg-dark-card rounded-2xl p-5 sm:p-6 w-full max-w-lg shadow-xl dark:border dark:border-dark-border"
                    @click.stop> {{-- @click.stop mencegah @click.away di parent --}}

                    <template x-if="selectedProduct">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-text-dark dark:text-text-light">Detail Produk
                                </h3>
                                <button @click="closeModal"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="md:flex md:space-x-6">
                                {{-- Kolom Gambar --}}
                                <div class="md:w-1/2 mb-4 md:mb-0">
                                    <div
                                        class="aspect-square bg-gray-100 dark:bg-dark-border rounded-xl flex items-center justify-center text-gray-400 overflow-hidden">
                                        <template x-if="selectedProduct.image">
                                            <img
                                            :src="getProductImageUrl(selectedProduct)"
                                            :alt="selectedProduct ? selectedProduct.name : 'Product Image'"
                                            class="w-full h-full rounded-md object-cover bg-gray-200 dark:bg-dark-border flex-shrink-0"
                                            onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';"
                                        />

                                        </template>
                                        <template x-if="!selectedProduct.image">
                                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </template>
                                    </div>
                                </div>

                                {{-- Kolom Detail Teks --}}
                                <div class="md:w-1/2 space-y-3 text-sm">
                                    <h4 class="text-lg font-semibold text-text-dark dark:text-text-light"
                                        x-text="selectedProduct.name"></h4>

                                    <p class="text-xl font-bold text-pink-brand dark:text-pink-brand-dark">
                                        Rp <span x-text="Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                                    </p>

                                    <div>
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Ukuran & Stok
                                            Tersedia:</p>
                                        <div
                                            class="text-xs space-y-1.5 max-h-28 overflow-y-auto p-2.5 border dark:border-dark-border rounded-md bg-gray-50 dark:bg-dark-subcard/30">
                                            {{-- Gunakan properti yg ditambahkan di controller: size_name, color_name, color_code --}}
                                            <template
                                                x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                                                <template x-for="item in selectedProduct.stock_combinations"
                                                    :key="item.id">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="px-1.5 py-0.5 bg-gray-200 dark:bg-dark-border text-[10px] rounded font-medium"
                                                            x-text="item.size_name ? item.size_name.toUpperCase() : 'N/A'"></span>
                                                        <template x-if="item.color_code">
                                                            <span
                                                                class="w-3 h-3 rounded-full border dark:border-dark-border inline-block"
                                                                :style="'background-color: ' + item.color_code"
                                                                :title="item.color_name"></span>
                                                            <span
                                                                class="text-[10px] text-gray-600 dark:text-text-light/80"
                                                                x-text="item.color_name"></span>
                                                        </template>
                                                        <template x-if="!item.color_code">
                                                            <span
                                                                class="text-[10px] text-gray-400 dark:text-text-light/50 italic">Tanpa
                                                                Warna</span>
                                                        </template>
                                                        <span
                                                            class="text-[10px] text-gray-500 dark:text-text-light/70 ml-auto"
                                                            x-text="item.stock + ' pcs'"></span>
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

                                    <div>
                                        <p class="font-medium text-text-dark dark:text-text-light mb-1">Deskripsi:</p>
                                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed"
                                            x-text="selectedProduct.description || 'Tidak ada deskripsi.'"></p>
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
        {{-- Pastikan file ini ada dan berisi logika AlpineJS --}}
        <script>
            // public/js/productList.js

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
                    selectedSizeId: '', // Bound to the size select dropdown (name="size_id")
                    selectedColorId: '', // Bound to the color select dropdown (name="color_id")
                    quantity: 1, // Bound to the quantity input (name="quantity")
                    maxStock: 0, // Calculated max stock for the selected size/color combo
                    availableSizes: [], // Dynamically populated list of sizes for the dropdown
                    availableColors: [], // Dynamically populated list of colors for the dropdown
                    storageBaseUrl: '{{ rtrim(asset('storage'), '/') }}', // Get base URL from Blade, remove trailing slash if any

                    // --- Helper ---
                    isExternalImage(url) {
                        return url && (url.startsWith('http://') || url.startsWith('https://'));
                    },

                    /**
                     * Generates the correct image URL for a product.
                     * Handles local storage paths, full HTTP/HTTPS URLs, and provides a placeholder.
                     * @param {object|null} product The product object (must have an 'image' property)
                     * @param {string} placeholder (Optional) Custom placeholder URL
                     * @returns {string} The final image URL
                     */
                    getProductImageUrl(product, placeholder = 'https://via.placeholder.com/150/EEEEEE/AAAAAA?text=No+Image') {
                        // Check if product and product.image exist
                        if (!product || !product.image) {
                            return placeholder;
                        }

                        const imagePath = product.image;

                        // Check if it's already a full URL
                        if (this.isExternalImage(imagePath)) {
                            return imagePath;
                        } else {
                            // It's a local path, prepend the storage base URL
                            // Ensure no double slashes if imagePath somehow starts with /
                            return this.storageBaseUrl + '/' + imagePath.replace(/^\//, '');
                        }
                    },
                    // --- Methods ---

                    // Opens the Detail Modal
                    openModal(product) {
                        this.selectedProduct = product; // Store the product data
                        this.modalOpen = true; // Show the modal
                    },

                    // Closes the Detail Modal
                    closeModal() {
                        this.modalOpen = false;
                        // Delay clearing to allow fade-out transition
                        setTimeout(() => {
                            this.selectedProduct = null;
                        }, 300);
                    },

                    // Opens the Buy Modal
                    openBuyModal(product) {
                        // console.log("Opening Buy Modal for:", product); // Debugging
                        this.selectedProduct = product;
                        this.loading = true; // Show loading indicator immediately
                        this.resetBuyFormState(); // Clear previous selections/states

                        // Simulate a small delay if needed, or directly populate
                        // In a real scenario, if options were fetched via API, you'd do it here.
                        // Since data is already in `product.stock_combinations`, we populate directly.
                        this.populateInitialOptions();

                        this.loading = false; // Hide loading indicator
                        this.showBuyModal = true; // Show the modal
                    },

                    // Closes the Buy Modal
                    closeBuyModal() {
                        this.showBuyModal = false;
                        // Delay clearing to allow fade-out transition
                        setTimeout(() => {
                            this.selectedProduct = null;
                            this.resetBuyFormState();
                        }, 300);
                    },

                    // Resets the state of the buy modal form
                    resetBuyFormState() {
                        this.selectedSizeId = '';
                        this.selectedColorId = '';
                        this.quantity = 1;
                        this.maxStock = 0;
                        this.availableSizes = [];
                        this.availableColors = [];
                    },

                    // Populates initial dropdown options when buy modal opens
                    populateInitialOptions() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) {
                            console.error("No product or stock combinations found for populating options.");
                            return;
                        }

                        const combinations = this.selectedProduct.stock_combinations;
                        // console.log("Combinations:", combinations); // Debugging

                        // Get unique sizes that exist in combinations
                        // Use Map to ensure uniqueness based on size.id
                        const uniqueSizes = [...new Map(combinations
                                .filter(c => c.size) // Filter out combinations without a size object
                                .map(item => [item.size.id, {
                                    id: item.size.id,
                                    name: item.size_name
                                }])) // Use pre-processed name
                            .values()
                        ];

                        // Get unique colors that exist in combinations
                        const uniqueColors = [...new Map(combinations
                                .filter(c => c.color) // Filter out combinations without a color object
                                .map(item => [item.color.id, {
                                    id: item.color.id,
                                    name: item.color_name
                                }])) // Use pre-processed name
                            .values()
                        ];

                        // console.log("Unique Sizes:", uniqueSizes);   // Debugging
                        // console.log("Unique Colors:", uniqueColors); // Debugging

                        this.availableSizes = uniqueSizes;
                        this.availableColors = uniqueColors;

                        // Important: Ensure selections are reset *after* populating
                        this.selectedSizeId = '';
                        this.selectedColorId = '';
                        this.maxStock = 0;
                    },

                    // Updates the list of available colors based on the selected size
                    updateAvailableColors() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                        const combinations = this.selectedProduct.stock_combinations;
                        const sizeId = parseInt(this.selectedSizeId);

                        if (!sizeId) {
                            // If no size is selected, show all unique colors again
                            this.availableColors = [...new Map(combinations
                                    .filter(c => c.color)
                                    .map(item => [item.color.id, {
                                        id: item.color.id,
                                        name: item.color_name
                                    }]))
                                .values()
                            ];
                            this.selectedColorId = ''; // Reset color selection
                            this.maxStock = 0; // Reset stock
                            return;
                        }

                        // Find colors available *for the selected size*
                        const colorsForSize = combinations
                            .filter(c => c.size_id === sizeId && c.color) // Match selected size_id and ensure color exists
                            .map(c => ({
                                id: c.color.id,
                                name: c.color_name
                            })); // Map to the color object/data needed

                        // Update availableColors with unique values for the selected size
                        this.availableColors = [...new Map(colorsForSize.map(item => [item.id, item])).values()];

                        // If the currently selected color is no longer in the available list for the new size, reset it
                        if (this.selectedColorId && !this.availableColors.some(c => c.id === parseInt(this.selectedColorId))) {
                            this.selectedColorId = '';
                        }

                        // Always update max stock after selection changes
                        this.updateMaxStock();
                    },

                    // Updates the list of available sizes based on the selected color (Optional but good UX)
                    updateAvailableSizes() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations) return;

                        const combinations = this.selectedProduct.stock_combinations;
                        const colorId = parseInt(this.selectedColorId);

                        if (!colorId) {
                            // If no color is selected, show all unique sizes again
                            this.availableSizes = [...new Map(combinations
                                    .filter(c => c.size)
                                    .map(item => [item.size.id, {
                                        id: item.size.id,
                                        name: item.size_name
                                    }]))
                                .values()
                            ];
                            this.selectedSizeId = ''; // Reset size selection
                            this.maxStock = 0; // Reset stock
                            return;
                        }

                        // Find sizes available *for the selected color*
                        const sizesForColor = combinations
                            .filter(c => c.color_id === colorId && c.size) // Match selected color_id and ensure size exists
                            .map(c => ({
                                id: c.size.id,
                                name: c.size_name
                            })); // Map to the size object/data needed

                        // Update availableSizes with unique values for the selected color
                        this.availableSizes = [...new Map(sizesForColor.map(item => [item.id, item])).values()];

                        // If the currently selected size is no longer in the available list for the new color, reset it
                        if (this.selectedSizeId && !this.availableSizes.some(s => s.id === parseInt(this.selectedSizeId))) {
                            this.selectedSizeId = '';
                        }

                        // Always update max stock after selection changes
                        this.updateMaxStock();
                    },


                    // Calculates and updates the maximum stock based on selected size and color
                    updateMaxStock() {
                        if (!this.selectedProduct || !this.selectedProduct.stock_combinations || !this.selectedSizeId || !this
                            .selectedColorId) {
                            this.maxStock = 0; // Not enough info to determine stock
                            this.validateQuantity(); // Re-validate quantity based on potentially zero stock
                            return;
                        }

                        const sizeId = parseInt(this.selectedSizeId);
                        const colorId = parseInt(this.selectedColorId);

                        const combination = this.selectedProduct.stock_combinations.find(
                            c => c.size_id === sizeId && c.color_id === colorId
                        );

                        this.maxStock = combination ? combination.stock : 0; // Set to 0 if combo not found
                        // console.log(`Max stock for Size ${sizeId} / Color ${colorId}: ${this.maxStock}`); // Debugging

                        // Re-validate quantity against the new maxStock
                        this.validateQuantity();
                    },

                    // Validates the quantity input
                    validateQuantity() {
                        // Ensure quantity is treated as a number
                        let currentQuantity = parseInt(this.quantity);

                        // Handle non-numeric input or NaN
                        if (isNaN(currentQuantity)) {
                            currentQuantity = 1;
                        }

                        // Minimum is 1
                        if (currentQuantity < 1) {
                            currentQuantity = 1;
                        }

                        // Maximum is maxStock (only if maxStock > 0)
                        if (this.maxStock > 0 && currentQuantity > this.maxStock) {
                            currentQuantity = this.maxStock;
                        }

                        // If maxStock is 0 (or less), and selections are made, quantity should ideally be unenterable
                        // but we can force it to 1 here, the submit button logic handles the disabling.
                        if (this.maxStock <= 0 && this.selectedSizeId && this.selectedColorId) {
                            currentQuantity = 1; // Or keep as is, but ensure button is disabled
                        }

                        // Update the model value if it changed
                        if (this.quantity !== currentQuantity) {
                            this.quantity = currentQuantity;
                        }
                    },

                    // --- Initialization Logic (Optional) ---
                    // init() {
                    //     console.log('Product List Alpine component initialized.');
                    //     // You could potentially load something initially here if needed
                    // }
                };
            }
        </script>

        {{-- Jika menggunakan FontAwesome untuk ikon (opsional) --}}
        {{-- <script src="https://kit.fontawesome.com/YOUR_KIT_ID.js" crossorigin="anonymous"></script> --}}
    @endpush

</x-app-layout>
