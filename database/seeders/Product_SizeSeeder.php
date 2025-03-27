<?php

namespace Database\Seeders;

use App\Models\Product_size;
use Illuminate\Database\Seeder;

class Product_SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product_Size::factory()->count(10)->create();
    }
}
