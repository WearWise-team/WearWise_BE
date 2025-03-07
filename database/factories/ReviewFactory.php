<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Review::class;
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'product_id' => function() {
                return Product::factory()->create()->id;
            },
            'order_item_id' => function() {
                return Order_Item::factory()->create()->id;
            },
            'rating' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->sentence(),
        ];
    }
}