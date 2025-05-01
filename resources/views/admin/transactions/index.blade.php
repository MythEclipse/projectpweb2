{{-- resources/views/admin/transactions/index.blade.php --}}
<x-app-layout>
    {{-- ... (Layout start, heading, buttons, alerts) ... --}}

    <div class="bg-white dark:bg-dark-card shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-dark-card text-sm">
                {{-- ... (thead remains the same) ... --}}
                <tbody class="divide-y divide-gray-200 dark:divide-dark-border text-text-dark dark:text-text-light" id="transaction-table-body">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-subcard" id="transaction-row-{{ $tx->id }}">
                        <td class="p-3 whitespace-nowrap">{{ $tx->product->name ?? 'N/A' }}</td>
                        <td class="p-3 whitespace-nowrap">
                            {{ $tx->size->name ?? 'N/A' }} / {{ $tx->color->name ?? 'N/A' }}
                        </td>
                        <td class="p-3 whitespace-nowrap text-right">{{ $tx->quantity }}</td>
                        <td class="p-3 whitespace-nowrap text-right">Rp {{ number_format($tx->price, 0, ',', '.') }}</td>
                        <td class="p-3 whitespace-nowrap text-right font-semibold">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                        <td class="p-3 whitespace-nowrap">{{ $tx->user->name ?? 'N/A' }}</td>

                        {{-- Status Column with Inline Controls --}}
                        <td class="p-3 whitespace-nowrap">
                             {{-- Status Transaksi (Static for now) --}}
                             <span class="block px-2 text-xs leading-5 font-semibold rounded-full mb-1
                                    @switch($tx->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @break
                                        @case('processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                        @case('completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                    @endswitch
                                ">
                                    {{ ucfirst($tx->status ?? 'N/A') }}
                             </span>

                            {{-- Payment Status Toggle --}}
                            <div class="mt-1 inline-flex items-center" title="Klik untuk ubah status pembayaran">
                                <label for="payment_toggle_{{ $tx->id }}" class="inline-flex items-center cursor-pointer">
                                    <span class="relative">
                                        <input type="checkbox" id="payment_toggle_{{ $tx->id }}"
                                               class="sr-only peer quick-update-toggle"
                                               data-transaction-id="{{ $tx->id }}"
                                               data-field="payment_status"
                                               data-value-checked="paid"
                                               data-value-unchecked="unpaid"
                                               @checked($tx->payment_status == 'paid')>
                                        {{-- Styling sama seperti di form edit --}}
                                        <div class="w-9 h-5 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-1 peer-focus:ring-pink-300 dark:peer-focus:ring-pink-brand peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-pink-brand"></div>
                                    </span>
                                    <span class="ml-2 text-xs font-medium text-gray-600 dark:text-gray-400 payment-status-text-{{ $tx->id }}">
                                        {{ $tx->payment_status == 'paid' ? 'Lunas' : 'Belum' }}
                                    </span>
                                </label>
                                {{-- Indikator Loading/Error (Awalnya Hidden) --}}
                                <span class="ml-1 status-indicator-{{ $tx->id }}"></span>
                            </div>

                            {{-- Shipping Status Select --}}
                             <div class="mt-1" title="Ubah status pengiriman">
                                <select name="shipping_status"
                                        class="quick-update-select text-xs p-1 border border-gray-300 dark:border-dark-border rounded shadow-sm focus:outline-none focus:ring-pink-brand focus:border-pink-brand dark:bg-dark-input dark:text-text-light"
                                        data-transaction-id="{{ $tx->id }}"
                                        data-field="shipping_status">
                                    <option value="not_shipped" @selected($tx->shipping_status == 'not_shipped')>Belum Kirim</option>
                                    <option value="shipped" @selected($tx->shipping_status == 'shipped')>Dikirim</option>
                                    <option value="delivered" @selected($tx->shipping_status == 'delivered')>Diterima</option>
                                </select>
                                 {{-- Indikator Loading/Error (Awalnya Hidden) --}}
                                <span class="ml-1 status-indicator-{{ $tx->id }}"></span>
                             </div>

                        </td>
                        <td class="p-3 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $tx->created_at->format('d M Y H:i') }}</td>
                        <td class="p-3 whitespace-nowrap text-center">
                            {{-- ... (Aksi Detail, Edit, Hapus tetap ada) ... --}}
                            <a href="{{ route('admin.transactions.show', $tx->id) }}" class="text-pink-brand hover:text-pink-brand-dark dark:text-pink-brand dark:hover:text-pink-500 mr-2 transition duration-150 ease-in-out" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.transactions.edit', $tx->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 mr-2 transition duration-150 ease-in-out" title="Edit Lengkap"><i class="fas fa-edit"></i></a>
                            {{-- <form action="{{ route('admin.transactions.destroy', $tx->id) }}" method="POST" class="inline" onsubmit="return confirm('...');"> ... </form> --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- ... (Paginasi jika ada) ... --}}
    </div>

    {{-- Include CSRF token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Inline JavaScript for AJAX Updates --}}
    @push('scripts') {{-- Atau letakkan sebelum </body> jika tidak pakai @push --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById('transaction-table-body');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // --- Helper Function to show temporary status indicator ---
            function showIndicator(transactionId, type = 'loading', message = '') {
                const indicator = document.querySelector(`.status-indicator-${transactionId}`);
                if (!indicator) return;

                indicator.innerHTML = ''; // Clear previous
                let icon = '';
                let colorClass = '';

                if (type === 'loading') {
                    // Simple spinner/dots or use FontAwesome if available
                    icon = '<i class="fas fa-spinner fa-spin text-blue-500"></i>'; // Requires FontAwesome
                    // icon = '...'; // simple text spinner
                } else if (type === 'success') {
                    icon = '<i class="fas fa-check-circle text-green-500"></i>'; // Requires FontAwesome
                    colorClass = 'text-green-500';
                } else if (type === 'error') {
                    icon = '<i class="fas fa-times-circle text-red-500"></i>'; // Requires FontAwesome
                    colorClass = 'text-red-500';
                }

                indicator.innerHTML = icon;
                indicator.title = message; // Show error message on hover

                // Clear indicator after a delay for success/error
                if (type === 'success' || type === 'error') {
                    setTimeout(() => {
                        indicator.innerHTML = '';
                        indicator.title = '';
                    }, 3000); // Hide after 3 seconds
                }
            }


            // --- Function to handle the update ---
            function handleQuickUpdate(element, transactionId, field, value) {
                const updateUrl = `/admin/transactions/${transactionId}/quick-update`; // Or use route() helper if Ziggy is installed
                const indicatorElement = element.closest('td').querySelector(`.status-indicator-${transactionId}`);

                // Show loading indicator near the element that triggered the change
                 showIndicator(transactionId, 'loading');
                 element.disabled = true; // Disable input while processing


                fetch(updateUrl, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        field: field,
                        value: value
                    })
                })
                .then(response => {
                    if (!response.ok) {
                         // Try to parse error JSON, default to generic message
                        return response.json().catch(() => ({})).then(errorData => {
                           throw new Error(errorData.message || `Server error: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log('Update successful:', data.message);
                         showIndicator(transactionId, 'success');

                         // Update visual text for payment toggle if needed
                         if (field === 'payment_status') {
                            const textSpan = document.querySelector(`.payment-status-text-${transactionId}`);
                            if(textSpan) {
                                textSpan.textContent = data.new_value === 'paid' ? 'Lunas' : 'Belum';
                            }
                         }
                         // The select value updates automatically, toggle checked state updates automatically
                    } else {
                        // Throw error to be caught by .catch block
                        throw new Error(data.message || 'Update failed.');
                    }
                })
                .catch(error => {
                    console.error('Update Error:', error);
                    showIndicator(transactionId, 'error', error.message);
                    alert(`Error updating status: ${error.message}`);

                    // --- Revert UI changes on error ---
                    if (element.type === 'checkbox') {
                        element.checked = !element.checked; // Revert toggle state
                    } else if (element.tagName === 'SELECT') {
                         // Find the original value (more complex, might need to store it initially)
                         // For simplicity, we might just alert and not revert the select
                         // Or reload the row/page on error if reverting is too complex
                         // Example: find the option that *was* selected before the change
                         // This requires storing the previous value, e.g., on focus/mousedown
                    }
                })
                .finally(() => {
                     element.disabled = false; // Re-enable input
                     // Optionally hide loading indicator if not replaced by success/error
                     if (indicatorElement && indicatorElement.innerHTML.includes('fa-spinner')) {
                         setTimeout(() => { if(indicatorElement.innerHTML.includes('fa-spinner')) indicatorElement.innerHTML = ''; }, 500);
                     }
                });
            }

            // --- Event Listener for Toggles ---
            tableBody.addEventListener('change', function(event) {
                if (event.target.matches('.quick-update-toggle')) {
                    const toggle = event.target;
                    const transactionId = toggle.dataset.transactionId;
                    const field = toggle.dataset.field;
                    const value = toggle.checked ? toggle.dataset.valueChecked : toggle.dataset.valueUnchecked;

                    handleQuickUpdate(toggle, transactionId, field, value);
                }
            });

            // --- Event Listener for Selects ---
             tableBody.addEventListener('change', function(event) {
                if (event.target.matches('.quick-update-select')) {
                    const select = event.target;
                    const transactionId = select.dataset.transactionId;
                    const field = select.dataset.field;
                    const value = select.value;

                    handleQuickUpdate(select, transactionId, field, value);
                 }
             });

        });
    </script>
    @endpush

</x-app-layout>
