@props(['product', 'sizes', 'colors'])

<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-6 p-6 transition-all"
      x-data="{
          selectedSizes: {{ Js::from(old('sizes', isset($product) ? $product->sizes->pluck('id')->toArray() : [])) }}
      }">
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

    <!-- Sizes & Colors with Stock Input -->
    <div>
        <x-input-label for="sizes_colors" value="Available Sizes, Colors & Stock" />
        <div class="grid grid-cols-1 gap-4 mt-2">
            @foreach ($sizes as $size)
                <div class="border p-4 rounded-md">
                    <!-- Size Selection -->
                    <div class="flex items-center space-x-2 mb-4">
                        <input type="checkbox"
                               name="sizes[]"
                               value="{{ $size->id }}"
                               @change="if($event.target.checked) { selectedSizes.push({{ $size->id }}) } else { selectedSizes = selectedSizes.filter(id => id !== {{ $size->id }}) }"
                               :checked="selectedSizes.includes({{ $size->id }})"
                               class="rounded border-gray-300 dark:border-gray-600 text-pink-600 shadow-sm focus:ring-pink-500" />
                        <label class="text-gray-800 dark:text-gray-100 font-medium">{{ strtoupper($size->name) }}</label>
                    </div>

                    <!-- Colors for Selected Size -->
                    <div class="ml-6 space-y-2">
                        @foreach ($colors as $color)
                            <div class="flex items-center space-x-4">
                                <span class="text-gray-600 dark:text-gray-300">{{ strtoupper($color->name) }}</span>
                                <div x-show="selectedSizes.includes({{ $size->id }})" x-transition class="flex-1">
                                    <input type="number"
                                           name="stocks[{{ $size->id }}-{{ $color->id }}]"
                                           id="stock_{{ $size->id }}_{{ $color->id }}"
                                           min="0"
                                           class="block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2d2d2d] text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                                           value="{{ old('stocks.' . $size->id . '-' . $color->id, isset($product) ? ($product->sizes->find($size->id)?->pivot->colors->find($color->id)?->pivot->stock ?? 0) : 0) }}"
                                           placeholder="Stock">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('stocks')" class="mt-2" />
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
