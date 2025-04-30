<x-app-layout>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 bg-[#FDFDFC] dark:bg-dark-bg shadow rounded-2xl">
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Product List
                </h2>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-pink-brand hover:bg-pink-brand-dark text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                    + New Product
                </a>
            </div>
        </x-slot>

        <turbo-frame id="products_frame">
            @include('admin.products._list', ['products' => $products])
        </turbo-frame>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("turbo:load", () => {
                // Tambahkan data-turbo-frame ke link pagination
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
