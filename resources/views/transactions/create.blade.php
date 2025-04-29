<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">
        <h1 class="text-xl font-bold mb-4">Tambah Transaksi</h1>

        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Produk</label>
                <select name="product_id" class="w-full border rounded p-2">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Ukuran</label>
                <select name="size_id" class="w-full border rounded p-2">
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Warna</label>
                <select name="color_id" class="w-full border rounded p-2">
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Jumlah</label>
                <input type="number" name="quantity" class="w-full border rounded p-2" min="1" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Harga Satuan</label>
                <input type="number" name="price" step="0.01" class="w-full border rounded p-2" required>
            </div>

            <button type="submit" class="bg-pink-brand text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>
