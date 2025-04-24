<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $fruitsId = Category::where('name', 'Fruits')->first()->id;
        $vegetablesId = Category::where('name', 'Vegetables')->first()->id;
        $dairyId = Category::where('name', 'Dairy')->first()->id;
        $bakeryId = Category::where('name', 'Bakery')->first()->id;
        $meatId = Category::where('name', 'Meat')->first()->id;
        $beveragesId = Category::where('name', 'Beverages')->first()->id;
        $snacksId = Category::where('name', 'Snacks')->first()->id;

        // Create products
        $products = [
            // Fruits
            [
                'name' => 'Apples',
                'description' => 'Fresh red apples, perfect for eating or baking.',
                'image' => 'products/apples.jpg',
                'price' => 2.99,
                'discount_price' => 2.49,
                'stock_quantity' => 50,
                'category_id' => $fruitsId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Bananas',
                'description' => 'Ripe yellow bananas, great for smoothies or snacking.',
                'image' => 'products/bananas.jpg',
                'price' => 1.99,
                'discount_price' => null,
                'stock_quantity' => 75,
                'category_id' => $fruitsId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Oranges',
                'description' => 'Juicy oranges, rich in vitamin C.',
                'image' => 'products/oranges.jpg',
                'price' => 3.49,
                'discount_price' => 2.99,
                'stock_quantity' => 45,
                'category_id' => $fruitsId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Vegetables
            [
                'name' => 'Carrots',
                'description' => 'Organic carrots, perfect for cooking or eating raw.',
                'image' => 'products/carrots.jpg',
                'price' => 1.49,
                'discount_price' => null,
                'stock_quantity' => 60,
                'category_id' => $vegetablesId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Broccoli',
                'description' => 'Fresh green broccoli, packed with nutrients.',
                'image' => 'products/broccoli.jpg',
                'price' => 2.29,
                'discount_price' => 1.99,
                'stock_quantity' => 40,
                'category_id' => $vegetablesId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Dairy
            [
                'name' => 'Milk',
                'description' => 'Fresh whole milk, 1 gallon.',
                'image' => 'products/milk.jpg',
                'price' => 3.99,
                'discount_price' => 3.49,
                'stock_quantity' => 80,
                'category_id' => $dairyId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Cheese',
                'description' => 'Premium cheddar cheese, 8oz block.',
                'image' => 'products/cheese.jpg',
                'price' => 4.99,
                'discount_price' => null,
                'stock_quantity' => 35,
                'category_id' => $dairyId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Bakery
            [
                'name' => 'Bread',
                'description' => 'Freshly baked whole wheat bread.',
                'image' => 'products/bread.jpg',
                'price' => 2.99,
                'discount_price' => 2.49,
                'stock_quantity' => 30,
                'category_id' => $bakeryId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Croissants',
                'description' => 'Buttery, flaky croissants, pack of 4.',
                'image' => 'products/croissants.jpg',
                'price' => 5.99,
                'discount_price' => 4.99,
                'stock_quantity' => 25,
                'category_id' => $bakeryId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Meat
            [
                'name' => 'Chicken Breast',
                'description' => 'Boneless, skinless chicken breast, 1lb package.',
                'image' => 'products/chicken.jpg',
                'price' => 6.99,
                'discount_price' => 5.99,
                'stock_quantity' => 40,
                'category_id' => $meatId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Ground Beef',
                'description' => 'Lean ground beef, 1lb package.',
                'image' => 'products/beef.jpg',
                'price' => 5.99,
                'discount_price' => null,
                'stock_quantity' => 35,
                'category_id' => $meatId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Beverages
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh squeezed orange juice, 64oz bottle.',
                'image' => 'products/orange_juice.jpg',
                'price' => 4.99,
                'discount_price' => 3.99,
                'stock_quantity' => 55,
                'category_id' => $beveragesId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Coffee',
                'description' => 'Premium ground coffee, 12oz bag.',
                'image' => 'products/coffee.jpg',
                'price' => 7.99,
                'discount_price' => null,
                'stock_quantity' => 60,
                'category_id' => $beveragesId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            
            // Snacks
            [
                'name' => 'Potato Chips',
                'description' => 'Classic potato chips, 8oz bag.',
                'image' => 'products/chips.jpg',
                'price' => 3.49,
                'discount_price' => 2.99,
                'stock_quantity' => 70,
                'category_id' => $snacksId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
            [
                'name' => 'Chocolate Cookies',
                'description' => 'Double chocolate chip cookies, pack of 12.',
                'image' => 'products/cookies.jpg',
                'price' => 4.49,
                'discount_price' => 3.99,
                'stock_quantity' => 45,
                'category_id' => $snacksId,
                'status' => true,
                'is_morning_available' => true,
                'is_afternoon_available' => true,
                'is_evening_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 