<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white dark:bg-[#1a1a1a] p-6 rounded shadow space-y-4">

            {{-- Image --}}
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-64 h-64 object-cover mb-4 rounded border border-gray-300 dark:border-gray-700">
            @endif

            {{-- Description --}}
            <p class="text-gray-700 dark:text-gray-300 mb-2">{{ $product->description }}</p>

            {{-- Price --}}
            <p class="text-lg font-semibold text-pink-600 dark:text-pink-400">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </p>

            {{-- Size, Color, Stock --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600 dark:text-gray-400 mt-4">
                <div>
                    <span class="font-semibold">Size:</span> {{ strtoupper($product->size ?? '-') }}
                </div>
                <div>
                    <span class="font-semibold">Color:</span> {{ $product->color ?? '-' }}
                </div>
                <div>
                    <span class="font-semibold">Stock:</span> {{ $product->stock ?? 0 }}
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-6">
                <a href="{{ route('products.edit', $product) }}"
                   class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
