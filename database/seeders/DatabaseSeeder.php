<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            RolePermissionSeeder::class,
            StaffSeeder::class,
            SampleDataSeeder::class,
            LoyaltySeeder::class, // Add loyalty seeder
        ]);
        Product::factory(20)->create();
        
        // Create sample branches
        \App\Models\Branch::factory(10)->create();

        // Create admin user only if it doesn't exist
        $existingUser = User::where('email', 'test@example.com')->first();
        if (!$existingUser) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('admin');
        }
    }
}
