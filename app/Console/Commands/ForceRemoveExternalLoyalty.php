<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoyaltyPoint;
use App\Models\Customer;

class ForceRemoveExternalLoyalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:force-remove-external';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force remove all loyalty records for external customers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚨 Force removing all external customer loyalty records...');

        // Find all loyalty records for external customers
        $externalLoyaltyRecords = LoyaltyPoint::whereHas('customer', function($query) {
            $query->where('Customer_Type', '!=', 'internal');
        })->get();

        if ($externalLoyaltyRecords->isEmpty()) {
            $this->info('✅ No external customer loyalty records found. System is clean!');
            return 0;
        }

        $this->warn("Found {$externalLoyaltyRecords->count()} loyalty records for external customers:");
        
        foreach ($externalLoyaltyRecords as $record) {
            $this->line("  - Customer: {$record->customer->Customer_Name} (ID: {$record->Customer_ID})");
            $this->line("    Type: {$record->customer->Customer_Type} | Points: {$record->points_earned} earned, {$record->current_balance} balance");
        }

        $this->warn("\n🚨 REMOVING ALL EXTERNAL CUSTOMER LOYALTY RECORDS...");
        
        $deletedCount = 0;
        foreach ($externalLoyaltyRecords as $record) {
            try {
                $customerName = $record->customer->Customer_Name;
                $record->delete();
                $deletedCount++;
                $this->info("  ✅ Deleted loyalty record for {$customerName}");
            } catch (\Exception $e) {
                $this->error("  ❌ Failed to delete loyalty record for {$record->customer->Customer_Name}: {$e->getMessage()}");
            }
        }

        $this->info("\n🎉 Force cleanup completed! Removed {$deletedCount} external customer loyalty records.");
        $this->info('Now only internal members will have loyalty records.');
        
        return 0;
    }
}

