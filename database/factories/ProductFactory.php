<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition()
    {
        // Ambil ukuran dan warna acak
        $size = Size::inRandomOrder()->first();
        $color = Color::inRandomOrder()->first();

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
