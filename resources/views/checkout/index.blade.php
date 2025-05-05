{{-- resources/views/checkout/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success') || session('error') || $errors->any())
                 @if (session('success'))
                     <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                         <span class="block sm:inline">{{ session('success') }}</span>
                     </div>
                 @endif
                 @if (session('error'))
                     <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                         <span class="block sm:inline">{{ session('error') }}</span>
                     </div>
                 @endif
                 {{-- Menampilkan error validasi dari checkout --}}
                 @if ($errors->any())
                    <div class="rounded-xl p-4 shadow-md bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700/50 text-red-800 dark:text-red-200 mb-4" role="alert">
                         <h3 class="text-sm font-semibold">Terdapat {{ $errors->count() }} {{ Str::plural('kesalahan', $errors->count()) }}:</h3>
                         <div class="mt-2 text-sm">
                             <ul role="list" class="list-disc space-y-1 pl-5">
                                 @foreach ($errors->all() as $error)
                                     <li>{{ $error }}</li>
                                 @endforeach
                             </ul>
                         </div>
                    </div>
                 @endif
            @endif

            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border p-6 md:p-8">

                <form method="POST" action="{{ route('checkout.process') }}">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Kolom Kiri: Ringkasan Pesanan --}}
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-semibold mb-4 text-text-dark dark:text-text-light">Ringkasan Pesanan</h3>

                            <div class="space-y-4 border-b border-gray-200 dark:border-dark-border pb-4 mb-4">
                                @foreach ($cartItems as $item)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden mr-4">
                                            @if ($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                 {{-- Placeholder image --}}
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <p class="text-sm font-medium text-text-dark dark:text-text-light">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->size ? $item->size->name : '' }}{{ $item->size && $item->color ? ' / ' : '' }}{{ $item->color ? $item->color->name : '' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right text-sm font-medium text-gray-900 dark:text-text-light">
                                            Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between text-lg font-semibold text-text-dark dark:text-text-light">
                                <span>Total</span>
                                <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </div>
                            {{-- Anda bisa menambahkan estimasi ongkir di sini --}}

                        </div>

                        {{-- Kolom Kanan: Detail Pengiriman & Pembayaran --}}
                        <div>
                            <h3 class="text-xl font-semibold mb-4 text-text-dark dark:text-text-light">Detail Pengiriman</h3>

                            {{-- Form Alamat Pengiriman --}}
                            <div class="space-y-4">
                                {{-- Ini adalah contoh sederhana. Anda mungkin perlu lebih banyak field (nama, telp, provinsi, kota, dll) --}}
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Alamat Lengkap</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="4" required
                                              class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('shipping_address') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">{{ old('shipping_address', $userAddress ? $userAddress->full_address : '') }}</textarea> {{-- Asumsikan $userAddress adalah model Address dengan field full_address --}}
                                    @error('shipping_address') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                {{-- Form Catatan (Opsional) --}}
                                <div>
                                     <label for="notes" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Catatan Pesanan (Opsional)</label>
                                     <textarea id="notes" name="notes" rows="3" placeholder="Tambahkan catatan untuk penjual..."
                                               class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('notes') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">{{ old('notes') }}</textarea>
                                     @error('notes') <p class="text-red-500 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
                                 </div>

                                 {{-- Form Metode Pembayaran (Contoh placeholder) --}}
                                 {{-- Anda perlu mengembangkan bagian ini --}}
                                 <div class="pt-4 border-t border-gray-200 dark:border-dark-border">
                                     <h4 class="text-lg font-semibold mb-3 text-text-dark dark:text-text-light">Metode Pembayaran</h4>
                                     <p class="text-sm text-gray-600 dark:text-gray-400">Opsi pembayaran akan diimplementasikan di sini.</p>
                                     {{-- Contoh: radio buttons, bank transfer info, link ke payment gateway --}}
                                      {{-- <input type="hidden" name="payment_method" value="bank_transfer"> --}}
                                 </div>
                            </div>


                            {{-- Tombol Proses Checkout --}}
                            <div class="mt-8">
                                <button type="submit" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                    Proses Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div> {{-- End Card Utama --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}
</x-app-layout>
