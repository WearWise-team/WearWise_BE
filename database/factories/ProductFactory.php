<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'supplier_id' => function () {
                return Supplier::factory()->create()->id;
            },
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(400, 400, 'products'),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}