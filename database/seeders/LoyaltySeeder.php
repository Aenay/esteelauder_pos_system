<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoyaltyPoint;
use App\Models\Customer;

class LoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing customers or create some if none exist
        $customers = Customer::all();
        
        if ($customers->isEmpty()) {
            // Create some sample customers if none exist
            $customers = Customer::factory(10)->create();
        }

        // Create loyalty records for each customer
        foreach ($customers as $customer) {
            LoyaltyPoint::factory()->create([
                'Customer_ID' => $customer->Customer_ID,
            ]);
        }

        // Create some specific tier examples
        LoyaltyPoint::factory()->bronze()->create([
            'Customer_ID' => $customers->first()->Customer_ID,
            'points_earned' => 50,
            'current_balance' => 50,
        ]);

        LoyaltyPoint::factory()->silver()->create([
            'Customer_ID' => $customers->skip(1)->first()->Customer_ID,
            'points_earned' => 250,
            'current_balance' => 200,
            'points_used' => 50,
        ]);

        LoyaltyPoint::factory()->gold()->create([
            'Customer_ID' => $customers->skip(2)->first()->Customer_ID,
            'points_earned' => 750,
            'current_balance' => 600,
            'points_used' => 150,
        ]);

        LoyaltyPoint::factory()->platinum()->create([
            'Customer_ID' => $customers->skip(3)->first()->Customer_ID,
            'points_earned' => 1500,
            'current_balance' => 1200,
            'points_used' => 300,
        ]);

        $this->command->info('Loyalty points seeded successfully!');
        $this->command->info('Created loyalty records for ' . $customers->count() . ' customers');
    }
}

