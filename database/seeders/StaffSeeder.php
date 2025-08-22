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
        // Create a sample staff member
        Staff::create([n            'Staff_Name' => 'Test Staff',
            'Staff_Phone' => '123-456-7890',
            'Staff_Address' => '123 Test St',
            'email' => 'test@example.com', // IMPORTANT: Change this to the email of your authenticated user
            'password' => Hash::make('password'), // Change 'password' to a strong password
            'department_id' => 1, // IMPORTANT: Ensure this department_id exists in your departments table
        ]);
    }
}