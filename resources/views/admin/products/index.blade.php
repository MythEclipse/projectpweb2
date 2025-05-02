<x-app-layout>
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
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">

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
                <form method="GET" action="{{ route('admin.products.index') }}" class="relative w-full sm:w-auto flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}"
                    class="flex-grow border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white w-full" {{-- Added w-full for better mobile --}}
                           placeholder="Cari produk...">
                    <button type="submit" title="Cari"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Table Section --}}
            <div class="space-y-6 px-4 sm:px-0">
                <div class="overflow-x-auto bg-white dark:bg-[#0a0a0a] rounded-xl shadow">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-[#3E3E3A]">
                        <thead class="bg-gray-100 dark:bg-[#1a1a1a]">
                            <tr class="text-gray-600 dark:text-gray-300">
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">#</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden sm:table-cell">Harga</th>
                                <th class="px-4 py-3 text-left uppercase tracking-wider hidden md:table-cell">Varian</th>
                                <th class="px-4 py-3 text-center uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-gray-100 dark:divide-[#2d2d2d] text-gray-700 dark:text-gray-300">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition">
                                    <td class="px-4 py-4 hidden sm:table-cell whitespace-nowrap">
                                        {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold break-words">{{ $product->name }}</td>
                                    <td class="px-4 py-4 hidden sm:table-cell text-pink-600 dark:text-pink-400 whitespace-nowrap">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 hidden md:table-cell whitespace-nowrap">
                                        @if ($product->stockCombinations->isEmpty())
                                            <span class="text-xs italic text-gray-400">Tidak ada varian</span>
                                        @elseif ($product->stockCombinations->count() === 1)
                                            @php $comb = $product->stockCombinations->first(); @endphp
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300 text-xs font-medium w-fit">
                                                    {{ strtoupper($comb->size->name ?? 'N/A') }} / {{ $comb->color->name ?? 'N/A' }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $comb->stock }} pcs
                                                </span>
                                            </div>
                                        @else
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open"
                                                        class="text-xs text-pink-600 dark:text-pink-400 hover:underline">
                                                    Lihat ({{ $product->stockCombinations->count() }})
                                                </button>
                                                <div x-show="open"
                                                     x-transition
                                                     @click.outside="open = false"
                                                     x-cloak
                                                     class="mt-2 space-y-1 absolute bg-white dark:bg-[#1a1a1a] shadow-lg rounded-md p-2 z-50 border border-gray-200 dark:border-[#3E3E3A] max-h-40 overflow-y-auto w-auto min-w-[150px]">
                                                    @foreach ($product->stockCombinations as $comb)
                                                        <div class="flex items-center justify-between space-x-3 text-xs py-1">
                                                            <span class="px-1.5 py-0.5 rounded-full bg-gray-200 dark:bg-[#2d2d2d] text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                                                {{ strtoupper($comb->size->name ?? 'N/A') }} / {{ $comb->color->name ?? 'N/A' }}
                                                            </span>
                                                            <span class="text-gray-500 dark:text-gray-400 font-semibold whitespace-nowrap">
                                                                {{ $comb->stock }} pcs
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex gap-2 flex-wrap justify-center">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                View
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:underline text-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        Tidak ada data produk ditemukan.
                                        @if(request('search'))
                                            <span class="block text-sm">Coba ubah kata kunci pencarian Anda.</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($products->hasPages())
                    <div class="flex flex-wrap justify-center sm:justify-between items-center mt-6 gap-2 px-4 sm:px-0">
                        <div>
                            {{ $products->appends(request()->query())->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scripts Section --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                e.preventDefault();

                const isDarkMode = document.documentElement.classList.contains('dark');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tindakan ini tidak dapat diurungkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EC4899', // pink-brand
                    cancelButtonColor: '#6b7280', // neutral gray
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    background: isDarkMode ? '#0a0a0a' : '#ffffff', // dark-bg or white
                    color: isDarkMode ? '#EDEDEC' : '#1b1b18', // text-light or text-dark
                }).then((result) => {
                    if (result.isConfirmed) {
                    this.submit();
                    }
                });
                });
            });
            });
        </script>
    @endpush

</x-app-layout>
