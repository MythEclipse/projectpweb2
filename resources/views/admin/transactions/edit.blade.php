<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-text-dark dark:text-text-light">
                Edit Transaksi #{{ $transaction->id }}
            </h1>
            <a href="{{ route('admin.transactions.index') }}"
               class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-dark-subcard dark:hover:bg-dark-border text-text-dark dark:text-text-light px-4 py-2 rounded transition duration-150 ease-in-out">
                Batal
            </a>
        </div>

        {{-- Tampilkan Error Validasi Umum --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-200" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- atau PATCH --}}

            <div class="bg-white dark:bg-dark-card shadow-md rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kolom Kiri: Detail Produk & Kuantitas --}}
                <div>
                    <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2 text-text-dark dark:text-text-light">Detail Produk</h2>
                    <div class="space-y-4">
                         {{-- Produk --}}
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk</label>
                            <select name="product_id" id="product_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $transaction->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Ukuran --}}
                        <div>
                            <label for="size_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ukuran</label>
                            <select name="size_id" id="size_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ old('size_id', $transaction->size_id) == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                             @error('size_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Warna --}}
                        <div>
                            <label for="color_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warna</label>
                            <select name="color_id" id="color_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('color_id', $transaction->color_id) == $color->id ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                             @error('color_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Jumlah --}}
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $transaction->quantity) }}" min="1" required
                                   class="mt-1 focus:ring-pink-brand focus:border-pink-brand block w-full shadow-sm sm:text-sm border-gray-300 dark:border-dark-border rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                            @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Satuan (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $transaction->price) }}" min="0" step="any" required
                                   class="mt-1 focus:ring-pink-brand focus:border-pink-brand block w-full shadow-sm sm:text-sm border-gray-300 dark:border-dark-border rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                         {{-- Total (Display Only - Dihitung di Controller) --}}
                        <div>
                             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Harga (Otomatis)</label>
                             <p class="mt-1 text-sm text-gray-900 dark:text-text-light font-semibold">
                                 Rp {{ number_format($transaction->total, 0, ',', '.') }}
                             </p>
                             <p class="text-xs text-gray-500 dark:text-gray-400">Akan dihitung ulang saat disimpan jika harga atau jumlah diubah.</p>
                         </div>

                         {{-- User (Jika bisa diubah) --}}
                         {{--
                         <div>
                             <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                             <select name="user_id" id="user_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                 @foreach($users as $user)
                                     <option value="{{ $user->id }}" {{ old('user_id', $transaction->user_id) == $user->id ? 'selected' : '' }}>
                                         {{ $user->name }} ({{ $user->email }})
                                     </option>
                                 @endforeach
                             </select>
                             @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                         </div>
                         --}}

                    </div>
                </div>

                {{-- Kolom Kanan: Status & Info Tambahan --}}
                <div>
                     <h2 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-dark-border pb-2 text-text-dark dark:text-text-light">Status & Informasi Lain</h2>
                     <div class="space-y-4">
                         {{-- Status Transaksi --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Transaksi</label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status', $transaction->status) == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                             @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                         {{-- Status Pembayaran --}}
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($paymentStatuses as $pStatus)
                                    <option value="{{ $pStatus }}" {{ old('payment_status', $transaction->payment_status) == $pStatus ? 'selected' : '' }}>
                                        {{ ucfirst($pStatus) }}
                                    </option>
                                @endforeach
                            </select>
                             @error('payment_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                         {{-- Status Pengiriman --}}
                        <div>
                            <label for="shipping_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status Pengiriman</label>
                            <select name="shipping_status" id="shipping_status" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-dark-border focus:outline-none focus:ring-pink-brand focus:border-pink-brand sm:text-sm rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                                @foreach($shippingStatuses as $sStatus)
                                    <option value="{{ $sStatus }}" {{ old('shipping_status', $transaction->shipping_status) == $sStatus ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $sStatus)) }}
                                    </option>
                                @endforeach
                            </select>
                             @error('shipping_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                         {{-- Nomor Pelacakan --}}
                        <div>
                            <label for="tracking_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Pelacakan (Resi)</label>
                            <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number', $transaction->tracking_number) }}"
                                   class="mt-1 focus:ring-pink-brand focus:border-pink-brand block w-full shadow-sm sm:text-sm border-gray-300 dark:border-dark-border rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light">
                            @error('tracking_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                         {{-- Catatan --}}
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea name="notes" id="notes" rows="4"
                                      class="mt-1 focus:ring-pink-brand focus:border-pink-brand block w-full shadow-sm sm:text-sm border-gray-300 dark:border-dark-border rounded-md bg-white dark:bg-dark-subcard text-text-dark dark:text-text-light"
                            >{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                 {{-- Tombol Aksi --}}
                <div class="md:col-span-2 mt-6 flex justify-end">
                     <a href="{{ route('admin.transactions.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-dark-subcard dark:hover:bg-dark-border text-text-dark dark:text-text-light py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 mr-3 transition duration-150 ease-in-out">
                         Batal
                     </a>
                    <button type="submit"
                            class="bg-pink-brand hover:bg-pink-brand-dark text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 dark:ring-offset-dark-card focus:ring-pink-brand-dark transition duration-150 ease-in-out">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
