<div class="relative mb-4">
    <form @submit.prevent class="flex">
        <input type="text" x-model="search"
            class="w-full border border-gray-300 dark:border-[#3E3E3A] rounded-md py-2 pl-4 pr-10 focus:ring-pink-500 focus:border-pink-500 dark:bg-[#0a0a0a] dark:text-white"
            placeholder="Search products...">
        <button type="submit"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M16.65 16.65a7 7 0 1 0-9.9-9.9 7 7 0 0 0 9.9 9.9z" />
            </svg>
        </button>
    </form>
</div>
