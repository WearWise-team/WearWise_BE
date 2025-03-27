<?php

namespace Database\Seeders;

use App\Models\Discount_Assignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount_Assignment::factory(5)->create();
    }
}