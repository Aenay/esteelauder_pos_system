<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoyaltyPoint;
use App\Models\Customer;

class CleanupWalkInLoyalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:cleanup-external';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove loyalty records for external customers (non-members)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting cleanup of external customer loyalty records...');

        // Find all loyalty records for external customers (including walk-ins)
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
            $this->line("    Points: {$record->points_earned} earned, {$record->current_balance} balance");
        }

        if ($this->confirm('Do you want to remove these walk-in customer loyalty records?')) {
            $deletedCount = 0;
            
            foreach ($walkInLoyaltyRecords as $record) {
                try {
                    $record->delete();
                    $deletedCount++;
                    $this->info("  ✅ Deleted loyalty record for {$record->customer->Customer_Name}");
                } catch (\Exception $e) {
                    $this->error("  ❌ Failed to delete loyalty record for {$record->customer->Customer_Name}: {$e->getMessage()}");
                }
            }

            $this->info("\n🎉 Cleanup completed! Removed {$deletedCount} external customer loyalty records.");
            $this->info('Now only internal members will have loyalty records.');
        } else {
            $this->info('Cleanup cancelled. External customer loyalty records remain.');
        }

        return 0;
    }
}
