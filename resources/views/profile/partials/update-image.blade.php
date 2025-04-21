<form action="{{ route('profile.updateImage') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="space-y-2">
        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
            Upload New Profile Image
        </label>
        <input
            type="file"
            name="image"
            id="image"
            required
            class="block w-full text-sm text-gray-900 dark:text-gray-300 bg-gray-50 dark:bg-[#2A2A2A] border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 transition"
        >
    </div>

    <div class="text-right mt-3">
        <button type="submit"
                class="inline-flex items-center px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-xl shadow transition-colors focus:outline-none focus:ring-2 focus:ring-pink-400">
            {{ __('Save') }}
        </button>
    </div>
</form>
