<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        'total',
        'user_id',
    ];

    // Relasi dengan tabel produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi dengan tabel ukuran
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    // Relasi dengan tabel warna
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // Relasi dengan tabel pengguna
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
