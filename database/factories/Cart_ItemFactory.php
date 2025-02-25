<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
use App\Models\Product_Color;
use App\Models\Product_Size;
use App\Models\Product_variants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart_Item>
 */
class Cart_ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Cart_Item::class;
    public function definition(): array
    {
        return [
            'cart_id' => Cart::inRandomOrder()->first()->id ?? Cart::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'product_color_id' => Product_Color::inRandomOrder()->first()->id ?? Product_Color::factory()->create()->id,
            'product_size_id' => Product_Size::inRandomOrder()->first()->id ?? Product_Size::factory()->create()->id,
            'product_id' => Product::all()->random()->id,
        ];
    }
}
