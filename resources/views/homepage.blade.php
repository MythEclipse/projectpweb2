<x-app-layout>
    {{-- Modal Notifikasi (Sukses/Error) - Tetap Ada --}}
    @if (session('success') || session('error'))
        <div x-data="{ showModal: true, closeModal() { this.showModal = false; } }" x-init="$nextTick(() => showModal = true)" x-show="showModal"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black/70" style="display: none;">
            <div class="bg-white dark:bg-dark-card rounded-2xl p-6 w-80 max-w-full shadow-xl dark:border dark:border-dark-border"
                @click.away="closeModal">
                {{-- Konten Notifikasi Modal (tidak berubah) --}}
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <p class="text-text-dark dark:text-text-light mb-6">{{ session('success') ?? session('error') }}</p>
                <div class="flex justify-end">
                    <button @click="closeModal" class="px-4 py-2 bg-pink-brand text-white rounded-lg hover:bg-pink-brand-dark transition-colors focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-card">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Kontainer Utama Produk - Hapus x-data --}}
    <div class="p-4 sm:p-6 bg-white dark:bg-dark-card rounded-2xl shadow-sm">

        {{-- Skeleton Loader Area (Tetap Ada) --}}
        {{-- Skeleton shown/hidden via JavaScript --}}
        <div id="product-list-skeleton" class="hidden">
             {{-- Skeleton Search Bar --}}
            <div class="relative mb-6 animate-pulse">
                <div class="w-full h-[46px] bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
            </div>
            {{-- Skeleton Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6 animate-pulse">
                @php $skeletonCount = $products->perPage() ?: 12; @endphp
                @for ($i = 0; $i < $skeletonCount; $i++)
                    <div class="bg-gray-200 dark:bg-dark-subcard/50 rounded-xl shadow-md overflow-hidden flex flex-col h-[270px] sm:h-[290px]">
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
                    <div class="flex items-center space-x-1 sm:space-x-1.5">
                        <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 bg-pink-300 dark:bg-pink-800/50 rounded-md"></div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                        <div class="h-8 w-8 sm:h-9 sm:w-9 bg-gray-300 dark:bg-dark-border rounded-md"></div>
                    </div>
                    <div class="h-4 bg-gray-300 dark:bg-dark-border rounded w-36 sm:w-48"></div>
                </div>
            @endif
        </div>
        {{-- >>> END: Skeleton Loader Area <<< --}}

        {{-- Kontainer untuk Konten Asli --}}
        {{-- Initially visible --}}
        <div id="product-list-content">
            <!-- Pencarian Server-Side -->
            {{-- Removed data-turbo-frame --}}
            <form method="GET" action="{{ route('homepage') }}" class="relative mb-6">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full border border-gray-300 dark:border-dark-border rounded-lg py-2.5 pl-4 pr-12 focus:ring-2 focus:ring-pink-brand/50 focus:border-pink-brand dark:bg-dark-subcard dark:text-text-light placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="Cari produk...">
                <button type="submit"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-brand dark:hover:text-pink-brand-dark transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                </button>
            </form>

            <!-- Daftar Produk -->
            <div id="product-grid"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
                @forelse ($products as $product)
                    <div class="bg-white dark:bg-dark-subcard rounded-xl shadow-md overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-gray-200 dark:border-dark-border hover:border-pink-300 dark:hover:border-pink-brand/50">
                        {{-- Link ke Detail Page pada Gambar --}}
                        {{-- Removed @click --}}
                        <a href="{{ route('products.show', $product) }}" class="block aspect-square w-full bg-gray-100 dark:bg-dark-border flex items-center justify-center text-gray-400 overflow-hidden relative group" data-turbo="false">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="object-cover h-full w-full group-hover:scale-105 transition-transform duration-300 ease-in-out"
                                    loading="lazy"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/150/EEEEEE/AAAAAA?text=Error';">
                            @else
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </a>

                        {{-- Konten Detail Produk --}}
                        <div class="p-3 sm:p-4 flex flex-col flex-grow">
                            {{-- Link ke Detail Page pada Nama --}}
                            <h3 class="text-sm sm:text-base font-semibold text-text-dark dark:text-text-light mb-1 truncate" title="{{ $product->name }}">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-pink-brand dark:hover:text-pink-brand-dark transition-colors">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-pink-brand dark:text-pink-brand-dark font-bold text-base sm:text-lg mb-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            {{-- Indikator Stok --}}
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                Stok:
                                @if(isset($product->total_stock) && $product->total_stock > 0)
                                    <span class="text-green-600 dark:text-green-400 font-medium">Tersedia</span>
                                @else
                                    <span class="text-red-500 dark:text-red-400 font-medium">Habis</span>
                                @endif
                            </p>

                            {{-- Tombol Wishlist (Tetap Ada, Server-Side) --}}
                            @auth
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="mb-2">
                                    @csrf
                                    @php $isInWishlist = Auth::user()->hasInWishlist($product); @endphp
                                    <button type="submit" class="w-full flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard {{ $isInWishlist ? 'bg-red-100 border-red-300 text-red-700 hover:bg-red-200 dark:bg-red-900/50 dark:border-dark-border dark:text-red-300 hover:dark:bg-red-800/60 focus:ring-red-500' : 'bg-pink-100 border-pink-300 text-pink-700 hover:bg-pink-200 dark:bg-pink-900/50 dark:border-dark-border dark:text-pink-300 hover:dark:bg-pink-800/60 focus:ring-pink-500' }}" title="{{ $isInWishlist ? __('Remove from Wishlist') : __('Add to Wishlist') }}">
                                        @if ($isInWishlist)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5 flex-shrink-0"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>
                                            <span>{{ __('Remove') }}</span>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1.5 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                                            <span>{{ __('Wishlist') }}</span>
                                        @endif
                                    </button>
                                </form>
                            @endauth

                            {{-- Tombol "Beli" Menjadi Link ke Detail --}}
                            {{-- Removed @click and :disabled --}}
                            <div class="mt-auto">
                                <a href="{{ route('products.show', $product) }}"
                                   class="block text-center w-full bg-pink-brand hover:bg-pink-brand-dark text-white text-xs font-medium rounded-lg px-3 py-1.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:focus:ring-offset-dark-subcard">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada produk --}}
                    <div class="col-span-full text-center py-16 px-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-text-dark dark:text-text-light">Produk tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if (request('search'))
                                Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}".
                            @else
                                Belum ada produk yang tersedia saat ini.
                            @endif
                        </p>
                        @if (request('search'))
                            <div class="mt-6">
                                {{-- Removed data-turbo-action="replace" as it's no longer needed without the frame --}}
                                <a href="{{ route('homepage') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                    Hapus Pencarian
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>
            <!-- /Daftar Produk -->

            <!-- Pagination (Tetap Ada) -->
            @if ($products->hasPages())
                {{-- Added an ID for easier JS targeting --}}
                <div class="mt-8" id="product-pagination">
                    {{-- Pastikan pagination view Anda sesuai dengan tema --}}
                    {{ $products->links() }}
                </div>
            @endif
        </div> {{-- End #product-list-content --}}

        {{-- >>> MODAL BELI DAN DETAIL DIHAPUS DARI SINI <<< --}}

    </div> {{-- End Kontainer Utama Produk --}}

    {{-- Script Skeleton Loader (Adjusted for non-frame loading) --}}
    @push('scripts')
        <script>
            // --- Skrip Skeleton Loader ---
            document.addEventListener('DOMContentLoaded', () => {
                const skeletonContainer = document.getElementById('product-list-skeleton');
                const contentContainer = document.getElementById('product-list-content');
                const searchForm = document.querySelector('form[action="{{ route('homepage') }}"]'); // Target the search form
                const paginationContainer = document.getElementById('product-pagination'); // Target the pagination div

                if (!skeletonContainer || !contentContainer) {
                    // If necessary elements aren't found, stop.
                    console.warn('Skeleton or content container not found.');
                    return;
                }

                const showSkeleton = () => {
                    contentContainer.style.display = 'none';
                    skeletonContainer.style.display = 'block';
                };

                const hideSkeleton = () => {
                    skeletonContainer.style.display = 'none';
                    // Ensure content is shown if it was hidden by the skeleton
                    if (contentContainer.style.display === 'none') {
                        contentContainer.style.display = ''; // Reset display to default (usually block or grid depending on container)
                    }
                };

                // --- Listen for actions that trigger page load (search/pagination) ---

                // Listen for search form submission
                if (searchForm) {
                    searchForm.addEventListener('submit', (event) => {
                         // show skeleton slightly before Turbo starts fetching
                         requestAnimationFrame(showSkeleton);
                    });
                }

                // Listen for clicks on pagination links within the pagination container
                if (paginationContainer) {
                     paginationContainer.addEventListener('click', (event) => {
                        const link = event.target.closest('a[href]');
                        // Check if the clicked element or its ancestor is a link *and* it's within the pagination container
                        // and it's not data-turbo="false" (though pagination links usually aren't)
                        if (link && paginationContainer.contains(link) && link.getAttribute('data-turbo') !== 'false') {
                            // If it's a standard link handled by Turbo Drive, show skeleton
                            requestAnimationFrame(showSkeleton);
                        }
                     });
                }


                // --- Listen for Turbo Drive events ---

                // Hide skeleton when Turbo finishes rendering the new page content
                // This happens after initial load and after any Turbo Drive navigation completes
                document.addEventListener('turbo:load', hideSkeleton);

                // Hide skeleton if the fetch request fails
                document.addEventListener('turbo:fetch-request-error', hideSkeleton);

                // Optional: Handle Turbo cache restoration
                // When navigating back/forward, Turbo might show a cached page instantly before fetching the fresh one.
                // You might want to hide the skeleton explicitly here if you want it to appear only on fresh fetches.
                // Or, you might want to show a lighter indicator. For this example, hideSkeleton on turbo:load is enough.
                // document.addEventListener('turbo:restoration-start', hideSkeleton);

                 // Initial state: Ensure content is visible and skeleton is hidden
                 // This handles cases where browser back button might restore skeleton state or initial server render state
                 hideSkeleton(); // Make sure it's hidden on DOMContentLoaded

            });
        </script>
    @endpush

</x-app-layout>
