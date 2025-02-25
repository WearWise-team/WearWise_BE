<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColorFactory extends Factory
{
    protected $model = Color::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Red', 'Blue', 'Green', 'Yellow', 'Black', 'White']),
            'code' => fake()->hexColor(),
        ];
    }
}
