<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order_Item>
 */
class Order_ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order_Item::class;
    public function definition(): array
    {
        return [
            'order_id' => function () {
                return Order::factory()->create()->id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}