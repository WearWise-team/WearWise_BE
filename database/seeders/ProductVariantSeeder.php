<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product_variants;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        Product_variants::factory()->count(10)->create();
    }
}
