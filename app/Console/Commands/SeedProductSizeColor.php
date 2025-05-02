<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductSizeColor;
use Faker\Factory as Faker;

class SeedProductSizeColor extends Command
{
    protected $signature = 'seed:product-size-color';
    protected $description = 'Seed product-size-color combinations with biased stock (more 0s)';

    public function handle()
    {
        $faker = Faker::create();
        $sizes = Size::all();
        $colors = Color::all();
        $count = 0;

        foreach (Product::all() as $product) {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $exists = ProductSizeColor::where([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                    ])->exists();

                    if (!$exists) {
                        $stock = $faker->boolean(70) ? 0 : $faker->numberBetween(1, 100);

                        if ($stock > 0) {
                            ProductSizeColor::create([
                                'product_id' => $product->id,
                                'size_id' => $size->id,
                                'color_id' => $color->id,
                                'stock' => $stock,
                            ]);
                            $count++;
                        }
                    }
                }
            }
        }

        $this->info("Selesai! $count kombinasi berhasil ditambahkan.");
    }
}
