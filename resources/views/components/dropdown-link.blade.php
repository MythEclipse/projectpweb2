<a {{ $attributes->merge([
    'class' => '
        block w-full px-4 py-2 text-start text-sm font-medium
        text-gray-700 dark:text-gray-300
        hover:bg-pink-50 dark:hover:bg-[#3E3E3A]
        focus:outline-none focus:bg-pink-100 dark:focus:bg-[#3E3E3A]
        transition duration-150 ease-in-out
        rounded-md
    ']) }}>
    {{ $slot }}
</a>
