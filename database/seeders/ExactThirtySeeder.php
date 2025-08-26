<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\Order;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExactThirtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧹 Clearing existing data...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data in reverse dependency order
        Order::query()->delete();
        DeliveryDetail::query()->delete();
        Delivery::query()->delete();
        Staff::query()->delete();
        Department::query()->delete();
        Customer::query()->delete();
        Promotion::query()->delete();
        Supplier::query()->delete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('✅ Data cleared successfully!');
        $this->command->info('🌱 Starting to seed exactly 30 records for each model...');

        // Create 30 Departments
        $this->command->info('🏢 Creating 30 departments...');
        Department::factory(30)->create();
        $this->command->info('✅ Departments created successfully!');

        // Create 30 Suppliers
        $this->command->info('📦 Creating 30 suppliers...');
        Supplier::factory(30)->create();
        $this->command->info('✅ Suppliers created successfully!');

        // Create 30 Staff Members
        $this->command->info('👨‍💼 Creating 30 staff members...');
        $departments = Department::all();
        Staff::factory(30)->create([
                            'department_id' => function () use ($departments) {
                    return $departments->random()->Department_ID;
                }
        ]);
        $this->command->info('✅ Staff members created successfully!');

        // Create 30 Customers
        $this->command->info('👥 Creating 30 customers...');
        Customer::factory(30)->create();
        $this->command->info('✅ Customers created successfully!');

        // Create 30 Promotions
        $this->command->info('🎉 Creating 30 promotions...');
        Promotion::factory(30)->create();
        $this->command->info('✅ Promotions created successfully!');

        // Create 30 Deliveries
        $this->command->info('🚚 Creating 30 deliveries...');
        $suppliers = Supplier::all();
        Delivery::factory(30)->create([
            'Supplier_ID' => function () use ($suppliers) {
                return $suppliers->random()->Supplier_ID;
            }
        ]);
        $this->command->info('✅ Deliveries created successfully!');

        // Create 30 Delivery Details
        $this->command->info('📋 Creating 30 delivery details...');
        $deliveries = Delivery::all();
        $products = Product::all();
        
        if ($products->count() > 0) {
            DeliveryDetail::factory(30)->create([
                'Delivery_ID' => function () use ($deliveries) {
                    return $deliveries->random()->Delivery_ID;
                },
                'Product_ID' => function () use ($products) {
                    return $products->random()->Product_ID;
                }
            ]);
            $this->command->info('✅ Delivery details created successfully!');
        } else {
            $this->command->warn('⚠️  No products found. Skipping delivery details creation.');
        }

        // Create 30 Orders
        $this->command->info('🛒 Creating 30 orders...');
        $customers = Customer::all();
        $staffMembers = Staff::all();
        $promotions = Promotion::all();
        
        Order::factory(30)->create([
            'Customer_ID' => function () use ($customers) {
                return $customers->random()->Customer_ID;
            },
            'Staff_ID' => function () use ($staffMembers) {
                return $staffMembers->random()->Staff_ID;
            },
            'Promotion_ID' => function () use ($promotions) {
                return fake()->optional(0.3)->randomElement([$promotions->random()->Promotion_ID]);
            }
        ]);
        $this->command->info('✅ Orders created successfully!');

        $this->command->info('🎉 All models have been seeded with exactly 30 records!');
        $this->command->info('📊 Final Summary:');
        $this->command->info('   - Departments: ' . Department::count());
        $this->command->info('   - Suppliers: ' . Supplier::count());
        $this->command->info('   - Staff Members: ' . Staff::count());
        $this->command->info('   - Customers: ' . Customer::count());
        $this->command->info('   - Promotions: ' . Promotion::count());
        $this->command->info('   - Deliveries: ' . Delivery::count());
        $this->command->info('   - Delivery Details: ' . DeliveryDetail::count());
        $this->command->info('   - Orders: ' . Order::count());
        $this->command->info('   - Products: ' . Product::count());
        $this->command->info('   - Users: ' . User::count());
    }
}
