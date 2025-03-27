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
    protected $model = Order_Item::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory()->create()->id,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price, // Tính total_price
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'product_color_id' => Product_Color::inRandomOrder()->first()->id ?? Product_Color::factory()->create()->id,
            'product_size_id' => Product_Size::inRandomOrder()->first()->id ?? Product_Size::factory()->create()->id,
            'product_id' => $product->id,
        ];
    }
}
