<turbo-frame id="products_frame">
    <div x-data="{ loading: false }"
         x-init="
            document.addEventListener('turbo:before-fetch-request', (e) => {
                if (e.target.id === 'products_frame') loading = true
            });
            document.addEventListener('turbo:frame-load', (e) => {
                if (e.target.id === 'products_frame') loading = false
            });
         "
         class="space-y-6 px-4 sm:px-6">

        <!-- Search Form -->
        <div class="relative">
            <form method="GET" action="{{ route('products.index') }}" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white"
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

        <!-- Table -->
        <div class="overflow-x-auto bg-white dark:bg-[#0a0a0a] rounded-xl shadow">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-[#3E3E3A]">
                <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                <tr class="text-gray-600 dark:text-gray-300">
                    <th class="px-4 py-3 text-left hidden sm:table-cell">#</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left hidden sm:table-cell">Price</th>
                    <th class="px-4 py-3 text-left hidden md:table-cell">Variants</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-gray-100 dark:divide-[#2d2d2d]">
                <!-- Loading Skeleton -->
                <template x-if="loading">
                    <tr class="border-b border-gray-100 dark:border-[#2d2d2d] animate-pulse">
                        <td class="px-4 py-4 hidden sm:table-cell"><div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-6"></div></td>
                        <td class="px-4 py-4"><div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-32"></div></td>
                        <td class="px-4 py-4 hidden sm:table-cell"><div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-20"></div></td>
                        <td class="px-4 py-4 hidden md:table-cell"><div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-24"></div></td>
                        <td class="px-4 py-4">
                            <div class="flex space-x-2">
                                <div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-12"></div>
                                <div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-12"></div>
                                <div class="h-4 bg-gray-300 dark:bg-[#2d2d2d] rounded w-12"></div>
                            </div>
                        </td>
                    </tr>
                </template>

                <!-- Loaded Data -->
                <template x-if="!loading">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                            <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                            <td class="px-4 py-4 font-semibold text-gray-800 dark:text-gray-100 break-words">{{ $product->name }}</td>
                            <td class="px-4 py-4 hidden sm:table-cell text-pink-600 dark:text-pink-400 whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                @if ($product->sizes->isEmpty())
                                    <span class="text-sm text-gray-500 dark:text-gray-400">No variants</span>
                                @elseif ($product->sizes->count() === 1)
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 rounded-md bg-pink-100 dark:bg-pink-900 text-xs font-medium">
                                            {{ strtoupper($product->sizes->first()->name) }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $product->sizes->first()->pivot->stock }} pcs
                                        </span>
                                    </div>
                                @else
                                    <div x-data="{ open: false }">
                                        <button @click="open = !open"
                                                class="text-sm text-pink-600 dark:text-pink-400 hover:underline">
                                            View ({{ $product->sizes->count() }})
                                        </button>
                                        <div x-show="open" x-transition class="mt-2 space-y-2">
                                            @foreach ($product->sizes as $size)
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 rounded-md bg-pink-100 dark:bg-pink-900 text-xs font-medium">
                                                        {{ strtoupper($size->name) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $size->pivot->stock }} pcs
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                        View
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" data-turbo="false">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm" data-turbo="false">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">No products found.</td>
                        </tr>
                    @endforelse
                </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
            <div class="flex items-center space-x-1">
                @if ($products->onFirstPage())
                    <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&lt;</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&lt;</a>
                @endif

                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    @if ($i == $products->currentPage())
                        <span class="px-3 py-1 rounded-md bg-pink-500 text-white font-semibold">{{ $i }}</span>
                    @else
                        <a href="{{ $products->url($i) }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">{{ $i }}</a>
                    @endif
                @endfor

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&gt;</a>
                @else
                    <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&gt;</span>
                @endif
            </div>

            <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
                Showing page <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->currentPage() }}</span>
                of <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->lastPage() }}</span>
                (Total: <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->total() }}</span> products)
            </div>
        </div>

    </div>
</turbo-frame>
