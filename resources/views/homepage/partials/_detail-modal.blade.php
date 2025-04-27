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
                                    <template x-for="item in selectedProduct.stock_combinations"
                                        :key="item.id">
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
