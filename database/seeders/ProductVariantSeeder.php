<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product_Variants;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        Product_Variants::factory()->count(10)->create();
    }
}
