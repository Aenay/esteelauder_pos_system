<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\StaffPerformance;
use App\Services\PerformanceTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $performanceService;

    public function __construct(PerformanceTrackingService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    public function index()
    {
        // Generate performance data from actual orders if none exists
        if (StaffPerformance::count() == 0) {
            $this->performanceService->generatePerformanceFromOrders();
        }

        // Today's statistics
        $today = now()->toDateString();
        
        $todayStats = [
            'sales' => Order::whereDate('Order_Date', $today)->sum('Final_Amount') ?? 0,
            'orders' => Order::whereDate('Order_Date', $today)->count() ?? 0,
            'customers' => Customer::whereDate('created_at', $today)->count() ?? 0,
            'products' => Product::count() ?? 0,
        ];

        // Staff performance statistics using real data with null safety
        $topPerformer = $this->performanceService->getTopPerformers(1)->first();
        
        $staffStats = [
            'total_staff' => Staff::count() ?? 0,
            'active_today' => StaffPerformance::whereDate('performance_date', $today)->count() ?? 0,
            'top_performer' => $topPerformer,
        ];

        // Weekly sales data for chart
        $weeklySales = Order::selectRaw('DATE(Order_Date) as date, SUM(Final_Amount) as total_sales')
            ->whereBetween('Order_Date', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing staff using real data with null safety
        $topPerformers = $this->performanceService->getTopPerformers(5);
        
        // Ensure all staff have valid data
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
        })->filter(); // Remove any null entries

        // Recent orders with null safety
        $recentOrders = Order::with(['staff', 'customer'])
            ->orderBy('Order_Date', 'desc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                if ($order->staff) {
                    $order->staff->Staff_Name = $order->staff->Staff_Name ?? 'Unknown Staff';
                }
                if ($order->customer) {
                    $order->customer->Customer_Name = $order->customer->Customer_Name ?? 'Unknown Customer';
                }
                return $order;
            });

        return view('admin.dashboard', compact(
            'todayStats',
            'staffStats',
            'weeklySales',
            'topPerformers',
            'recentOrders'
        ));
    }
}
