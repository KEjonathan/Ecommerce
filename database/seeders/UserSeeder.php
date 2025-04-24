<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'type' => 'admin',
            'phone' => '1234567890',
            'address' => '123 Admin St, Admin City',
            'profile_image' => 'users/admin.jpg',
            'status' => true,
        ]);

        // Create delivery users
        $deliveryUsers = [
            [
                'name' => 'John Delivery',
                'email' => 'john@delivery.com',
                'password' => Hash::make('password'),
                'type' => 'delivery',
                'phone' => '2345678901',
                'address' => '456 Delivery Ave, Delivery Town',
                'profile_image' => 'users/delivery1.jpg',
                'status' => true,
            ],
            [
                'name' => 'Jane Delivery',
                'email' => 'jane@delivery.com',
                'password' => Hash::make('password'),
                'type' => 'delivery',
                'phone' => '3456789012',
                'address' => '789 Courier Rd, Delivery City',
                'profile_image' => 'users/delivery2.jpg',
                'status' => true,
            ],
        ];

        foreach ($deliveryUsers as $user) {
            User::create($user);
        }

        // Create customer users
        $customerUsers = [
            [
                'name' => 'Alice Customer',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'phone' => '4567890123',
                'address' => '123 Main St, Customer City',
                'profile_image' => 'users/customer1.jpg',
                'status' => true,
            ],
            [
                'name' => 'Bob Customer',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'phone' => '5678901234',
                'address' => '456 Oak Ave, Customer Town',
                'profile_image' => 'users/customer2.jpg',
                'status' => true,
            ],
            [
                'name' => 'Charlie Customer',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'type' => 'customer',
                'phone' => '6789012345',
                'address' => '789 Pine Blvd, Customer Village',
                'profile_image' => 'users/customer3.jpg',
                'status' => true,
            ],
        ];

        foreach ($customerUsers as $user) {
            User::create($user);
        }
    }
} 