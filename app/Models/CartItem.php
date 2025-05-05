<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'size_id',
        'color_id',
        'quantity',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function productSizeColor()
    {
         // Relasi ini penting untuk mengecek stok gabungan size/color
         return $this->belongsTo(ProductSizeColor::class, null, null, null, 'id') // Ini tidak tepat, perlu join manually atau relasi hasOne based on multiple keys
            ->whereColumn('cart_items.product_id', '=', 'product_size_colors.product_id')
            ->whereColumn('cart_items.size_id', '=', 'product_size_colors.size_id')
            ->whereColumn('cart_items.color_id', '=', 'product_size_colors.color_id');

        // Alternatively, fetch ProductSizeColor explicitly in controller/service
    }
}
