<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'image'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = static::generateUniqueSlug($product->name, $product->id);
        });
    }

    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    // Relasi many-to-many dengan Size
    public function sizes()
    {
        return $this->belongsToMany(Size::class)
            ->withPivot('stock')  // Menyertakan kolom pivot 'stock'
            ->withTimestamps();  // Menyertakan timestamps di pivot table
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
