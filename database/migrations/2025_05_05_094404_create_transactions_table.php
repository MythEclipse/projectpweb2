<?php

// database/migrations/YYYY_MM_DD_create_transactions_table.php
// Sesuaikan YYYY_MM_DD dengan tanggal dan waktu saat Anda membuat migrasi ini

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Struktur tabel 'transactions' yang baru hanya untuk ITEM pesanan
        // Nama tabel tetap 'transactions' agar kompatibel dengan model Transaction
        // jika Anda ingin tetap menggunakan nama model tersebut.
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel 'orders' (pastikan migrasi create_orders_table
            // sudah ada dan dijalankan SEBELUM migrasi ini).
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Detail item pesanan (dari cart_items / variasi produk)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // size_id dan color_id nullable karena variasi mungkin tidak selalu ada
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');

            $table->integer('quantity');

            // Harga per unit saat item ini dimasukkan ke pesanan
            // Penting untuk menyimpan harga historis, karena harga produk bisa berubah.
            // Menggunakan decimal untuk presisi.
            $table->decimal('price', 10, 2);

            // Kolom-kolom yang sebelumnya ada di struktur lama dan sekarang seharusnya DIHAPUS
            // karena detail pesanan utama disimpan di tabel 'orders':
            // - user_id
            // - total (total item = quantity * price bisa dihitung saat dibutuhkan)
            // - status (status item akan merefleksikan status order via relasi)
            // - payment_method
            // - payment_status
            // - shipping_address
            // - tracking_number
            // - shipping_status
            // - notes

            $table->timestamps();

            // Opsional: Menambahkan unique constraint jika user tidak boleh membeli variasi yang sama
            // lebih dari sekali dalam satu pesanan. Ini jarang diperlukan untuk tabel item pesanan
            // karena quantity sudah menangani banyak item sejenis.
            // $table->unique(['order_id', 'product_id', 'size_id', 'color_id'], 'order_item_variation_unique');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
