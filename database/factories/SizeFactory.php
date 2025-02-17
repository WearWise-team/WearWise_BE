<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Size::class;
    public function definition(): array
    {
        return [
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'shirt_size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'pant_size' => $this->faker->numberBetween(28, 40),
            'minimun_weight' => $this->faker->numberBetween(40, 60),
            'maximun_weight' => $this->faker->numberBetween(61, 100),
            'minimun_height' => $this->faker->numberBetween(150, 170),
            'maximun_height' => $this->faker->numberBetween(171, 200),
            'target_audience' => $this->faker->randomElement(['Men', 'Women', 'Unisex']),
        ];
    }
}