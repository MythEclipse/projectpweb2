<div class="p-6 bg-white dark:bg-[#0a0a0a] shadow rounded-2xl" data-turbo="false">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Product List</h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + New Product
            </a>
        </div>
    </x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-pink-600 dark:text-pink-400">Product List</h2>
        <input type="text" wire:model="search" placeholder="Search products..."
            class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-sm border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-500" />
    </div>

    <table class="min-w-full bg-white dark:bg-[#0a0a0a] text-sm text-left">
        <thead>
            <tr class="border-b border-gray-300 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-900">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->price }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-3">
                            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>

@push('scripts')
    <script>
        // Menambahkan event listener untuk tombol delete
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah form submit otomatis
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Submit form jika konfirmasi
                    }
                });
            });
        });
    </script>
@endpush
