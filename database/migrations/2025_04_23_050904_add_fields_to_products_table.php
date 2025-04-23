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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('size', ['s', 'm', 'l', 'xl', 'xxl'])->nullable()->after('price');
            $table->string('color')->nullable()->after('size');
            $table->integer('stock')->default(0)->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size', 'color', 'stock']);
        });
    }
};
