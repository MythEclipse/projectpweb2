<div
    x-cloak
    x-show="showBuyModal"
    x-init="$watch('showBuyModal', value => { if (value) loadProductData() })"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 transition-all"
>
    <div
        class="bg-white dark:bg-[#2d2d2d] rounded-xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto"
        @click.away="showBuyModal = false"
    >
        <h2 class="text-xl font-bold mb-4">Beli Produk</h2>

        <template x-if="loading">
            <div class="text-center py-10">
                <span class="text-gray-500">Loading...</span>
            </div>
        </template>

        <template x-if="!loading">
            <form method="POST" :action="`/products/${selectedProduct}/purchase`">
                @csrf

                <!-- Pilih Ukuran -->
                <div class="mb-4">
                    <label class="block mb-2">Ukuran</label>
                    <select name="size_id" class="w-full p-2 border rounded" required>
                        <template x-for="size in availableSizes" :key="size.id">
                            <option :value="size.id" x-text="size.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Pilih Warna -->
                <div class="mb-4">
                    <label class="block mb-2">Warna</label>
                    <select name="color_id" class="w-full p-2 border rounded" required>
                        <template x-for="color in availableColors" :key="color.id">
                            <option :value="color.id" x-text="color.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Jumlah -->
                <div class="mb-4">
                    <label class="block mb-2">Jumlah</label>
                    <input
                        type="number"
                        name="quantity"
                        class="w-full p-2 border rounded"
                        min="1"
                        :max="maxStock"
                        required
                    >
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="showBuyModal = false"
                        class="px-4 py-2 text-gray-500 hover:text-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Beli
                    </button>
                </div>
            </form>
        </template>
    </div>
</div>
