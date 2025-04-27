<x-app-layout>
    <turbo-frame id="products_frame">
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
