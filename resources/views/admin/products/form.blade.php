@props(['product', 'sizes', 'colors'])

<form
    action="{{ isset($product)
        ? route('products.update', $product)
        : route('products.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6 p-6 transition-all"
    x-data="{
      selectedSizes: {{ Js::from(
        old(
          'sizes',
          isset($product)
            ? $product->stockCombinations->pluck('size_id')->unique()->toArray()
            : []
        )
      ) }}
    }"
>
    @csrf
    @if(isset($product)) @method('PUT') @endif

    {{-- Name --}}
    <div>
        <x-input-label for="name" value="Product Name" />
        <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('name', $product->name ?? '') }}"
            required
            autofocus
        />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- Description --}}
    <div>
        <x-input-label for="description" value="Description" />
        <textarea
            id="description"
            name="description"
            rows="4"
            class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2d2d2d] text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 transition"
            required
        >{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    {{-- Price --}}
    <div>
        <x-input-label for="price" value="Price" />
        <x-text-input
            id="price"
            name="price"
            type="number"
            step="0.01"
            min="0"
            class="mt-1 block w-full"
            value="{{ old('price', $product->price ?? '') }}"
            required
        />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    {{-- Sizes & Colors & Stock --}}
    <div>
        <x-input-label value="Available Sizes, Colors & Stock" />

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-2">
            @foreach ($sizes as $size)
                <label class="cursor-pointer">
                    <input
                        type="checkbox"
                        name="sizes[]"
                        x-model.number="selectedSizes"
                        value="{{ $size->id }}"
                        class="hidden"
                    />

                    <div
                        class="border p-3 rounded-lg transition-all"
                        :class="{
                            'border-pink-500 bg-pink-50 dark:bg-pink-900/20': selectedSizes.includes({{ $size->id }}),
                            'hover:border-pink-300 dark:hover:border-pink-600': !selectedSizes.includes({{ $size->id }})
                        }"
                    >
                        <div class="flex flex-col items-center space-y-1">
                            <span class="font-bold text-lg text-gray-800 dark:text-gray-200">
                                {{ strtoupper($size->name) }}
                            </span>
                            <span
                                class="text-xs text-pink-600 dark:text-pink-400"
                                x-text="selectedSizes.includes({{ $size->id }}) ? 'Selected' : 'Click to select'"
                            ></span>
                        </div>

                        <div x-show="selectedSizes.includes({{ $size->id }})" x-transition class="mt-4">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($colors as $color)
                                    @php
                                        $rec = isset($product)
                                            ? $product->stockCombinations
                                                ->firstWhere(fn($c) =>
                                                    $c->size_id == $size->id &&
                                                    $c->color_id == $color->id
                                                )
                                            : null;
                                    @endphp

                                    <div class="space-y-1 text-center">
                                        <span class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                            {{ strtoupper($color->name) }}
                                        </span>
                                        <div class="flex justify-center items-center space-x-1">
                                            <span
                                                class="inline-block w-4 h-4 rounded-full border border-gray-300 dark:border-gray-600"
                                                style="background-color: {{ $color->code }};"
                                            ></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $color->code }}
                                            </span>
                                        </div>
                                        <input
                                            type="number"
                                            name="stocks[{{ $size->id }}-{{ $color->id }}]"
                                            min="0"
                                            class="w-full px-2 py-1 text-sm text-center border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#2d2d2d] rounded-md focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition"
                                            value="{{ old(
                                                'stocks.' . $size->id . '-' . $color->id,
                                                $rec?->stock ?? ''
                                            ) }}"
                                            placeholder="0"
                                            required
                                        />
                                        <x-input-error :messages="$errors->get('stocks.' . $size->id . '-' . $color->id)" class="mt-1 text-xs" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>

        <x-input-error :messages="$errors->get('sizes')" class="mt-2" />
        <x-input-error :messages="$errors->get('stocks')" class="mt-2" />
    </div>

    {{-- Image --}}
    <div>
        <x-input-label for="image" value="Product Image" />
        <x-input-file id="image" name="image" label="Choose Image" :required="!isset($product)" />
        <x-input-error :messages="$errors->get('image')" class="mt-2" />

        @if(isset($product) && $product->image)
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    Current Image:
                </p>
                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="Product Image"
                    class="h-32 object-cover rounded-md border border-gray-200 dark:border-gray-700 shadow-sm"
                />
            </div>
        @endif
    </div>

    {{-- Submit --}}
    <div class="flex justify-end">
        <button
            type="submit"
            class="inline-flex items-center px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-lg shadow-md transition-transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500"
        >
            {{ isset($product) ? 'Update' : 'Create' }}
        </button>
    </div>
</form>
