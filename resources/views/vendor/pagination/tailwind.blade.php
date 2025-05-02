@if ($paginator->hasPages())
    {{-- Container Utama Pagination --}}
    <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-0">

        {{-- Wadah untuk Link Halaman (Tombol Previous, Nomor, Tombol Next) --}}
        <div class="flex items-center space-x-1">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                {{-- Tombol Disabled --}}
                <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed"><</span>
            @else
                {{-- Tombol Aktif --}}
                <a href="{{ $paginator->previousPageUrl() . (request('search') ? '&search='.request('search') : '') }}"
                   class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-text-dark dark:text-text-light hover:bg-gray-100 dark:hover:bg-dark-card transition"
                   data-turbo-frame="products_frame" {{-- Sesuaikan 'products_frame' jika perlu --}}
                   rel="prev"
                   aria-label="{{ __('pagination.previous') }}"><</a>
            @endif

            {{-- Elemen Pagination (Nomor Halaman dan Elipsis) --}}
            @foreach ($elements as $element)
                {{-- Elipsis "..." --}}
                @if (is_string($element))
                    <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-gray-500 dark:text-gray-600 opacity-50">{{ $element }}</span>
                @endif

                {{-- Array Link Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        {{-- Tambahkan parameter 'search' jika ada --}}
                        @php $url = request('search') ? $url . '&search=' . request('search') : $url; @endphp

                        @if ($page == $paginator->currentPage())
                            {{-- Halaman Aktif --}}
                            <span class="px-3 py-1 rounded-md bg-pink-brand text-white font-semibold" aria-current="page">{{ $page }}</span>
                        @else
                            {{-- Link Halaman Lain --}}
                            <a href="{{ $url }}"
                               class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-text-dark dark:text-text-light hover:bg-gray-100 dark:hover:bg-dark-card transition"
                               data-turbo-frame="products_frame" {{-- Sesuaikan 'products_frame' jika perlu --}}
                               aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                {{-- Tombol Aktif --}}
                <a href="{{ $paginator->nextPageUrl() . (request('search') ? '&search='.request('search') : '') }}"
                   class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-text-dark dark:text-text-light hover:bg-gray-100 dark:hover:bg-dark-card transition"
                   data-turbo-frame="products_frame" {{-- Sesuaikan 'products_frame' jika perlu --}}
                   rel="next"
                   aria-label="{{ __('pagination.next') }}">></a>
            @else
                {{-- Tombol Disabled --}}
                <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-dark-border text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed">></span>
            @endif
        </div>

        {{-- Ringkasan Pagination --}}
        <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
            Showing page <span class="font-semibold text-pink-brand dark:text-pink-brand">{{ $paginator->currentPage() }}</span>
            of <span class="font-semibold text-pink-brand dark:text-pink-brand">{{ $paginator->lastPage() }}</span>
            (Total: <span class="font-semibold text-pink-brand dark:text-pink-brand">{{ $paginator->total() }}</span> results)
        </div>

    </div>
@endif
