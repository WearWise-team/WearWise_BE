<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Mật khẩu mặc định sử dụng bcrypt
            'phone' => $this->faker->optional()->phoneNumber(),
            'avatar' => "https://placehold.co/100x100",
            'address' => $this->faker->optional()->address(),
            'role' => $this->faker->optional()->randomElement(['admin', 'user']),
            'weight' => $this->faker->optional()->randomFloat(2, 40, 120),
            'height' => $this->faker->optional()->randomFloat(2, 140, 200),
            'shirt_size' => $this->faker->optional()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'pant_size' => $this->faker->optional()->numberBetween(28, 40),
            'gender' => $this->faker->optional()->randomElement(['male', 'female', 'other']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
