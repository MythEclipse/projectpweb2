@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-4 pe-5 py-3 border-l-4 border-pink-500 dark:border-pink-400 text-start text-base font-semibold text-pink-700 dark:text-pink-300 bg-pink-50 dark:bg-pink-900/40 focus:outline-none focus:bg-pink-100 dark:focus:bg-pink-800/60 transition duration-150 ease-in-out'
    : 'block w-full ps-4 pe-5 py-3 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-pink-600 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:border-pink-400 dark:focus:border-pink-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
