<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use App\Models\Product_Color;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product_Color>
 */
class Product_ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product_Color::class;
    public function definition(): array
    {
        return [
            'color_id' => function () {
                return Color::factory()->create()->id;
            },

            'product_id' => function () {
                return Product::factory()->create()->id;
            },
        ];
    }
}