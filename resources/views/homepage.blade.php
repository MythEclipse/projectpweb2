<x-app-layout>
    <turbo-frame id="products_frame">
        <div class="p-6 bg-white dark:bg-[#0a0a0a] rounded-2xl" x-data="productList()">
            <!-- Search Form -->
            <div class="relative mb-4">
                <form @submit.prevent class="flex">
                    <input type="text" x-model="search"
                        class="w-full border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white"
                        placeholder="Search products...">
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Product List -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white dark:bg-[#2d2d2d] rounded-2xl shadow p-4 flex flex-col items-start hover:shadow-lg transition w-full h-[300px]"
                        x-show="filterProduct('{{ strtolower($product->name) }}')">

                        <div
                            class="h-32 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mb-4 flex items-center justify-center text-gray-400">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }} "
                                    class="object-cover h-full w-full rounded-xl">
                            @else
                                <span>No Image</span>
                            @endif
                        </div>

                        <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $product->name }}</h3>

                        <p class="text-pink-600 dark:text-pink-400 font-bold mb-1">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                            {{ $product->description ?? 'No description' }}
                        </p>

                        <!-- Tombol Aksi -->
                        <div class="mt-auto w-full flex space-x-2">
                            <button @click="openModal({{ json_encode($product) }})"
                                class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-xl text-xs">
                                Detail
                            </button>

                            <form method="POST" action="" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-pink-600 hover:bg-pink-700 text-white px-3 py-2 rounded-xl text-xs">
                                    Beli
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex flex-col md:flex-row items-center justify-between mt-6 space-y-4 md:space-y-0">
                <div class="flex items-center space-x-1 text-gray-600 dark:text-gray-400">
                    @if ($products->onFirstPage())
                        <span
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&lt;</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&lt;</a>
                    @endif

                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                        @if ($i == $products->currentPage())
                            <span
                                class="px-3 py-1 rounded-md bg-pink-500 text-white font-semibold">{{ $i }}</span>
                        @else
                            <a href="{{ $products->url($i) }}"
                                class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">{{ $i }}</a>
                        @endif
                    @endfor

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&gt;</a>
                    @else
                        <span
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&gt;</span>
                    @endif
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-400 text-center md:text-right">
                    Showing page <span
                        class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->currentPage() }}</span>
                    of <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->lastPage() }}</span>
                    (Total: <span
                        class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->total() }}</span>
                    products)
                </div>
            </div>

            <!-- Modal -->
            <div x-show="modalOpen" @click.away="closeModal" x-transition.opacity
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-6 w-96 max-w-full">
                    <template x-if="selectedProduct">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Product Details</h3>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Name: <span class="text-pink-600 dark:text-pink-400"
                                    x-text="selectedProduct.name"></span>
                            </p>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Price:
                                <span class="text-pink-600 dark:text-pink-400">
                                    Rp <span x-text="Number(selectedProduct.price).toLocaleString('id-ID')"></span>
                                </span>
                            </p>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Sizes and Stock:</p>
                            <template x-if="selectedProduct.stock_combinations">
                                <div>
                                    <template x-for="item in selectedProduct.stock_combinations" :key="item.id">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900 text-xs rounded"
                                                x-text="item.size.name.toUpperCase()"></span>

                                            <span class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="item.stock + ' pcs'"></span>

                                            <template x-if="item.color">
                                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="item.color.name"></span>

                                                <span class="w-4 h-4 rounded-full border"
                                                    :style="'background-color: ' + item.color.code"></span>
                                            </template>

                                            <template x-if="!item.color">
                                                <span class="text-xs text-gray-400">No Color</span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <p class="font-medium text-gray-700 dark:text-gray-300 mt-2 mb-1">
                                Description:
                                <span class="text-gray-600 dark:text-gray-400"
                                    x-text="selectedProduct.description"></span>
                            </p>

                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Image:</h4>
                                <div
                                    class="h-40 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mt-1 flex items-center justify-center text-gray-400">
                                    <template x-if="selectedProduct.image">
                                        <img :src="'/storage/' + selectedProduct.image.replace(/\\/g, '/')"
                                            :alt="selectedProduct.name" class="object-cover h-full w-full rounded-xl">
                                    </template>
                                    <template x-if="!selectedProduct.image">
                                        <span class="text-gray-500 dark:text-gray-400">No Image</span>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button @click="closeModal"
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
        function productList() {
            return {
                search: '',
                modalOpen: false,
                selectedProduct: null,

                filterProduct(name) {
                    return name.includes(this.search.toLowerCase());
                },

                openModal(product) {
                    this.selectedProduct = product;
                    this.modalOpen = true;
                },

                closeModal() {
                    this.selectedProduct = null;
                    this.modalOpen = false;
                }
            }
        }
    </script>
</x-app-layout>
