@props(['product'])

<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-6">
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
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- Price -->
    <div>
        <x-input-label for="price" value="Price" />
        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full"
                      value="{{ old('price', $product->price ?? '') }}" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <!-- Image Upload -->
    <div>
        <x-input-label for="image" value="Product Image" />
        <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500" />
        <x-input-error :messages="$errors->get('image')" class="mt-2" />

        @if(isset($product) && $product->image)
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="h-32 object-cover rounded-md">
            </div>
        @endif
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
        <x-primary-button>
            {{ isset($product) ? 'Update' : 'Create' }}
        </x-primary-button>
    </div>
</form>
