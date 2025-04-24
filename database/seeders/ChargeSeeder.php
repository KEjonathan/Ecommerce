<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create delivery charges
        $charges = [
            [
                'name' => 'Standard Delivery',
                'amount' => 5.00,
                'type' => 'delivery',
                'status' => true,
            ],
            [
                'name' => 'Express Delivery',
                'amount' => 10.00,
                'type' => 'delivery',
                'status' => true,
            ],
            [
                'name' => 'Weekend Delivery',
                'amount' => 7.50,
                'type' => 'delivery',
                'status' => true,
            ],
            [
                'name' => 'Service Fee',
                'amount' => 2.00,
                'type' => 'service',
                'status' => true,
            ],
        ];

        foreach ($charges as $charge) {
            Charge::create($charge);
        }
    }
} 