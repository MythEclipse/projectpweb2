<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-[#1a1a1a] p-8 rounded-2xl shadow-2xl space-y-10 transition-all">

                {{-- Product Image --}}
                @if ($product->image)
                    <div class="flex justify-center">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-72 h-72 object-cover rounded-2xl border border-gray-200 dark:border-[#3E3E3A] shadow-md hover:scale-105 transition-transform duration-300" />
                    </div>
                @endif

                {{-- Description + Price --}}
                <div class="space-y-4 text-center">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $product->description }}
                    </p>
                    <p class="text-3xl font-bold text-pink-600 dark:text-pink-400">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Stock per Size & Color --}}
                <div class="space-y-8">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 text-center mb-6">
                        Stock per Size & Color
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($product->stockCombinations->groupBy('size_id') as $sizeId => $combos)
                            <div class="bg-gray-50 dark:bg-[#2d2d2d] p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all">
                                <div class="inline-block bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300 px-4 py-1 rounded-full font-semibold mb-4">
                                    {{ strtoupper($combos->first()->size->name) }}
                                </div>

                                <ul class="space-y-3">
                                    @foreach($combos as $comb)
                                        <li class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-full border border-gray-300 dark:border-[#3E3E3A]"
                                                      style="background-color: {{ $comb->color->code }};"></span>
                                                <span class="text-sm text-gray-700 dark:text-gray-300 capitalize">
                                                    {{ $comb->color->name }}
                                                </span>
                                            </div>
                                            <span class="text-sm font-semibold text-green-600">
                                                {{ $comb->stock }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>

                    @if($product->stockCombinations->isEmpty())
                        <p class="mt-6 text-center text-gray-500 dark:text-gray-400">
                            Belum ada stock tersedia.
                        </p>
                    @endif
                </div>

                {{-- Action Button --}}
                <div class="flex justify-center">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-xl shadow-md transition-transform hover:scale-105">
                        ✏️ Edit Produk
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
