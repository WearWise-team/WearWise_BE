<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
            ProductSeeder::class,
            UserSeeder::class,
            SupplierSeeder::class,
            // SizeSeeder::class,
            // ColorSeeder::class,
            DiscountSeeder::class,
            ImageSeeder::class,
            Product_ColorSeeder::class,
            Product_SizeSeeder::class,  
            DiscountAssignmentSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class,
            OrderSeeder::class, 
            
        ]);
        $this->call([
            CartItemSeeder::class, 
            OrderItemSeeder::class,
        ]);
    }
}