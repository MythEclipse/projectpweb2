<div x-show="showBuyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.away="closeBuyModal">
    <div class="bg-white dark:bg-[#1a1a1a] rounded-2xl p-6 w-96 max-w-full" @click.stop>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Beli Produk</h2>

        <template x-if="loading">
            <div class="text-center py-10">
                <span class="text-gray-500">Loading...</span>
            </div>
        </template>

        <template x-if="!loading && selectedProduct">
            <form method="POST" :action="`/products/${selectedProduct.name.replace(/\s+/g, '-').toLowerCase()}/purchase`">
                @csrf

                <!-- Ukuran dan Stok -->
                <p class="font-medium text-gray-700 dark:text-gray-300 mb-1">Ukuran dan Stok:</p>
                <template x-if="selectedProduct.stock_combinations && selectedProduct.stock_combinations.length > 0">
                    <div>
                        <template x-for="item in selectedProduct.stock_combinations" :key="item.id">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 bg-pink-100 dark:bg-pink-900 text-xs rounded"
                                    x-text="item.size.name.toUpperCase()"></span>

                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="item.stock + ' pcs'"></span>

                                <template x-if="item.color">
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="item.color.name"></span>
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

                <!-- Pilih Ukuran -->
                <div class="mb-4">
                    <label class="block mb-2">Pilih Ukuran</label>
                    <select name="size_id" class="w-full p-2 border rounded" required>
                        <template x-for="size in availableSizes" :key="size.id">
                            <option :value="size.id" x-text="size.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Pilih Warna -->
                <div class="mb-4">
                    <label class="block mb-2">Pilih Warna</label>
                    <select name="color_id" class="w-full p-2 border rounded" required>
                        <template x-for="color in availableColors" :key="color.id">
                            <option :value="color.id" x-text="color.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Jumlah -->
                <div class="mb-4">
                    <label class="block mb-2">Jumlah</label>
                    <x-text-input type="number" name="quantity" class="w-full p-2 border rounded" min="1"
                        max="maxStock" required/>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeBuyModal" class="px-4 py-2 text-gray-500 hover:text-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">
                        Beli
                    </button>
                </div>
            </form>
        </template>
    </div>
</div>
