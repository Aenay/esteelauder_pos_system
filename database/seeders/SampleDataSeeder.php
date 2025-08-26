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

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting to seed sample data...');

        // Create 30 Suppliers
        $this->command->info('📦 Creating 30 suppliers...');
        Supplier::factory(30)->create();
        $this->command->info('✅ Suppliers created successfully!');

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

        // Create 30 Customers
        $this->command->info('👥 Creating 30 customers...');
        Customer::factory(30)->create();
        $this->command->info('✅ Customers created successfully!');

        // Create 30 Promotions
        $this->command->info('🎉 Creating 30 promotions...');
        Promotion::factory(30)->create();
        $this->command->info('✅ Promotions created successfully!');

        // Create 30 Orders
        $this->command->info('🛒 Creating 30 orders...');
        $customers = Customer::all();
        $staffMembers = Staff::all();
        
        if ($staffMembers->count() > 0) {
            Order::factory(30)->create([
                'Customer_ID' => function () use ($customers) {
                    return $customers->random()->Customer_ID;
                },
                'Staff_ID' => function () use ($staffMembers) {
                    return $staffMembers->random()->Staff_ID;
                }
            ]);
            $this->command->info('✅ Orders created successfully!');
        } else {
            $this->command->warn('⚠️  No staff members found. Skipping orders creation.');
        }

        $this->command->info('🎉 All sample data has been seeded successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Suppliers: ' . Supplier::count());
        $this->command->info('   - Deliveries: ' . Delivery::count());
        $this->command->info('   - Delivery Details: ' . DeliveryDetail::count());
        $this->command->info('   - Customers: ' . Customer::count());
        $this->command->info('   - Promotions: ' . Promotion::count());
        $this->command->info('   - Orders: ' . Order::count());
    }
}
