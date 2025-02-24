<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            CartSeeder::class,
            DiscountSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            SizeSeeder::class,
            CartItemSeeder::class,
            OrderItemSeeder::class,
            DiscountAssignmentSeeder::class,
            ColorSeeder::class,
            Product_ColorSeeder::class,
            ImageSeeder::class,
            ProductVariantSeeder::class,
        ]);
    }
}