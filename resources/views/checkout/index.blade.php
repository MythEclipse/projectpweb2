{{-- resources/views/checkout/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-12 md:py-16">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Umum (dari redirect, misalnya keranjang kosong) --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            {{-- Notifikasi Error Umum (dari redirect) --}}
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Area untuk menampilkan error validasi dari AJAX --}}
            <div id="validation-errors" class="hidden rounded-xl p-4 shadow-md bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700/50 text-red-800 dark:text-red-200 mb-4" role="alert">
                 <h3 class="text-sm font-semibold">Terdapat kesalahan:</h3>
                 <div class="mt-2 text-sm">
                     <ul id="validation-errors-list" role="list" class="list-disc space-y-1 pl-5">
                         {{-- Errors akan di-inject di sini oleh JavaScript --}}
                     </ul>
                 </div>
            </div>
             {{-- Area untuk menampilkan error umum dari AJAX --}}
             <div id="general-error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                 <span id="general-error-message" class="block sm:inline"></span>
             </div>


            <div class="bg-white dark:bg-dark-card overflow-hidden shadow-xl rounded-2xl border border-gray-200 dark:border-dark-border p-6 md:p-8">

                {{-- Form tetap ada untuk menampung input, tapi submitnya akan di-handle JS --}}
                <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Kolom Kiri: Ringkasan Pesanan (Tetap sama) --}}
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
                                                 <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs">No Image</div>
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

                            <div class="space-y-4">
                                {{-- Form Alamat Pengiriman --}}
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Alamat Lengkap</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="4" required
                                              class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('shipping_address') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">{{ old('shipping_address', $userAddress ? $userAddress->full_address : '') }}</textarea>
                                    {{-- Error message ditampilkan di sini oleh JS --}}
                                    <p id="shipping_address-error" class="text-red-500 dark:text-red-400 text-xs mt-1.5"></p>
                                </div>

                                {{-- Form Catatan (Opsional) --}}
                                <div>
                                     <label for="notes" class="block text-sm font-medium mb-1.5 text-text-dark dark:text-text-light/90">Catatan Pesanan (Opsional)</label>
                                     <textarea id="notes" name="notes" rows="3" placeholder="Tambahkan catatan untuk penjual..."
                                               class="block w-full p-2.5 border rounded-lg shadow-sm dark:bg-dark-subcard dark:text-text-light focus:ring-pink-brand focus:border-pink-brand text-sm {{ $errors->has('notes') ? 'border-red-500 ring-1 ring-red-500 dark:border-red-500' : 'border-gray-300 dark:border-dark-border dark:focus:border-pink-brand focus:border-pink-brand' }}">{{ old('notes') }}</textarea>
                                      {{-- Error message ditampilkan di sini oleh JS --}}
                                     <p id="notes-error" class="text-red-500 dark:text-red-400 text-xs mt-1.5"></p>
                                 </div>

                                 {{-- Metode Pembayaran (Info) --}}
                                 <div class="pt-4 border-t border-gray-200 dark:border-dark-border">
                                     <h4 class="text-lg font-semibold mb-3 text-text-dark dark:text-text-light">Metode Pembayaran</h4>
                                     <p class="text-sm text-gray-600 dark:text-gray-400">Pilih metode pembayaran setelah klik "Bayar Sekarang".</p>
                                 </div>
                            </div>

                            {{-- Tombol Proses Checkout --}}
                            <div class="mt-8">
                                {{-- Ubah type menjadi button agar tidak submit form secara standar --}}
                                <button type="button" id="pay-button" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-brand hover:bg-pink-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 dark:focus:ring-offset-dark-card transition-colors duration-150">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div> {{-- End Card Utama --}}
        </div> {{-- End max-w-7xl --}}
    </div> {{-- End py-12 --}}

    {{-- Tambahkan Midtrans Snap.js --}}
    {{-- Gunakan URL Sandbox atau Production sesuai MIDTRANS_IS_PRODUCTION di .env --}}
    <script type="text/javascript"
            src="https://app{{ config('services.midtrans.is_production') ? '' : '.sandbox' }}.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    {{-- Script untuk menangani AJAX dan Snap.js --}}
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(event){
            event.preventDefault(); // Mencegah submit form standar

            // Sembunyikan error sebelumnya
            document.getElementById('validation-errors').classList.add('hidden');
            document.getElementById('validation-errors-list').innerHTML = '';
            document.getElementById('general-error').classList.add('hidden');
            document.getElementById('general-error-message').innerText = '';

            // Clear previous field errors
            document.getElementById('shipping_address-error').innerText = '';
            document.getElementById('shipping_address').classList.remove('border-red-500', 'ring-1', 'ring-red-500', 'dark:border-red-500');
             document.getElementById('notes-error').innerText = '';
            document.getElementById('notes').classList.remove('border-red-500', 'ring-1', 'ring-red-500', 'dark:border-red-500');


            // Ambil data dari form
            const shippingAddress = document.getElementById('shipping_address').value;
            const notes = document.getElementById('notes').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Pastikan Anda punya meta csrf token di head layout

            // Kirim data ke backend via AJAX (menggunakan Fetch API)
            fetch('{{ route('checkout.process') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Kirim CSRF token
                },
                body: JSON.stringify({
                    shipping_address: shippingAddress,
                    notes: notes
                })
            })
            .then(response => {
                // Tangani respons non-OK status (error)
                if (!response.ok) {
                    // Jika validasi error (422), parse JSON errornya
                    if (response.status === 422) {
                         return response.json().then(data => {
                             // Tampilkan error validasi di tempat yang sesuai
                             const errors = data.errors;
                             const errorsList = document.getElementById('validation-errors-list');
                             for (const field in errors) {
                                 if (errors.hasOwnProperty(field)) {
                                      errors[field].forEach(error => {
                                          const li = document.createElement('li');
                                          li.innerText = error;
                                          errorsList.appendChild(li);

                                          // Tampilkan error di bawah field terkait jika field errornya diketahui
                                          const fieldErrorElement = document.getElementById(`${field}-error`);
                                          if (fieldErrorElement) {
                                               fieldErrorElement.innerText = error;
                                               // Optional: Tambahkan kelas border merah ke field input
                                               const inputElement = document.getElementById(field);
                                               if(inputElement) {
                                                    inputElement.classList.add('border-red-500', 'ring-1', 'ring-red-500', 'dark:border-red-500');
                                               }
                                          }
                                      });
                                 }
                             }
                             document.getElementById('validation-errors').classList.remove('hidden');
                         });
                    } else {
                         // Tangani error selain validasi
                        return response.json().then(data => {
                            const errorMessage = data.message || 'Terjadi kesalahan saat memproses checkout.';
                             document.getElementById('general-error-message').innerText = errorMessage;
                             document.getElementById('general-error').classList.remove('hidden');
                        }).catch(() => {
                             // Gagal parse JSON error
                             document.getElementById('general-error-message').innerText = 'Terjadi kesalahan yang tidak diketahui.';
                             document.getElementById('general-error').classList.remove('hidden');
                        });
                    }
                }
                // Jika respons OK (200), parse JSON tokennya
                return response.json();
            })
            .then(data => {
                // Jika berhasil mendapatkan token, tampilkan modal Midtrans
                if (data && data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            /* You may add your own implementation here */
                            alert("payment success!"); console.log(result);
                            // Redirect ke halaman order history atau sukses
                            window.location.href = '{{ route('orders.index') }}'; // Ganti dengan route orders Anda
                        },
                        onPending: function(result){
                            /* You may add your own implementation here */
                            alert("wating your payment!"); console.log(result);
                             // Redirect ke halaman order history atau halaman instruksi pembayaran
                             window.location.href = '{{ route('orders.index') }}'; // Ganti dengan route orders Anda
                        },
                        onError: function(result){
                            /* You may add your own implementation here */
                            alert("payment failed!"); console.log(result);
                             // Tetap di halaman ini atau redirect ke halaman error/checkout
                             // window.location.reload(); // Refresh halaman
                        },
                        onClose: function(){
                            /* You may add your own implementation here */
                            alert('you closed the popup without finishing the payment');
                             // User menutup modal tanpa menyelesaikan pembayaran
                             // Anda bisa biarkan user tetap di halaman checkout atau redirect
                        }
                    });
                }
            })
            .catch(error => {
                // Tangani error jaringan atau error lainnya sebelum respons sampai
                console.error('Fetch Error:', error);
                 document.getElementById('general-error-message').innerText = 'Terjadi kesalahan jaringan atau sistem. Silakan coba lagi.';
                 document.getElementById('general-error').classList.remove('hidden');
            });
        };
    </script>

</x-app-layout>
