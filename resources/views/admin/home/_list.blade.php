<turbo-frame id="products_frame">
    <div class="overflow-auto rounded-xl shadow">
        <table class="min-w-full bg-white dark:bg-[#0a0a0a] text-sm">
            <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                <tr class="text-gray-600 dark:text-gray-300 border-b border-gray-200 dark:border-[#3E3E3A]">
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Variants</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr
                        class="border-b border-gray-100 dark:border-[#2d2d2d] hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                        <td class="px-4 py-4">
                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td class="px-4 py-4 font-semibold text-gray-800 dark:text-gray-100">{{ $product->name }}</td>
                        <td class="px-4 py-4 text-pink-600 dark:text-pink-400">
                            Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 space-y-1">
                            @foreach ($product->sizes as $size)
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="px-2 py-1 rounded-md bg-pink-100 dark:bg-pink-900 text-xs font-medium">{{ strtoupper($size->name) }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $size->pivot->stock }}
                                        pcs</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('products.show', $product) }}"
                                    class="text-blue-600 dark:text-blue-400 hover:underline text-sm">View</a>
                                <a href="{{ route('products.edit', $product) }}"
                                    class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}"
                                    class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->withQueryString()->links() }}
    </div>
</turbo-frame>
