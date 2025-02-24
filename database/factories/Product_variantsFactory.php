<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product_variants;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;

class Product_variantsFactory extends Factory
{
    protected $model = Product_variants::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'color_id' => Color::factory(),
            'size_id' => Size::factory(),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
        ];
    }
}
