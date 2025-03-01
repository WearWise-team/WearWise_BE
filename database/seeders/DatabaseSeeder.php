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
        // Seed dữ liệu cơ bản trước (không có quan hệ phức tạp)
        $this->call([
            ProductSeeder::class, // Cần product trước
            UserSeeder::class,
            SupplierSeeder::class,
            SizeSeeder::class,
            ColorSeeder::class,
            DiscountSeeder::class,
            ImageSeeder::class,
            Product_ColorSeeder::class, // Sau khi có product, seed color
            Product_SizeSeeder::class,  // Sau khi có product, seed size
            DiscountAssignmentSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class, // Cần có cart trước
            OrderSeeder::class, // Cần có order trước
        ]);

        // Seed dữ liệu có quan hệ phụ thuộc (CartItem, OrderItem)
        $this->call([
            CartItemSeeder::class,  // Cart_Item phụ thuộc Cart + Product + Color + Size
            OrderItemSeeder::class, // Order_Item phụ thuộc Order + Product + Color + Size
        ]);
    }
}
