<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductSizeColor;
use Faker\Factory as Faker;

class SeedProductsWithSizesAndColors extends Command
{
    protected $signature = 'seed:products-sizes-colors {count=10}';
    protected $description = 'Seed products and generate size-color-stock combinations';

    public function handle()
    {
        $faker = Faker::create();

        $count = (int) $this->argument('count');
        $sizes = Size::all();
        $colors = Color::all();

        if ($sizes->isEmpty() || $colors->isEmpty()) {
            $this->error('Pastikan tabel sizes dan colors sudah terisi.');
            return;
        }

        $this->info("Membuat $count produk...");
        $products = Product::factory()->count($count)->create();

        $totalCombinations = 0;

        foreach ($products as $product) {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $stock = $faker->boolean(70) ? 0 : $faker->numberBetween(1, 100);

                    // Skip if stock is 0
                    if ($stock === 0) {
                        continue;
                    }

                    ProductSizeColor::create([
                        'product_id' => $product->id,
                        'size_id' => $size->id,
                        'color_id' => $color->id,
                        'stock' => $stock,
                    ]);
                    $totalCombinations++;
                }
            }
        }

        $this->info("Selesai! $count produk dibuat dengan total $totalCombinations kombinasi size-color.");
    }
}
