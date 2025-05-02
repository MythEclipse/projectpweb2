{{-- resources/views/admin/products/_list.blade.php --}}
<turbo-frame id="products_frame">

    {{-- Actions: Search --}}
    {{-- Use px-4 sm:px-0 for outer elements like search/pagination if container has padding --}}
    {{-- The parent home.blade.php already has p-4 sm:p-6, so px-0 is fine here --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-0 mb-6">
        {{-- Search Form --}}
        <div class="relative w-full sm:w-auto flex-grow">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center" data-turbo-frame="products_frame">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white"
                       placeholder="Cari produk...">
                <button type="submit" title="Cari"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" /></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="space-y-6 px-0"> {{-- Inner container matching reference --}}

        <div class="overflow-x-auto bg-white dark:bg-[#0a0a0a] rounded-xl shadow">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-[#3E3E3A]">
                <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                <tr class="text-gray-600 dark:text-gray-300">
                    {{-- Use px-4 py-3 uppercase tracking-wider for headers --}}
                    <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">#</th>
                    <th class="px-4 py-3 text-left uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">Harga</th>
                    <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Varian</th>
                    <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th> {{-- Centered like reference --}}
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-gray-100 dark:divide-[#2d2d2d] text-gray-700 dark:text-gray-300">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                        {{-- Use px-4 py-4 for data cells --}}
                        <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td class="px-4 py-4 font-semibold break-words">{{ $product->name }}</td> {{-- Added font-semibold --}}
                        <td class="px-4 py-4 hidden sm:table-cell text-pink-600 dark:text-pink-400 whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                            {{-- Variant Display Logic (from previous correction) --}}
                            @if ($product->stockCombinations->isEmpty())
                                <span class="text-xs text-gray-500 dark:text-gray-400">No variants</span> {{-- Adjusted text size --}}
                            @elseif ($product->stockCombinations->count() === 1)
                                @php $combination = $product->stockCombinations->first(); @endphp
                                <div class="flex items-center space-x-2">
                                     {{-- Using reference badge style (adjust colors if needed) --}}
                                    <span class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300 text-xs font-medium w-fit">
                                        {{ strtoupper($combination->size->name ?? 'N/A') }} / {{ $combination->color->name ?? 'N/A' }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $combination->stock }} pcs
                                    </span>
                                </div>
                            @else
                                <div x-data="{ open: false }">
                                    <button @click="open = !open"
                                            class="text-xs text-pink-600 dark:text-pink-400 hover:underline"> {{-- Adjusted text size --}}
                                        Lihat ({{ $product->stockCombinations->count() }})
                                    </button>
                                    {{-- Adjusted dropdown style slightly for consistency --}}
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                         class="mt-2 space-y-1 absolute bg-white dark:bg-[#1a1a1a] shadow-lg rounded-md p-2 z-10 border border-gray-200 dark:border-[#3E3E3A] max-h-40 overflow-y-auto w-auto min-w-[150px]" {{-- Use dark-card bg --}}
                                         @click.outside="open = false" x-cloak>
                                        @foreach ($product->stockCombinations as $combination)
                                            <div class="flex items-center justify-between space-x-3 text-xs py-1">
                                                {{-- Using reference badge style --}}
                                                <span class="px-1.5 py-0.5 rounded-full bg-gray-200 dark:bg-[#2d2d2d] text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap"> {{-- Use dark-subcard bg --}}
                                                    {{ strtoupper($combination->size->name ?? 'N/A') }} / {{ $combination->color->name ?? 'N/A' }}
                                                </span>
                                                <span class="text-gray-500 dark:text-gray-400 font-semibold whitespace-nowrap">
                                                    {{ $combination->stock }} pcs
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                             {{-- Actions matching reference style --}}
                            <div class="flex gap-2 flex-wrap justify-center"> {{-- Added justify-center --}}
                                <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm" data-turbo="false">
                                    View
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm" data-turbo="false">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline delete-form">
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
                        <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400"> {{-- Updated colspan --}}
                            Tidak ada data produk.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

         {{-- Pagination matching reference style --}}
         @if ($products->hasPages())
         <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-0">
             {{-- Pagination Links --}}
             <div class="flex items-center space-x-1">
                 @if ($products->onFirstPage())
                     <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed"><</span>
                 @else
                     <a href="{{ $products->previousPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition" data-turbo-frame="products_frame"><</a> {{-- Added turbo frame --}}
                 @endif

                 {{-- Generate links using elements array to handle '...' correctly --}}
                 @foreach ($products->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                             @php $url = request('search') ? $url . '&search=' . request('search') : $url; @endphp {{-- Append search query --}}
                             @if ($page == $products->currentPage())
                                <span class="px-3 py-1 rounded-md bg-pink-500 text-white font-semibold">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition" data-turbo-frame="products_frame">{{ $page }}</a> {{-- Added turbo frame --}}
                            @endif
                        @endforeach
                    @endif
                @endforeach


                 @if ($products->hasMorePages())
                      <a href="{{ $products->nextPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition" data-turbo-frame="products_frame">></a> {{-- Added turbo frame --}}
                 @else
                      <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed">></span>
                 @endif
             </div>

             {{-- Pagination Summary --}}
             <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
                 Showing page <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->currentPage() }}</span>
                 of <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->lastPage() }}</span>
                 (Total: <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->total() }}</span> products)
             </div>
         </div>
         @endif

    </div> {{-- End inner container --}}

</turbo-frame>
