<?php

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
        Schema::create('product_size_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0); // Menyimpan stok untuk kombinasi ukuran dan warna
            $table->timestamps();

            $table->unique(['product_id', 'size_id', 'color_id']); // Menjamin kombinasi produk, ukuran, dan warna unik
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size_color');
    }
};
