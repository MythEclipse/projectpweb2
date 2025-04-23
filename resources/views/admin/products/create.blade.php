<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Create Product</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8  bg-white dark:bg-[#1a1a1a] p-6 rounded shadow">
            @include('admin.products.form', ['product' => null])
        </div>
    </div>
</x-app-layout>
