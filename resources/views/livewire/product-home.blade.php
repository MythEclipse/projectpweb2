<div class="p-6 bg-white dark:bg-[#0a0a0a] rounded-2xl" data-turbo="false">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-pink-600 dark:text-pink-400">Our Products</h2>
        <input type="text" wire:model="search" placeholder="Search..."
            class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow p-4 flex flex-col items-start hover:shadow-lg transition">
                <div class="h-40 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mb-4 flex items-center justify-center text-gray-400">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="object-cover h-full w-full rounded-xl">
                    @else
                        <span>No Image</span>
                    @endif
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $product->name }}</h3>
                <p class="text-pink-600 dark:text-pink-400 font-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <button class="mt-auto bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl text-sm">View Detail</button>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                No products found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
