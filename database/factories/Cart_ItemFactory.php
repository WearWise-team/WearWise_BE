<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Product;
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
            'cart_id' => function () {
                return Cart::factory()->create()->id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 10)
        ];
    }
}