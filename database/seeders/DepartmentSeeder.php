<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department; // Import the Department model

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default department if it doesn't exist
        Department::firstOrCreate(
            ['Department_ID' => 1], // Search criteria
            ['Department_Name' => 'Sales', 'Description' => 'Default Sales Department'] // Attributes to create if not found
        );
    }
}