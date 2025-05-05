<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'email_verified_at',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function wishlistProducts(): BelongsToMany
    {
        // Argumen kedua adalah nama tabel pivot
        // Argumen ketiga & keempat adalah foreign key (opsional jika mengikuti konvensi)
        return $this->belongsToMany(Product::class, 'product_wishlist', 'user_id', 'product_id')
            ->withTimestamps(); // Jika ingin mengakses created_at/updated_at di pivot
    }

    /**
     * Helper untuk mengecek apakah produk ada di wishlist.
     * Lebih efisien daripada mengambil semua produk lalu mengecek.
     */
    public function hasInWishlist(Product $product): bool
    {
        return $this->wishlistProducts()->where('product_id', $product->id)->exists();
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
