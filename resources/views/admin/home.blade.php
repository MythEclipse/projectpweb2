<x-app-layout>
    <div class="p-6 bg-[#FDFDFC] dark:bg-[#0a0a0a] shadow rounded-2xl">
        <x-slot name="header">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-pink-600 dark:text-pink-400">Product List</h2>
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg shadow-md hover:scale-105 transition">
                    + New Product
                </a>
            </div>
        </x-slot>

        <turbo-frame id="products_frame">
            @include('admin.home._list', ['products' => $products])
        </turbo-frame>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("turbo:load", () => {
            // Tambahkan data-turbo-frame ke link pagination agar tidak refresh
            document.querySelectorAll('.pagination a').forEach(link => {
                link.setAttribute('data-turbo-frame', 'products_frame');
            });

            // SweetAlert konfirmasi delete
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ec4899',
                        cancelButtonColor: '#3E3E3A',
                        confirmButtonText: 'Yes, delete it!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
