<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null'); // Biarkan nullable jika produk tdk punya size
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null'); // Biarkan nullable jika produk tdk punya color
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Menambahkan unique constraint agar user tidak bisa memiliki item produk yang sama (dengan size/color yang sama) lebih dari sekali di keranjang
            $table->unique(['user_id', 'product_id', 'size_id', 'color_id'], 'user_product_variation_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
