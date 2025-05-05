<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model // Model ini sekarang berperan sebagai OrderItem
{
    use HasFactory;

    protected $fillable = [
        'order_id', // Tambahkan ini
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        // Hapus field yang dipindah ke Order model (user_id, status, total, dll.)
    ];

     // Jika Anda mengganti nama tabel:
     // protected $table = 'order_items';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

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
}
