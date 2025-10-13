<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some customers with passwords for testing
        $customers = [
            [
                'Customer_Name' => 'Sarah Johnson',
                'Customer_Email' => 'sarah.johnson@example.com',
                'Customer_Phone' => '+1-555-0123',
                'Customer_Address' => '123 Main St, New York, NY 10001',
                'Customer_Type' => 'internal',
                'Registration_Date' => now()->subMonths(6),
                'password' => Hash::make('password'),
            ],
            [
                'Customer_Name' => 'Michael Chen',
                'Customer_Email' => 'michael.chen@example.com',
                'Customer_Phone' => '+1-555-0124',
                'Customer_Address' => '456 Oak Ave, Los Angeles, CA 90210',
                'Customer_Type' => 'internal',
                'Registration_Date' => now()->subMonths(3),
                'password' => Hash::make('password'),
            ],
            [
                'Customer_Name' => 'Emily Davis',
                'Customer_Email' => 'emily.davis@example.com',
                'Customer_Phone' => '+1-555-0125',
                'Customer_Address' => '789 Pine St, Chicago, IL 60601',
                'Customer_Type' => 'internal',
                'Registration_Date' => now()->subMonths(1),
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::updateOrCreate(
                ['Customer_Email' => $customerData['Customer_Email']],
                $customerData
            );
        }

        // Update existing customers without passwords to have a default password
        Customer::whereNull('password')->update([
            'password' => Hash::make('password')
        ]);
    }
}