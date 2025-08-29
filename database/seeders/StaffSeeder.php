<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff; // Import the Staff model
use Illuminate\Support\Facades\Hash; // For hashing the password

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample staff member only if it doesn't exist
        $existingStaff = Staff::where('email', 'staff@esteelauder.com')->first();
        if (!$existingStaff) {
            Staff::create(['Staff_Name' => 'Test Staff',
                'Staff_Phone' => '123-456-7890',
                'Staff_Address' => '123 Test St',
                'email' => 'staff@esteelauder.com', // Changed to avoid conflict with user email
                'password' => Hash::make('password'), // Change 'password' to a strong password
                'department_id' => 1, // IMPORTANT: Ensure this department_id exists in your departments table
            ]);
        }
    }
}