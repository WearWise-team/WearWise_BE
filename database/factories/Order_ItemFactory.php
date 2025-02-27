<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Product;
use App\Models\Product_Color;
use App\Models\Product_Size;
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
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory()->create()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'product_color_id' => Product_Color::inRandomOrder()->first()->id ?? Product_Color::factory()->create()->id,
            'product_size_id' => Product_Size::inRandomOrder()->first()->id ?? Product_Size::factory()->create()->id,
        ];
    }
}
