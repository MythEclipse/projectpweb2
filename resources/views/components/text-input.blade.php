@props([
    'disabled' => false,
    'min' => null,
    'max' => null,
    'step' => null,
    'type' => 'text',
])

<input
    type="{{ $type }}"
    @if(!is_null($min)) min="{{ $min }}" @endif
    @if(!is_null($max)) max="{{ $max }}" @endif
    @if(!is_null($step)) step="{{ $step }}" @endif
    @disabled($disabled)
    {{ $attributes->merge([
        'class' => '
            block w-full px-4 py-2
            border border-gray-200 dark:border-[#3E3E3A]
            bg-[#FDFDFC] dark:bg-[#1a1a1a]
            text-[#1b1b18] dark:text-[#EDEDEC]
            focus:border-pink-600 dark:focus:border-pink-500
            focus:ring-2 focus:ring-pink-500 dark:focus:ring-pink-500
            rounded-lg shadow-sm
            disabled:bg-gray-100 dark:disabled:bg-[#2d2d2d]
            disabled:opacity-70
            transition duration-150 ease-in-out
        '
    ]) }}
>
