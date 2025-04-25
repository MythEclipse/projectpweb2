<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    // Menentukan kolom yang dapat diisi (fillable)
    protected $fillable = ['name'];

    // Relasi many-to-many dengan Product
    public function products()
{
    return $this->belongsToMany(Product::class, 'product_size_color')
                ->withPivot('color_id', 'stock')
                ->withTimestamps();
}
}
