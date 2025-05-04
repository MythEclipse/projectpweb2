<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Product extends Model
{
    use HasFactory;
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
    public function getImageUrlAttribute(): ?string
    {
        $imageValue = $this->image;

        if (empty($imageValue)) {
            // return 'path/to/default/placeholder.png'; // Opsional: Placeholder
            return null;
        }

        // Cek apakah $imageValue adalah URL absolut (mulai dengan http/https)
        if (Str::startsWith($imageValue, ['http://', 'https://'])) {
            return $imageValue; // Sudah berupa URL lengkap
        }

        // Jika bukan URL, anggap itu path lokal di disk 'public'
        // Pastikan file benar-benar ada sebelum generate URL untuk menghindari error jika path invalid
        if (Storage::disk('public')->exists($imageValue)) {
            return asset('storage/' . $imageValue);
        }

        // Fallback jika path tidak ditemukan atau format tidak dikenali
        // Mungkin return null atau placeholder default
        return null; // Atau return $imageValue jika ingin menampilkan path mentahnya
    }
    // Relasi many-to-many dengan Size
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size_color')
            ->withPivot('color_id', 'stock')
            ->withTimestamps();
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_size_color')
            ->withPivot('size_id', 'stock')
            ->withTimestamps();
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function stockCombinations()
    {
        return $this->hasMany(ProductSizeColor::class);
    }
    public function getColorName($colorId)
    {
        return $this->colors()->where('color_id', $colorId)->first()->name ?? null;
    }

    public function getColorCode($colorId)
    {
        return $this->colors()->where('color_id', $colorId)->first()->code ?? null;
    }
    public function sizeColorStocks()
    {
        return $this->hasMany(ProductSizeColor::class, 'product_id', 'id');
    }
    public function wishlistedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_wishlist', 'product_id', 'user_id')
                    ->withTimestamps();
    }
}
