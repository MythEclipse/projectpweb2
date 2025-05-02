   {{-- Header Title --}}
   <x-slot name="header">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Daftar Produk
        </h2>
        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
            + Tambah Produk
        </a>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8"> {{-- Outer padding --}}

        {{-- Session Status / Alerts --}}
        <div class="px-4 sm:px-0">
            @if (session('success'))
                <div id="alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-200 rounded-md shadow-sm transition-opacity duration-500"
                     role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
             @if (session('error'))
                <div id="alert-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm transition-opacity duration-500"
                     role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
             @endif
             {{-- Display validation errors if needed --}}
             @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 rounded-md shadow-sm">
                    <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Actions: Search --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-0 mb-6">
            {{-- Search Form --}}
            <div class="relative w-full sm:w-auto flex-grow">
                <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center">
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
        <div class="space-y-6 px-4 sm:px-0"> {{-- Inner container matching reference --}}

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
                         {{-- $product is defined ONLY within this loop --}}
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                                {{-- Use px-4 py-4 for data cells --}}
                                <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td class="px-4 py-4 font-semibold break-words">{{ $product->name }}</td> {{-- Use $product --}}
                                <td class="px-4 py-4 hidden sm:table-cell text-pink-600 dark:text-pink-400 whitespace-nowrap">Rp{{ number_format($product->price, 0, ',', '.') }}</td> {{-- Use $product --}}
                                <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                    {{-- Variant Display Logic --}}
                                    @if ($product->stockCombinations->isEmpty()) {{-- Use $product --}}
                                        <span class="text-xs text-gray-500 dark:text-gray-400">No variants</span>
                                    @elseif ($product->stockCombinations->count() === 1) {{-- Use $product --}}
                                        @php $combination = $product->stockCombinations->first(); @endphp {{-- Use $product --}}
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300 text-xs font-medium w-fit">
                                                {{-- Check if relations exist before accessing properties --}}
                                                {{ strtoupper($combination->size->name ?? 'N/A') }} / {{ $combination->color->name ?? 'N/A' }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $combination->stock }} pcs
                                            </span>
                                        </div>
                                    @else
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open"
                                                    class="text-xs text-pink-600 dark:text-pink-400 hover:underline">
                                                Lihat ({{ $product->stockCombinations->count() }}) {{-- Use $product --}}
                                            </button>
                                            <div x-show="open"
                                                 x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                                 class="mt-2 space-y-1 absolute bg-white dark:bg-[#1a1a1a] shadow-lg rounded-md p-2 z-10 border border-gray-200 dark:border-[#3E3E3A] max-h-40 overflow-y-auto w-auto min-w-[150px]"
                                                 @click.outside="open = false" x-cloak>
                                                @foreach ($product->stockCombinations as $combination) {{-- Use $product --}}
                                                    <div class="flex items-center justify-between space-x-3 text-xs py-1">
                                                        <span class="px-1.5 py-0.5 rounded-full bg-gray-200 dark:bg-[#2d2d2d] text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                                             {{-- Check if relations exist --}}
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
                                    <div class="flex gap-2 flex-wrap justify-center">
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm"> {{-- Use $product --}}
                                            View
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm"> {{-- Use $product --}}
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline delete-form"> {{-- Use $product --}}
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- $product is NOT available here --}}
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    Tidak ada data produk ditemukan.
                                    @if(request('search'))
                                        <span class="block text-sm">Coba ubah kata kunci pencarian Anda.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse {{-- End of $product scope --}}
                    </tbody>
                </table>
            </div>

            {{-- Pagination matching reference style --}}
            {{-- Make sure $products is the paginator instance passed from controller --}}
            @if ($products->hasPages())
            <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                {{-- Pagination Links --}}
                <div class="flex items-center space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed"><</span>
                    @else
                        <a href="{{ $products->previousPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition"><</a>
                    @endif

                    {{-- Pagination Elements --}}
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
                                    <a href="{{ $url }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                     {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1a1a1a] transition">></a>
                    @else
                        <span class="px-3 py-1 rounded-md border border-gray-300 dark:border-[#3E3E3A] text-gray-500 dark:text-gray-600 opacity-50 cursor-not-allowed">></span>
                    @endif
                </div>

                {{-- Pagination Summary --}}
                <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-right">
                    Menampilkan halaman <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->currentPage() }}</span>
                    dari <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->lastPage() }}</span>
                    (Total: <span class="font-semibold text-pink-600 dark:text-pink-400">{{ $products->total() }}</span> produk)
                </div>
            </div>
            @endif

        </div> {{-- End inner container space-y-6 --}}
    </div> {{-- End max-w-7xl --}}
</div> {{-- End py-12 --}}

{{-- Scripts Section --}}
@push('scripts')
    {{-- SweetAlert CDN (ensure it's loaded, either here or globally) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Alpine JS (ensure it's loaded for x-data, x-show etc.) --}}
    {{-- If not loaded globally, add: <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <script>
        // Use DOMContentLoaded for standard page loads
        document.addEventListener("DOMContentLoaded", () => {

            // SweetAlert konfirmasi delete
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent normal form submission

                    const isDarkMode = document.documentElement.classList.contains('dark');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Tindakan ini tidak dapat diurungkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ec4899', // Pink-500
                        cancelButtonColor: '#6b7280', // Gray-500
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        background: isDarkMode ? '#1f2937' : '#ffffff', // Gray-800 or White
                        color: isDarkMode ? '#f3f4f6' : '#1f2937', // Gray-100 or Gray-800
                        customClass: { // Optional: for finer control if needed
                            // popup: '...',
                            // confirmButton: '...',
                            // cancelButton: '...'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If confirmed, submit the original form
                            this.submit();
                        }
                    });
                });
            });

             // Auto-hide alerts (requires AlpineJS)
             // Already handled by x-init and setTimeout in the alert divs themselves

        });
    </script>
@endpush

</x-app-layout>
