<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'Fruits',
                'image' => 'categories/fruits.jpg',
                'status' => true,
            ],
            [
                'name' => 'Vegetables',
                'image' => 'categories/vegetables.jpg',
                'status' => true,
            ],
            [
                'name' => 'Dairy',
                'image' => 'categories/dairy.jpg',
                'status' => true,
            ],
            [
                'name' => 'Bakery',
                'image' => 'categories/bakery.jpg',
                'status' => true,
            ],
            [
                'name' => 'Meat',
                'image' => 'categories/meat.jpg',
                'status' => true,
            ],
            [
                'name' => 'Beverages',
                'image' => 'categories/beverages.jpg',
                'status' => true,
            ],
            [
                'name' => 'Snacks',
                'image' => 'categories/snacks.jpg',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 