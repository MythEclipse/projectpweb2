<?php

// database/migrations/YYYY_MM_DD_create_transactions_table.php (Ini file yang baru dengan timestamp terbaru)
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel 'orders' (pastikan migrasi orders dijalankan SEBELUM ini)
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Detail item pesanan (dari cart_items / variasi produk)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity');

            // Harga per unit saat item ini dimasukkan ke pesanan (penting untuk menjaga harga historis)
            $table->decimal('price', 10, 2);

            // Kolom-kolom redundant dari struktur lama DIHAPUS karena sudah ada di tabel 'orders':
            // user_id, total, status, payment_method, payment_status,
            // shipping_address, tracking_number, shipping_status, notes

            $table->timestamps();

            // Opsional: Menambahkan unique constraint jika user tidak boleh membeli variasi yang sama
            // dalam satu pesanan (walaupun jarang diperlukan di tabel item pesanan)
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
