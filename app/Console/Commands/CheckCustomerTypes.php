<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\LoyaltyPoint;

class CheckCustomerTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:check-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check customer types and loyalty records to debug walk-in issue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Checking customer types and loyalty records...');
        $this->line('');

        // Check all customers
        $this->info('📋 ALL CUSTOMERS:');
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $loyaltyRecord = $customer->loyaltyPoints;
            $hasLoyalty = $loyaltyRecord ? 'YES' : 'NO';
            $points = $loyaltyRecord ? $loyaltyRecord->current_balance : 0;
            
            $this->line("  ID: {$customer->Customer_ID} | Name: {$customer->Customer_Name} | Type: '{$customer->Customer_Type}' | Loyalty: {$hasLoyalty} | Points: {$points}");
        }

        $this->line('');

        // Check loyalty records specifically
        $this->info('🎯 LOYALTY RECORDS:');
        $loyaltyRecords = LoyaltyPoint::with('customer')->get();
        foreach ($loyaltyRecords as $loyalty) {
            $customer = $loyalty->customer;
            $this->line("  Customer: {$customer->Customer_Name} | Type: '{$customer->Customer_Type}' | Points: {$loyalty->current_balance} | Tier: {$loyalty->tier_level}");
        }

        $this->line('');

        // Check for customers with "Walk-in" in name
        $this->info('🚶 CUSTOMERS WITH "WALK-IN" IN NAME:');
        $walkInNamedCustomers = Customer::where('Customer_Name', 'like', '%Walk-in%')->get();
        foreach ($walkInNamedCustomers as $customer) {
            $loyaltyRecord = $customer->loyaltyPoints;
            $hasLoyalty = $loyaltyRecord ? 'YES' : 'NO';
            $points = $loyaltyRecord ? $loyaltyRecord->current_balance : 0;
            
            $this->line("  ID: {$customer->Customer_ID} | Name: {$customer->Customer_Name} | Type: '{$customer->Customer_Type}' | Loyalty: {$hasLoyalty} | Points: {$points}");
        }

        $this->line('');

        // Check for customers with Customer_Type = 'walk_in'
        $this->info('❌ CUSTOMERS WITH Customer_Type = "walk_in":');
        $walkInTypeCustomers = Customer::where('Customer_Type', 'walk_in')->get();
        if ($walkInTypeCustomers->isEmpty()) {
            $this->line('  No customers found with Customer_Type = "walk_in"');
        } else {
            foreach ($walkInTypeCustomers as $customer) {
                $loyaltyRecord = $customer->loyaltyPoints;
                $hasLoyalty = $loyaltyRecord ? 'YES' : 'NO';
                $points = $loyaltyRecord ? $loyaltyRecord->current_balance : 0;
                
                $this->line("  ID: {$customer->Customer_ID} | Name: {$customer->Customer_Name} | Type: '{$customer->Customer_Type}' | Loyalty: {$hasLoyalty} | Points: {$points}");
            }
        }

        $this->line('');
        $this->info('✅ Check complete! This will help identify why walk-in customers are still appearing.');
        
        return 0;
    }
}

