<div class="flex flex-col md:flex-row items-center justify-between mt-6 space-y-4 md:space-y-0">
    <div class="flex items-center space-x-1 text-gray-600 dark:text-gray-400">
        @if ($products->onFirstPage())
            <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&lt;</span>
        @else
            <a href="{{ $products->previousPageUrl() }}"
                class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&lt;</a>
        @endif

        @for ($i = 1; $i <= $products->lastPage(); $i++)
            @if ($i == $products->currentPage())
                <span class="px-3 py-1 rounded-md bg-pink-500 text-white font-semibold">{{ $i }}</span>
            @else
                <a href="{{ $products->url($i) }}"
                    class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">{{ $i }}</a>
            @endif
        @endfor

        @if ($products->hasMorePages())
            <a href="{{ $products->nextPageUrl() }}"
                class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] hover:bg-gray-100 dark:hover:bg-[#1a1a1a]">&gt;</a>
        @else
            <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] opacity-50 cursor-not-allowed">&gt;</span>
        @endif
    </div>

    <div class="text-sm text-gray-600 dark:text-gray-400 text-center md:text-right">
        Showing page <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->currentPage() }}</span>
        of <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->lastPage() }}</span>
        (Total: <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->total() }}</span> products)
    </div>
</div>
