<x-app-layout>

    <!-- Product Grid Section -->
    <main class="pt-20 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto mb-8 flex flex-col md:flex-row gap-4">
            <input type="text" x-model="searchQuery" placeholder="Cari produk..."
                class="w-full md:w-64 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            <select x-model="selectedCategory" class="w-full md:w-48 px-4 py-2 border rounded-lg">
                <option>Semua</option>
                <option>Pria</option>
                <option>Wanita</option>
                <option>Unisex</option>
            </select>
            <select x-model="sortBy" class="w-full md:w-48 px-4 py-2 border rounded-lg">
                <option value="terbaru">Terbaru</option>
                <option value="harga_terendah">Harga Terendah</option>
                <option value="harga_tertinggi">Harga Tertinggi</option>
            </select>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <template x-for="product in filteredProducts" :key="product.id">
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="relative group">
                        <img :src="product.image" :alt="product.name"
                            class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div x-show="!product.inStock"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <span class="text-white font-bold">Stok Habis</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 x-text="product.name" class="font-semibold text-lg mb-2"></h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-2">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(product.price)"></span>
                        </p>
                        <div class="flex justify-between items-center">
                            <button @click="cartItems++" :disabled="!product.inStock"
                                class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity">
                                + Keranjang
                            </button>
                            <button class="text-pink-600 hover:text-pink-700">
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="filteredProducts.length === 0" class="col-span-full text-center py-12">
                <p class="text-gray-600 text-lg">Produk tidak ditemukan</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-[#1a1a1a] border-t border-gray-100 dark:border-[#3E3E3A]">
        <div class="container mx-auto px-4 lg:px-8 py-12">
            <div class="text-center">
                <p class="text-gray-600 dark:text-gray-400">&copy; 2025 Fashionku. All rights reserved.</p>
                <div class="mt-4 flex justify-center space-x-4">
                    <a href="#" class="text-gray-500 hover:text-pink-600">Instagram</a>
                    <a href="#" class="text-gray-500 hover:text-pink-600">Facebook</a>
                    <a href="#" class="text-gray-500 hover:text-pink-600">TikTok</a>
                </div>
            </div>
        </div>
    </footer>
    </div>
</x-app-layout>
