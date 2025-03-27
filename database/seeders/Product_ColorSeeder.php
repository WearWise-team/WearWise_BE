<?php

namespace Database\Seeders;

use App\Models\Product_Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Product_ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product_Color::factory()->count(10)->create();
    }
}