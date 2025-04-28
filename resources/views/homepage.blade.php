<x-app-layout>
    <turbo-frame id="products_frame">
        <!-- Modal Component -->
        @if (session('success') || session('error'))
            <div x-data="{ showModal: true }" x-show="showModal" x-transition
                class="fixed inset-0 flex items-center justify-center z-50 bg-black/50" style="display: none;">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 w-80 max-w-full shadow-lg dark:border dark:border-dark-border"
                    @click.away="showModal = false">
                    <h2 class="text-lg font-semibold text-text-dark dark:text-text-light mb-4">
                        @if (session('success'))
                            Sukses
                        @elseif (session('error'))
                            Gagal
                        @endif
                    </h2>

                    <p class="text-text-dark dark:text-text-light mb-6">
                        @if (session('success'))
                            {{ session('success') }}
                        @elseif (session('error'))
                            {{ session('error') }}
                        @endif
                    </p>

                    <div class="flex justify-end">
                        <button @click="showModal = false"
                            class="px-4 py-2 bg-pink-brand text-white rounded hover:bg-pink-brand-dark transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif


        <div class="p-6 bg-white dark:bg-[#0a0a0a] rounded-2xl" x-data="productList()">
            @include('homepage.partials._search')

            <!-- Product List -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-6">
                @foreach ($products as $product)
                    @include('homepage.partials._product-item', ['product' => $product])
                @endforeach
            </div>

            @include('homepage.partials._pagination', ['products' => $products])
            @include('homepage.partials._buy-modal')
            @include('homepage.partials._detail-modal')
        </div>
    </turbo-frame>
    <script src="/js/productList.js"></script>
</x-app-layout>
