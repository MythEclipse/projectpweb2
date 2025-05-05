<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

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

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item-item pesanan (di tabel 'transactions')
    // Gunakan nama yang konsisten, misalnya transactionItems
    public function transactionItems(): HasMany
    {
        // The Transaction model represents the items in the 'transactions' table.
        // Eloquent will look for 'order_id' column on the related model (Transaction) by default.
        return $this->hasMany(Transaction::class);
    }

    // Hapus relasi 'items()' yang duplikat jika ada
    // public function items() { ... } // Hapus ini jika sudah ada transactionItems()
}
