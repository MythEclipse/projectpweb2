<x-app-layout>
    {{-- Header dengan Breadcrumbs --}}
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

    <div class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses/Error (Gunakan Tema Warna) --}}
            @if (session('success') || session('error'))
            <div class="mb-6">
                <div x-data="{ showNotif: true }" x-show="showNotif" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="rounded-xl p-4 shadow-md {{ session('success') ? 'bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700/50 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700/50 text-red-800 dark:text-red-200' }}"
                     role="alert">
                     <div class="flex justify-between items-center">
                         <span class="font-medium">{{ session('success') ? 'Berhasil!' : 'Gagal!' }}</span>
                         <span class="text-sm ml-3">{{ session('success') ?? session('error') }}</span>
                         <button type="button" @click="showNotif = false" class="ml-auto -mr-1 p-1 rounded-md focus:outline-none focus:ring-2 {{ session('success') ? 'text-green-600 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800/50 focus:ring-green-500' : 'text-red-600 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 focus:ring-red-500' }}">
                            <span class="sr-only">Tutup</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                         </button>
                     </div>
                </div>
            </div>
            @endif

            {{-- Card Utama Detail Produk (Gunakan Tema Warna) --}}
            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border">
                <div class="p-6 md:p-8 lg:flex lg:gap-10">

                    {{-- Kolom Kiri: Gambar --}}
                    <div class="lg:w-5/12 xl:w-4/12 mb-6 lg:mb-0 flex-shrink-0">
                        <div class="aspect-square bg-gray-100 dark:bg-dark-subcard rounded-xl flex items-center justify-center overflow-hidden shadow-lg border dark:border-dark-border">
                             @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/400/EEEEEE/AAAAAA?text=Image+Error';">
                            @else
                                {{-- Placeholder yang lebih sesuai tema --}}
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-dark-subcard dark:to-dark-border">
                                    <svg class="w-20 h-20 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        {{-- TODO: Tambahkan galeri thumbnail jika perlu --}}
                    </div>

                    {{-- Kolom Kanan: Info & Form Pembelian (Gunakan Tema Warna) --}}
                    <div class="lg:w-7/12 xl:w-8/12 text-text-dark dark:text-text-light">
                        {{-- Nama Produk --}}
                        <h1 class="text-3xl lg:text-4xl font-extrabold mb-2 tracking-tight">{{ $product->name }}</h1>
                        {{-- Harga --}}
                        <p class="text-3xl font-bold text-pink-brand dark:text-pink-brand-dark mb-5">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Tombol Wishlist (jika user login, gunakan tema warna) --}}
                        @auth
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-6">
                                @csrf
                                @php $isInWishlist = Auth::user()->hasInWishlist($product); @endphp
                                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-card {{ $isInWishlist ? 'bg-red-100 border-red-300 text-red-700 hover:bg-red-200 dark:bg-red-900/50 dark:border-dark-border dark:text-red-300 hover:dark:bg-red-800/60 focus:ring-red-500' : 'bg-pink-100 border-pink-300 text-pink-700 hover:bg-pink-200 dark:bg-pink-900/50 dark:border-dark-border dark:text-pink-300 hover:dark:bg-pink-800/60 focus:ring-pink-500' }}">
                                    @if ($isInWishlist)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>
                                        Hapus dari Wishlist
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                        Tambah ke Wishlist
                                    @endif
                                </button>
                            </form>
                        @endauth

                        {{-- Form Pembelian (Gunakan Tema Warna) --}}
                        <form method="POST" action="{{ route('products.purchase', $product) }}" class="mt-6 border-t border-gray-200 dark:border-dark-border pt-6 space-y-4">
                            @csrf
                             <h2 class="text-xl font-semibold mb-1">Pesan Sekarang</h2>
                             <p class="text-sm text-gray-500 dark:text-text-light/70 mb-4">Pilih ukuran dan warna yang Anda inginkan.</p>

                             {{-- Pilihan Ukuran & Warna dalam Grid --}}
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                 {{-- Pilih Ukuran --}}
                                 <div>
                                    <label for="size_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Ukuran</label>
                                    <select id="size_id" name="size_id" required
                                            class="block w-full p-2.5 border border-gray-300 dark:border-dark-border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('size_id') ? 'border-red-500 ring-1 ring-red-500' : 'dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                        <option value="" disabled {{ old('size_id') ? '' : 'selected' }}>-- Pilih Ukuran --</option>
                                        @forelse ($availableSizes as $size)
                                            <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                                {{ $size->name }}
                                            </option>
                                        @empty
                                             <option value="" disabled>Ukuran tidak tersedia</option>
                                        @endforelse
                                    </select>
                                    @error('size_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                 {{-- Pilih Warna --}}
                                 <div>
                                    <label for="color_id" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Warna</label>
                                    <select id="color_id" name="color_id" required
                                            class="block w-full p-2.5 border border-gray-300 dark:border-dark-border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('color_id') ? 'border-red-500 ring-1 ring-red-500' : 'dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                         <option value="" disabled {{ old('color_id') ? '' : 'selected' }}>-- Pilih Warna --</option>
                                        @forelse ($availableColors as $color)
                                            <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Warna tidak tersedia</option>
                                        @endforelse
                                    </select>
                                    @error('color_id') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                 </div>
                             </div>

                            {{-- Jumlah --}}
                            <div class="pt-2"> {{-- add padding top --}}
                                <label for="quantity" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Jumlah</label>
                                <input id="quantity" type="number" name="quantity" required min="1" value="{{ old('quantity', 1) }}"
                                        placeholder="1"
                                        class="block w-full p-2.5 border border-gray-300 dark:border-dark-border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('quantity') ? 'border-red-500 ring-1 ring-red-500' : 'dark:focus:border-pink-brand focus:border-pink-brand' }}">
                                @error('quantity') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                {{-- Info stok max bisa ditambahkan di sini jika diperlukan --}}
                             </div>

                             {{-- Tombol Submit (Gunakan Tema Warna) --}}
                            <div class="pt-4"> {{-- add padding top --}}
                                @php
                                    // Hitung total stok lagi di sini untuk tombol disable
                                    $totalStock = $product->stockCombinations->sum('stock');
                                @endphp
                                <button type="submit" id="buy-now-button" {{ $totalStock <= 0 ? 'disabled' : '' }}
                                    class="w-full flex items-center justify-center px-8 py-3 bg-pink-brand text-base font-medium text-white rounded-lg shadow-lg hover:bg-pink-brand-dark transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card disabled:opacity-50 disabled:cursor-not-allowed">
                                     <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Beli Sekarang
                                </button>
                                @if($totalStock <= 0)
                                    <p class="text-center text-red-500 dark:text-red-400 text-sm font-semibold mt-3">Stok produk ini habis.</p>
                                    {{-- Optionally disable selects/inputs too via JS if needed --}}
                                @endif
                            </div>
                        </form>

                         {{-- Deskripsi Produk (Gunakan Tema Warna) --}}
                         <div class="mt-10 border-t border-gray-200 dark:border-dark-border pt-6">
                             <h2 class="text-lg font-semibold mb-3 text-text-dark dark:text-text-light">Deskripsi Produk</h2>
                             <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-text-light/80 leading-relaxed space-y-3">
                                 {{-- Gunakan nl2br untuk mempertahankan line break dari textarea, e() untuk escaping HTML --}}
                                 {!! nl2br(e($product->description ?: 'Tidak ada deskripsi untuk produk ini.')) !!}
                             </div>
                         </div>
                    </div> {{-- End Kolom Kanan --}}
                </div> {{-- End p-6 / flex container --}}
            </div> {{-- End Card Utama --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- Script untuk disable form elements jika stok habis (opsional, karena tombol sudah disabled) --}}
    @if($product->stockCombinations->sum('stock') <= 0)
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buyButton = document.getElementById('buy-now-button');
                if (buyButton && buyButton.disabled) {
                    // Optionally disable selects and quantity input too
                    document.getElementById('size_id')?.setAttribute('disabled', 'disabled');
                    document.getElementById('color_id')?.setAttribute('disabled', 'disabled');
                    document.getElementById('quantity')?.setAttribute('disabled', 'disabled');
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>



