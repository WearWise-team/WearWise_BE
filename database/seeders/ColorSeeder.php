<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Black', 'code' => '#000000'],
            ['name' => 'White', 'code' => '#FFFFFF'],
            ['name' => 'Gray', 'code' => '#808080'],
            ['name' => 'Beige', 'code' => '#F5F5DC'],
            ['name' => 'Navy', 'code' => '#000080'],
            ['name' => 'Blue', 'code' => '#0000FF'],
            ['name' => 'Red', 'code' => '#FF0000'],
            ['name' => 'Green', 'code' => '#008000'],
            ['name' => 'Yellow', 'code' => '#FFFF00'],
            ['name' => 'Brown', 'code' => '#A52A2A'],
            ['name' => 'Pink', 'code' => '#FFC0CB'],
            ['name' => 'Purple', 'code' => '#800080'],
            ['name' => 'Orange', 'code' => '#FFA500'],
            ['name' => 'Olive', 'code' => '#808000'],
            ['name' => 'Khaki', 'code' => '#F0E68C'],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}
