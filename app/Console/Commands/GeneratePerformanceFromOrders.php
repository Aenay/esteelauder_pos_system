<?php

namespace App\Console\Commands;

use App\Services\PerformanceTrackingService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GeneratePerformanceFromOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:generate 
                            {--start-date= : Start date for performance generation (Y-m-d format)}
                            {--end-date= : End date for performance generation (Y-m-d format)}
                            {--force : Force regeneration even if data exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate staff performance records from actual order data';

    /**
     * Execute the console command.
     */
    public function handle(PerformanceTrackingService $performanceService)
    {
        $this->info('🔄 Starting performance data generation from actual orders...');

        try {
            // Parse dates if provided
            $startDate = $this->option('start-date') ? Carbon::parse($this->option('start-date')) : null;
            $endDate = $this->option('end-date') ? Carbon::parse($this->option('end-date')) : null;

            if ($startDate && $endDate) {
                $this->info("📅 Generating performance data from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
            } else {
                $this->info('📅 Generating performance data for the last 30 days');
            }

            // Check if data already exists
            if (!$this->option('force') && \App\Models\StaffPerformance::count() > 0) {
                if (!$this->confirm('Performance data already exists. Do you want to regenerate it?')) {
                    $this->info('❌ Operation cancelled.');
                    return 0;
                }
            }

            // Generate performance data
            $performanceService->generatePerformanceFromOrders($startDate, $endDate);

            $this->info('✅ Performance data generated successfully!');
            $this->info('📊 You can now view the data in the admin dashboard.');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error generating performance data: ' . $e->getMessage());
            return 1;
        }
    }
}
