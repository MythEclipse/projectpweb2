<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8  bg-white dark:bg-[#1a1a1a] p-6 rounded shadow">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-64 h-64 object-cover mb-4 rounded">
            @endif
            <p class="text-gray-700 mb-4">{{ $product->description }}</p>
            <p class="text-sm text-gray-600">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

            <div class="mt-6">
                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline mr-4">Edit</a>
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">← Back to Products</a>
            </div>
        </div>
    </div>
</x-app-layout>
