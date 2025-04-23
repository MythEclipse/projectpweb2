@props(['product'])

<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-6 p-6 transition-all">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <!-- Name -->
    <div>
        <x-input-label for="name" value="Product Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                      value="{{ old('name', $product->name ?? '') }}" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Description -->
    <div>
        <x-input-label for="description" value="Description" />
        <textarea name="description" id="description" rows="4"
                  class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2d2d2d] text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 transition">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- Price -->
    <div>
        <x-input-label for="price" value="Price" />
        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                      value="{{ old('price', $product->price ?? '') }}" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <!-- Size -->
    <div>
        <x-input-label for="size" value="Size" />
        <select name="size" id="size" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2d2d2d] text-gray-900 dark:text-white shadow-sm focus:ring-pink-500 focus:border-pink-500">
            <option value="">-- Select Size --</option>
            @foreach(['s', 'm', 'l', 'xl', 'xxl'] as $size)
                <option value="{{ $size }}" @selected(old('size', $product->size ?? '') === $size)>{{ strtoupper($size) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('size')" class="mt-2" />
    </div>

    <!-- Color -->
    <div>
        <x-input-label for="color" value="Color" />
        <x-text-input id="color" name="color" type="text" class="mt-1 block w-full"
                      value="{{ old('color', $product->color ?? '') }}" />
        <x-input-error :messages="$errors->get('color')" class="mt-2" />
    </div>

    <!-- Stock -->
    <div>
        <x-input-label for="stock" value="Stock Quantity" />
        <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full"
                      value="{{ old('stock', $product->stock ?? 0) }}" />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>

    <!-- Image Upload -->
    <div>
        <x-input-label for="image" value="Product Image" />
        <x-input-file id="image" name="image" label="Product Image" />
        <x-input-error :messages="$errors->get('image')" class="mt-2" />

        @if(isset($product) && $product->image)
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                     class="h-32 object-cover rounded-md border border-gray-200 dark:border-gray-700 shadow-sm" />
            </div>
        @endif
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
        <button type="submit"
                class="inline-flex items-center px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-lg shadow-md transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
            {{ isset($product) ? 'Update' : 'Create' }}
        </button>
    </div>
</form>
