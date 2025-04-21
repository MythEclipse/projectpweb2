<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Product List</h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + New Product
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @foreach ($products as $product)
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $product->description }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">View</a>
                        <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete product?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
