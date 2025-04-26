<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductSizeColor;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSizeColor>
 */
class ProductSizeColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductSizeColor::class;

    public function definition()
    {
        // Ambil produk, ukuran dan warna acak
        $product = Product::inRandomOrder()->first();
        $size = Size::inRandomOrder()->first();
        $color = Color::inRandomOrder()->first();

        return [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'color_id' => $color->id,
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
