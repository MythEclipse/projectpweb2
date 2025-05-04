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
        Schema::create('product_wishlist', function (Blueprint $table) {
            $table->id();
            // Foreign key untuk user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key untuk product
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps(); // Opsional: jika ingin tahu kapan ditambahkan

            // Pastikan kombinasi user_id dan product_id unik
            $table->unique(['user_id', 'product_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_wishlist');
    }
};
