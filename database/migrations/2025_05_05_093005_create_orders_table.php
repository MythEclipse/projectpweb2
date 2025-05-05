<?php

// database/migrations/..._create_orders_table.php
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('midtrans_order_id')->unique(); // Order ID yang dikirim ke Midtrans
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending'); // pending, paid, failed, cancelled, etc.
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, settlement, pending, expire, cancel, etc. (status dari Midtrans)
            $table->string('payment_method')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_status')->default('not_shipped'); // not_shipped, shipped, delivered, etc.
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();

            // Midtrans specific
            $table->string('midtrans_transaction_id')->nullable(); // transaction_id dari Midtrans
            $table->string('midtrans_gross_amount')->nullable(); // gross_amount dari Midtrans
            $table->string('midtrans_payment_type')->nullable(); // e.g., bank_transfer, credit_card
            $table->string('midtrans_va_number')->nullable(); // Virtual Account number if applicable
            $table->string('midtrans_expiry_time')->nullable(); // Expiry time for payment
            $table->text('midtrans_snap_token')->nullable(); // Snap token if using Snap
            $table->text('midtrans_redirect_url')->nullable(); // Redirect URL if not using Snap modal

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
