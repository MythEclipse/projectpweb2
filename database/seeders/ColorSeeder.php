<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Merah'],
            ['name' => 'Biru'],
            ['name' => 'Hijau'],
            ['name' => 'Kuning'],
            ['name' => 'Hitam'],
            ['name' => 'Putih'],
            ['name' => 'Abu-abu'],
            ['name' => 'Coklat'],
            ['name' => 'Ungu'],
            ['name' => 'Pink'],
            ['name' => 'Orange'],
            ['name' => 'Emas'],
            ['name' => 'Perak'],
            ['name' => 'Navy'],
            ['name' => 'Marun'],
            ['name' => 'Khaki'],
            ['name' => 'Tosca'],
            ['name' => 'Burgundy'],
            ['name' => 'Teal'],
            ['name' => 'Lavender'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
