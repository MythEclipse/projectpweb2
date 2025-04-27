<div class="bg-white dark:bg-[#2d2d2d] rounded-2xl shadow p-4 flex flex-col items-start hover:shadow-lg transition w-full h-[300px]"
    x-show="filterProduct('{{ strtolower($product->name) }}')">

    <div class="h-32 w-full bg-gray-100 dark:bg-gray-800 rounded-xl mb-4 flex items-center justify-center text-gray-400">
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                class="object-cover h-full w-full rounded-xl">
        @else
            <span>No Image</span>
        @endif
    </div>

    <h3 class="text-md font-semibold text-gray-800 dark:text-gray-100 mb-1">{{ $product->name }}</h3>
    <p class="text-pink-600 dark:text-pink-400 font-bold mb-1">
        Rp {{ number_format($product->price, 0, ',', '.') }}
    </p>
    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
        {{ $product->description ?? 'No description' }}
    </p>

    <div class="mt-auto w-full flex space-x-2">
        <button @click="openModal({{ json_encode($product) }})"
            class="flex-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-xl text-xs">
            Detail
        </button>
        <button @click="openBuyModal({{ json_encode($product) }})"
            class="bg-pink-600 hover:bg-pink-700 text-white text-xs rounded-lg transition-transform hover:scale-105">
            Beli Sekarang
        </button>
    </div>
</div>
