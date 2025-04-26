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
        return $this->belongsToMany(Product::class, 'product_size_color') // Gunakan tabel pivot yang konsisten
            ->withPivot('color_id', 'stock') // Menyertakan 'color_id' dan 'stock' di pivot
            ->withTimestamps();
    }

    // Relasi many-to-many dengan Color melalui pivot
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_size_color') // Konsisten menggunakan 'product_size_color'
            ->withPivot('color_id');
    }
    public function getColorName($colorId)
    {
        return $this->colors()->where('color_id', $colorId)->first()->name ?? null;
    }
    public function getColorCode($colorId)
    {
        return $this->colors()->where('color_id', $colorId)->first()->code ?? null;
    }

}
