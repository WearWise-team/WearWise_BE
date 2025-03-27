<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Discount_Assignment;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Discount_AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Discount_Assignment::class;
    public function definition(): array
    {
        return [
            'discount_id' => function () {
                return Discount::factory()->create()->id;
            },

            'product_id' => function() {
                return Product::factory()->create()->id;
            },
        ];
    }
}