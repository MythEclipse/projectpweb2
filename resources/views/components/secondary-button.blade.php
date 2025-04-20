<button {{ $attributes->merge([
    'type' => 'button',
    'class' => '
        inline-flex items-center justify-center
        px-5 py-3 rounded-lg
        font-semibold text-sm tracking-wide
        bg-pink-600 text-white
        hover:bg-pink-700
        dark:bg-pink-500 dark:hover:bg-pink-600
        focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900
        disabled:opacity-50
        transition ease-in-out duration-150
    '
]) }}>
    {{ $slot }}
</button>
