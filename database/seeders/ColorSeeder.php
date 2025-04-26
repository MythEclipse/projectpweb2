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
            ['name' => 'Merah', 'code' => '#FF0000'],
            ['name' => 'Biru', 'code' => '#0000FF'],
            ['name' => 'Hijau', 'code' => '#008000'],
            ['name' => 'Kuning', 'code' => '#FFFF00'],
            ['name' => 'Hitam', 'code' => '#000000'],
            ['name' => 'Putih', 'code' => '#FFFFFF'],
            ['name' => 'Abu-abu', 'code' => '#808080'],
            ['name' => 'Coklat', 'code' => '#A52A2A'],
            ['name' => 'Ungu', 'code' => '#800080'],
            ['name' => 'Pink', 'code' => '#FFC0CB'],
            ['name' => 'Orange', 'code' => '#FFA500'],
            ['name' => 'Emas', 'code' => '#FFD700'],
            ['name' => 'Perak', 'code' => '#C0C0C0'],
            ['name' => 'Navy', 'code' => '#000080'],
            ['name' => 'Marun', 'code' => '#800000'],
            ['name' => 'Khaki', 'code' => '#F0E68C'],
            ['name' => 'Tosca', 'code' => '#40E0D0'],
            ['name' => 'Burgundy', 'code' => '#800020'],
            ['name' => 'Teal', 'code' => '#008080'],
            ['name' => 'Lavender', 'code' => '#E6E6FA'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
