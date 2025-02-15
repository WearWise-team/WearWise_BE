<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Bcrypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Mật khẩu mặc định sử dụng bcrypt
            'phone' => $this->faker->phoneNumber(),
            'avatar' => $this->faker->imageUrl(200, 200, 'people'),
            'address' => $this->faker->address(),
            'role' => $this->faker->randomElement(['admin', 'user']),
            'weight' => $this->faker->randomFloat(2, 40, 120),
            'height' => $this->faker->randomFloat(2, 140, 200),
            'shirt_size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'pant_size' => $this->faker->numberBetween(28, 40),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
        ];
    }
}