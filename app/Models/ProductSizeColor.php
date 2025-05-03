<?php // app/Models/ProductSizeColor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSizeColor extends Model
{
    use HasFactory;
    protected $table = 'product_size_color';
    protected $fillable = ['product_id', 'size_id', 'color_id', 'stock'];

    // ... existing relationships ...

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

    /**
     * Accessor to check if this specific combination is out of stock.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute(): bool
    {
        // You might consider null stock as out of stock too, adjust if needed
        return $this->stock <= 0;
    }

    /**
     * Accessor to get a display string for the stock.
     *
     * @return string
     */
     public function getStockDisplayAttribute(): string
     {
         if ($this->is_out_of_stock) {
             return '<span class="badge bg-danger">Habis</span>'; // Example using Bootstrap badge
             // Or just return "Habis";
         }
         return (string) $this->stock; // Cast to string
     }

}
