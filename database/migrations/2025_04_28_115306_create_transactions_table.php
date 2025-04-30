<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // harga per unit
            $table->decimal('total', 10, 2); // total harga transaksi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // jika ada autentikasi
            $table->string('status')->default('pending'); // status transaksi
            $table->string('payment_method')->nullable(); // metode pembayaran
            $table->string('payment_status')->default('unpaid'); // status pembayaran
            $table->string('shipping_address')->nullable(); // alamat pengiriman
            $table->string('tracking_number')->nullable(); // nomor resi pengiriman
            $table->string('shipping_status')->default('pending'); // status pengiriman
            $table->string('notes')->nullable(); // catatan tambahan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
