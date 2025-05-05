<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model // Model ini sesuai dengan struktur tabel transactions lama + order_id
{
    use HasFactory;

    protected $table = 'transactions'; // Pastikan ini benar

    protected $fillable = [
        'order_id', // Tambahkan ini untuk link ke Order
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        'total', // Field dari struktur lama
        'user_id', // Field dari struktur lama
        'status', // Field dari struktur lama (status item/pesanan)
        'payment_method', // Field dari struktur lama
        'payment_status', // Field dari struktur lama
        'shipping_address', // Field dari struktur lama
        'tracking_number', // Field dari struktur lama
        'shipping_status', // Field dari struktur lama
        'notes', // Field dari struktur lama
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Product, Size, Color tetap sama
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // Relasi ke User (Ini diperlukan agar kode lama Auth::user()->transactions() bekerja)
    // Meskipun redundan secara data, ini menjaga kompatibilitas kode.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function productSizeColor(): BelongsTo // <<< TAMBAHKAN INI
    {

        // Asumsi: Ada kolom 'product_size_color_id' di tabel 'transactions'
        return $this->belongsTo(ProductSizeColor::class); // <<< Ini relasi yang dicari
    }
}
