<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model // Model ini sesuai dengan struktur tabel transactions (item pesanan)
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        // Field berikut seharusnya TIDAK ada di fillable jika sudah dihapus dari tabel transactions:
        // 'total',
        // 'user_id',
        // 'status',
        // 'payment_method',
        // 'payment_status',
        // 'shipping_address',
        // 'tracking_number',
        // 'shipping_status',
        // 'notes',

        // Jika Anda menambahkan kolom product_size_color_id ke tabel transactions, tambahkan di sini:
        // 'product_size_color_id',
    ];

    // Relasi ke Order (induk)
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Size (nullable)
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    // Relasi ke Color (nullable)
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    // Relasi ke ProductSizeColor (untuk mendapatkan stok)
    // Relasi BelongsTo standar membutuhkan kolom 'product_size_color_id' di tabel 'transactions'
    public function productSizeColor(): BelongsTo
    {
        // Jika ada kolom 'product_size_color_id' di tabel 'transactions', gunakan ini:
        return $this->belongsTo(ProductSizeColor::class);

        // Jika TIDAK ada kolom 'product_size_color_id' dan Anda tidak ingin menambahkannya,
        // relasi ini tidak bisa menjadi BelongsTo standar. Anda perlu pendekatan lain
        // (misal: accessor atau query langsung) untuk mendapatkan ProductSizeColor berdasarkan
        // product_id, size_id, color_id.
        // Namun, metode load('transactionItems.productSizeColor') di NotificationController
        // MENGASUMSIKAN relasi ini ada dan berfungsi sebagai BelongsTo.
        // Jadi, menambahkan kolom product_size_color_id adalah solusi yang paling konsisten.
    }

    // Relasi ke User (Ini diperlukan agar kode lama Auth::user()->transactions() bekerja)
    // Relasi ini SALAH di skema baru karena user_id ada di tabel orders.
    // Sebaiknya dihapus jika tidak ada kode lama yang KRITIS bergantung padanya.
    // Jika harus dipertahankan untuk kompatibilitas, perlu penanganan khusus
    // (misal: accessor yang mengambil dari $this->order->user jika order ada).
    // public function user()
    // {
    //     // Ini akan mencari 'user_id' di tabel 'transactions', yang seharusnya sudah dihapus.
    //     // Relasi yang benar ke user adalah melalui order: $this->order->user
    //     // Menghapus relasi ini adalah pilihan paling bersih untuk skema baru.
    //     return $this->belongsTo(User::class); // Relasi ini kemungkinan SALAH
    // }
}
