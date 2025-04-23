@props([
    'id' => 'file',
    'name' => 'file',
    'required' => false,
])

<div>
    <input
        type="file"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-pink-600 file:text-white
                        hover:file:bg-pink-700
                        bg-white dark:bg-[#2d2d2d] text-gray-700 dark:text-gray-200
                        border border-gray-300 dark:border-gray-600 rounded-md shadow-sm transition']) }}
    />
</div>
