<x-app-layout>
    <turbo-frame id="products_frame"> <!-- ✨ Turbo Frame supaya pagination halus -->
        <div class="p-6 bg-white dark:bg-[#0a0a0a] rounded-2xl" x-data="{ open: false, product: null, search: '' }">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-pink-600 dark:text-pink-400">Our Products</h2>
                <input type="text" x-model="search" placeholder="Search..."
                    class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div
                        class="bg-white dark:bg-[#2d2d2d] rounded-2xl shadow p-4 flex flex-col items-start hover:shadow-lg transition w-full h-[400px]"
                        x-show="productMatchesSearch('{{ $product->name }}')"
                    >
                        <div class="h-40 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mb-4 flex items-center justify-center text-gray-400">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="object-cover h-full w-full rounded-xl">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $product->name }}</h3>
                        <p class="text-pink-600 dark:text-pink-400 font-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                        <button
                            @click="open = true; product = JSON.parse(atob('{{ base64_encode(json_encode($product)) }}'))"
                            class="mt-auto bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl text-sm">
                            View Detail
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>

            <!-- Modal -->
            <div x-show="open" @click.away="open = false; product = null"
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-6 w-96 max-w-full" x-show="open" x-transition>
                    <template x-if="product">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Product Details</h3>
                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Name:
                                <span class="text-pink-600 dark:text-pink-400" x-text="product.name"></span>
                            </p>
                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Price:
                                <span class="text-pink-600 dark:text-pink-400">
                                    Rp <span x-text="Number(product.price).toLocaleString('id-ID')"></span>
                                </span>
                            </p>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Sizes and Stock:</p>
                            <ul class="text-sm text-gray-600 dark:text-gray-400">
                                <template x-for="combination in product.stock_combinations" :key="combination.id">
                                    <li>
                                        <span x-text="combination.size.name"></span> -
                                        <span x-text="combination.color.name"></span>:
                                        <span x-text="combination.stock"></span> pcs
                                    </li>
                                </template>
                            </ul>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Description:
                                <span class="text-gray-600 dark:text-gray-400" x-text="product.description"></span>
                            </p>

                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Image:</h4>
                                <div class="h-40 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mt-1 flex items-center justify-center text-gray-400">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image.replace(/\\/g, '/')" :alt="product.name"
                                            class="object-cover h-full w-full rounded-xl">
                                    </template>
                                    <template x-if="!product.image">
                                        <span class="text-gray-500 dark:text-gray-400">No Image</span>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button @click="open = false; product = null"
                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl">
                                    Close
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </turbo-frame>

    <script>
        function productMatchesSearch(name) {
            return name.toLowerCase().includes(document.querySelector('[x-model="search"]').value.toLowerCase());
        }
    </script>
</x-app-layout>
