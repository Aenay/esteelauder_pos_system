<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Staff;
use App\Models\StaffPerformance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerformanceTrackingService
{
    /**
     * Generate performance records from actual order data
     */
    public function generatePerformanceFromOrders($startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(30);
        }
        if (!$endDate) {
            $endDate = Carbon::now();
        }

        // Clear existing fake performance data
        $this->clearFakePerformanceData();

        // Get all staff members
        $staff = Staff::all();
        
        foreach ($staff as $member) {
            $this->generateStaffPerformance($member, $startDate, $endDate);
        }

        return true;
    }

    /**
     * Generate performance records for a specific staff member
     */
    private function generateStaffPerformance($staff, $startDate, $endDate)
    {
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Get orders for this staff member on this specific date
            $dailyOrders = Order::where('Staff_ID', $staff->Staff_ID)
                ->whereDate('Order_Date', $currentDate)
                ->get();

            if ($dailyOrders->count() > 0) {
                $this->createDailyPerformanceRecord($staff, $currentDate, $dailyOrders);
            }

            $currentDate->addDay();
        }
    }

    /**
     * Create a daily performance record based on actual orders
     */
    private function createDailyPerformanceRecord($staff, $date, $orders)
    {
        // Calculate actual sales from orders
        $actualSales = $orders->sum('Final_Amount');
        $ordersProcessed = $orders->count();
        
        // Get unique customers served
        $customersServed = $orders->unique('Customer_ID')->count();
        
        // Calculate daily sales target (you can customize this logic)
        $dailyTarget = $this->calculateDailyTarget($staff, $date);
        
        // Calculate customer satisfaction based on order amounts and frequency
        $customerSatisfaction = $this->calculateCustomerSatisfaction($orders);
        
        // Calculate performance rating based on target achievement
        $performanceRating = $this->calculatePerformanceRating($actualSales, $dailyTarget);
        
        // Generate notes based on actual performance
        $notes = $this->generatePerformanceNotes($orders, $actualSales, $dailyTarget);

        // Create or update performance record
        StaffPerformance::updateOrCreate(
            [
                'Staff_ID' => $staff->Staff_ID,
                'performance_date' => $date->format('Y-m-d')
            ],
            [
                'daily_sales_target' => $dailyTarget,
                'actual_sales' => $actualSales,
                'orders_processed' => $ordersProcessed,
                'customers_served' => $customersServed,
                'customer_satisfaction' => $customerSatisfaction,
                'performance_rating' => $performanceRating,
                'notes' => $notes
            ]
        );
    }

    /**
     * Calculate daily sales target based on staff performance history
     */
    private function calculateDailyTarget($staff, $date)
    {
        // Get average daily sales for this staff member in the last 30 days
        $avgDailySales = Order::where('Staff_ID', $staff->Staff_ID)
            ->whereBetween('Order_Date', [$date->copy()->subDays(30), $date->copy()->subDay()])
            ->selectRaw('DATE(Order_Date) as order_date, SUM(Final_Amount) as daily_total')
            ->groupBy('order_date')
            ->get()
            ->avg('daily_total');

        // If no history, use a default target
        if (!$avgDailySales) {
            $avgDailySales = 1000; // Default $1000 daily target
        }

        // Adjust target based on day of week (weekends might have higher targets)
        if ($date->isWeekend()) {
            $avgDailySales *= 1.2; // 20% higher on weekends
        }

        return round($avgDailySales, 2);
    }

    /**
     * Calculate customer satisfaction based on order data
     */
    private function calculateCustomerSatisfaction($orders)
    {
        if ($orders->isEmpty()) {
            return 3.0; // Neutral rating if no orders
        }

        $satisfaction = 3.0; // Base rating

        // Higher satisfaction for higher order values
        $avgOrderValue = $orders->avg('Final_Amount');
        if ($avgOrderValue > 200) {
            $satisfaction += 0.5;
        } elseif ($avgOrderValue > 100) {
            $satisfaction += 0.3;
        }

        // Higher satisfaction for more orders (busy day)
        if ($orders->count() > 10) {
            $satisfaction += 0.3;
        } elseif ($orders->count() > 5) {
            $satisfaction += 0.2;
        }

        // Higher satisfaction for weekend orders (typically better service)
        if ($orders->first()->Order_Date->isWeekend()) {
            $satisfaction += 0.2;
        }

        return min(5.0, max(1.0, round($satisfaction, 1)));
    }

    /**
     * Calculate performance rating based on target achievement
     */
    private function calculatePerformanceRating($actualSales, $target)
    {
        if ($target <= 0) {
            return 3; // Neutral rating if no target
        }

        $achievement = ($actualSales / $target) * 100;

        if ($achievement >= 120) return 5;      // Outstanding
        if ($achievement >= 100) return 4;      // Above average
        if ($achievement >= 80) return 3;       // Satisfactory
        if ($achievement >= 60) return 2;       // Below average
        return 1;                               // Unsatisfactory
    }

    /**
     * Generate performance notes based on actual data
     */
    private function generatePerformanceNotes($orders, $actualSales, $target)
    {
        $notes = [];

        // Target achievement note
        if ($target > 0) {
            $achievement = round(($actualSales / $target) * 100, 1);
            if ($achievement >= 100) {
                $notes[] = "Exceeded daily target by " . ($achievement - 100) . "%";
            } elseif ($achievement >= 80) {
                $notes[] = "Achieved " . $achievement . "% of daily target";
            } else {
                $notes[] = "Below target - achieved " . $achievement . "%";
            }
        }

        // Order count note
        $orderCount = $orders->count();
        if ($orderCount > 15) {
            $notes[] = "High volume day - processed " . $orderCount . " orders";
        } elseif ($orderCount > 8) {
            $notes[] = "Moderate volume - " . $orderCount . " orders processed";
        } else {
            $notes[] = "Processed " . $orderCount . " orders";
        }

        // Customer count note
        $customerCount = $orders->unique('Customer_ID')->count();
        if ($customerCount > 10) {
            $notes[] = "Served " . $customerCount . " unique customers";
        }

        // Special day note
        $firstOrder = $orders->first();
        if ($firstOrder && $firstOrder->Order_Date->isWeekend()) {
            $notes[] = "Weekend performance";
        }

        return implode('. ', $notes);
    }

    /**
     * Clear all fake performance data
     */
    private function clearFakePerformanceData()
    {
        // Remove all existing performance records
        StaffPerformance::truncate();
    }

    /**
     * Get performance summary for dashboard
     */
    public function getPerformanceSummary()
    {
        $today = Carbon::today();
        
        return [
            'today_total_sales' => StaffPerformance::whereDate('performance_date', $today)->sum('actual_sales'),
            'today_total_orders' => StaffPerformance::whereDate('performance_date', $today)->sum('orders_processed'),
            'monthly_avg_satisfaction' => StaffPerformance::whereMonth('performance_date', $today->month)
                ->whereYear('performance_date', $today->year)
                ->avg('customer_satisfaction'),
            'monthly_avg_rating' => StaffPerformance::whereMonth('performance_date', $today->month)
                ->whereYear('performance_date', $today->year)
                ->avg('performance_rating'),
        ];
    }

    /**
     * Get top performers based on actual sales data
     */
    public function getTopPerformers($limit = 5)
    {
        $topPerformers = Staff::with('department')
            ->withCount(['orders' => function($query) {
                $query->whereNotNull('Final_Amount');
            }])
            ->withSum(['orders' => function($query) {
                $query->whereNotNull('Final_Amount');
            }], 'Final_Amount')
            ->whereHas('orders', function($query) {
                $query->whereNotNull('Final_Amount');
            })
            ->orderByDesc('orders_sum_final_amount')
            ->limit($limit)
            ->get();

        // If no staff with orders, return staff with departments but no orders
        if ($topPerformers->isEmpty()) {
            $topPerformers = Staff::with('department')
                ->withCount('orders')
                ->withSum('orders', 'Final_Amount')
                ->limit($limit)
                ->get()
                ->map(function($staff) {
                    if (!$staff) return null;
                    
                    $staff->orders_sum_final_amount = $staff->orders_sum_final_amount ?? 0;
                    $staff->orders_count = $staff->orders_count ?? 0;
                    $staff->Staff_Name = $staff->Staff_Name ?? 'Unknown Staff';
                    
                    // Ensure department relationship is safe
                    if ($staff->department) {
                        $staff->department->Department_Name = $staff->department->Department_Name ?? 'Unknown Department';
                    }
                    
                    return $staff;
                })
                ->filter(); // Remove any null entries
        } else {
            // Ensure all staff have valid data even if they have orders
            $topPerformers = $topPerformers->map(function($staff) {
                if (!$staff) return null;
                
                $staff->Staff_Name = $staff->Staff_Name ?? 'Unknown Staff';
                $staff->orders_sum_final_amount = $staff->orders_sum_final_amount ?? 0;
                $staff->orders_count = $staff->orders_count ?? 0;
                
                // Ensure department relationship is safe
                if ($staff->department) {
                    $staff->department->Department_Name = $staff->department->Department_Name ?? 'Unknown Department';
                }
                
                return $staff;
            })
            ->filter(); // Remove any null entries
        }

        return $topPerformers;
    }
}
