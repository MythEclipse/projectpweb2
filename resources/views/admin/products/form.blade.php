@props(['product'])

<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" class="space-y-6">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div>
        <x-input-label for="name" value="Product Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $product->name ?? '') }}" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" value="Price" />
        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('price', $product->price ?? '') }}" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div class="flex justify-end">
        <x-primary-button>
            {{ isset($product) ? 'Update' : 'Create' }}
        </x-primary-button>
    </div>
</form>
