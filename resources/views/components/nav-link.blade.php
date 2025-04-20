@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-2 py-1 border-b-2 border-pink-500 dark:border-pink-400 text-sm font-semibold leading-5 text-gray-900 dark:text-white tracking-wide focus:outline-none focus:border-pink-600 transition duration-200 ease-in-out'
    : 'inline-flex items-center px-2 py-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-gray-500 dark:text-gray-400 tracking-wide hover:text-gray-800 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:border-gray-400 dark:focus:border-gray-600 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
