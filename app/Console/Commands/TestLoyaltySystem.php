<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LoyaltyService;
use App\Models\Customer;
use App\Models\Order;

class TestLoyaltySystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:test {customer_id} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the loyalty system by simulating a purchase';

    protected $loyaltyService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LoyaltyService $loyaltyService)
    {
        parent::__construct();
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customerId = $this->argument('customer_id');
        $amount = (float) $this->argument('amount');

        $customer = Customer::find($customerId);
        
        if (!$customer) {
            $this->error("Customer with ID {$customerId} not found!");
            return 1;
        }

        $this->info("Testing loyalty system for customer: {$customer->Customer_Name}");
        $this->info("Customer Type: {$customer->Customer_Type}");
        $this->info("Purchase Amount: $" . number_format($amount, 2));

        // Check eligibility
        $isEligible = $this->loyaltyService->isCustomerEligibleForLoyalty($customerId);
        $this->info("Eligible for loyalty points: " . ($isEligible ? 'Yes' : 'No'));

        if ($isEligible) {
            // Calculate points
            $points = $this->loyaltyService->calculatePointsForPurchase($amount);
            $this->info("Points that would be awarded: {$points}");
            
            // Get current loyalty status
            $loyaltySummary = $this->loyaltyService->getCustomerLoyaltySummary($customerId);
            $this->info("Current loyalty status:");
            $this->info("  - Tier: {$loyaltySummary['tier_level']}");
            $this->info("  - Current Balance: {$loyaltySummary['points_balance']} points");
            $this->info("  - Total Earned: {$loyaltySummary['total_earned']} points");
        } else {
            $this->warn("Customer is not eligible for loyalty points (walk-in customer)");
        }

        $this->info("\nLoyalty System Test Complete!");
        return 0;
    }
}
