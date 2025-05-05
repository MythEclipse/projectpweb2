<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'midtrans_order_id',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_address',
        'shipping_status',
        'tracking_number',
        'notes',
        'midtrans_transaction_id',
        'midtrans_gross_amount',
        'midtrans_payment_type',
        'midtrans_va_number',
        'midtrans_expiry_time',
        'midtrans_snap_token',
        'midtrans_redirect_url',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item-item pesanan (di tabel 'transactions')
    public function items()
    {
        // Sesuaikan dengan nama model jika Anda mengganti nama model Transaction
        return $this->hasMany(Transaction::class); // Assuming Transaction model now represents OrderItems
    }
}
