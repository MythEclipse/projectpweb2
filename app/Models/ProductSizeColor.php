<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSizeColor extends Model
{
    protected $table = 'product_size_color';
    protected $fillable = ['product_id', 'size_id', 'color_id', 'stock'];

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
