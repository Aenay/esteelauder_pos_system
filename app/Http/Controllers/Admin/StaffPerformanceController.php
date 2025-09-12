<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPerformance;
use App\Models\Order;
use App\Services\PerformanceTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffPerformanceController extends Controller
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

        $staffPerformances = StaffPerformance::with('staff.department')
            ->whereHas('staff') // Only include records with valid staff
            ->orderBy('performance_date', 'desc')
            ->paginate(15);

        $topPerformers = $this->performanceService->getTopPerformers(5);
        $performanceStats = $this->performanceService->getPerformanceSummary();

        return view('admin.staff_performances.index', compact(
            'staffPerformances',
            'topPerformers',
            'performanceStats'
        ));
    }

    public function create()
    {
        $staff = Staff::with('department')->get();
        return view('admin.staff_performances.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Staff_ID' => 'required|exists:staff,Staff_ID',
            'performance_date' => 'required|date',
            'daily_sales_target' => 'required|numeric|min:0',
            'actual_sales' => 'required|numeric|min:0',
            'orders_processed' => 'required|integer|min:0',
            'customers_served' => 'required|integer|min:0',
            'customer_satisfaction' => 'required|numeric|min:0|max:5',
            'performance_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        // Check if performance record already exists for this staff and date
        $existingPerformance = StaffPerformance::where('Staff_ID', $request->Staff_ID)
            ->whereDate('performance_date', $request->performance_date)
            ->first();

        if ($existingPerformance) {
            return back()->withErrors(['performance_date' => 'Performance record already exists for this staff and date.']);
        }

        StaffPerformance::create($request->all());

        return redirect()->route('admin.staff-performances.index')
            ->with('success', 'Staff performance record created successfully.');
    }

    public function show(StaffPerformance $staffPerformance)
    {
        $staffPerformance->load('staff.department');
        return view('admin.staff_performances.show', compact('staffPerformance'));
    }

    public function edit(StaffPerformance $staffPerformance)
    {
        $staff = Staff::with('department')->get();
        return view('admin.staff_performances.edit', compact('staffPerformance', 'staff'));
    }

    public function update(Request $request, StaffPerformance $staffPerformance)
    {
        $request->validate([
            'Staff_ID' => 'required|exists:staff,Staff_ID',
            'performance_date' => 'required|date',
            'daily_sales_target' => 'required|numeric|min:0',
            'actual_sales' => 'required|numeric|min:0',
            'orders_processed' => 'required|integer|min:0',
            'customers_served' => 'required|integer|min:0',
            'customer_satisfaction' => 'required|numeric|min:0|max:5',
            'performance_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $staffPerformance->update($request->all());

        return redirect()->route('admin.staff-performances.index')
            ->with('success', 'Staff performance record updated successfully.');
    }

    public function destroy(StaffPerformance $staffPerformance)
    {
        $staffPerformance->delete();

        return redirect()->route('admin.staff-performances.index')
            ->with('success', 'Staff performance record deleted successfully.');
    }

    public function staffPerformance($staffId)
    {
        $staff = Staff::with('department')->findOrFail($staffId);
        $performances = $staff->performances()
            ->orderBy('performance_date', 'desc')
            ->paginate(15);

        $monthlyStats = $this->getStaffMonthlyStats($staffId);

        return view('admin.staff_performances.staff_detail', compact('staff', 'performances', 'monthlyStats'));
    }

    /**
     * Display analytics view
     */
    public function analytics()
    {
        // Totals
        $totalStaff = Staff::count();

        // Average performance rating across all records
        $avgPerformance = round((float) (StaffPerformance::avg('performance_rating') ?? 0), 1);

        // Best performing staff (by service method using orders & performance)
        $top = $this->performanceService->getTopPerformers(1)->first();
        $bestPerformer = $top ? ($top->Staff_Name ?? ($top->name ?? 'N/A')) : 'N/A';

        // Highest satisfaction (max average satisfaction per staff over last 30 days)
        $since = now()->subDays(30)->toDateString();
        $highestSatisfaction = (float) (StaffPerformance::whereDate('performance_date', '>=', $since)
            ->select(DB::raw('AVG(customer_satisfaction) as avg_sat'))
            ->value('avg_sat') ?? 0);
        $highestSatisfaction = round($highestSatisfaction, 1);

        // Most Orders (staff with most orders in last 30 days)
        $mostOrdersStaff = Order::whereDate('Order_Date', '>=', $since)
            ->select('Staff_ID', DB::raw('COUNT(*) as orders_count'))
            ->groupBy('Staff_ID')
            ->orderByDesc('orders_count')
            ->with('staff')
            ->first();
        $mostOrders = $mostOrdersStaff && $mostOrdersStaff->staff ? ($mostOrdersStaff->staff->Staff_Name ?? 'N/A') : 'N/A';

        // Success rate: average target achievement % over last 30 days
        $perfWindow = StaffPerformance::whereDate('performance_date', '>=', $since)->get();
        $successRate = 0.0;
        if ($perfWindow->isNotEmpty()) {
            $sum = 0.0; $count = 0;
            foreach ($perfWindow as $rec) {
                if ($rec->daily_sales_target > 0) {
                    $sum += ($rec->actual_sales / $rec->daily_sales_target) * 100.0;
                    $count++;
                }
            }
            $successRate = $count > 0 ? round($sum / $count, 1) : 0.0;
        }

        // Top department by average performance in last 30 days (optional)
        $topDepartment = 'N/A';
        $deptRow = StaffPerformance::whereDate('performance_date', '>=', $since)
            ->join('staff', 'staff.Staff_ID', '=', 'staff_performances.Staff_ID')
            ->leftJoin('departments', 'departments.Department_ID', '=', 'staff.Department_ID')
            ->select('departments.Department_Name', DB::raw('AVG(staff_performances.performance_rating) as avg_rating'))
            ->groupBy('departments.Department_Name')
            ->orderByDesc('avg_rating')
            ->first();
        if ($deptRow && $deptRow->Department_Name) {
            $topDepartment = $deptRow->Department_Name;
        }

        return view('admin.staff_performances.analytics', [
            'totalStaff' => $totalStaff,
            'avgPerformance' => $avgPerformance,
            'topDepartment' => $topDepartment,
            'successRate' => $successRate,
            'bestPerformer' => $bestPerformer,
            'highestSatisfaction' => $highestSatisfaction,
            'mostOrders' => $mostOrders,
        ]);
    }

    /**
     * Regenerate performance data from actual orders
     */
    public function regenerateFromOrders(Request $request)
    {
        try {
            $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
            $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

            $this->performanceService->generatePerformanceFromOrders($startDate, $endDate);

            return redirect()->route('admin.staff-performances.index')
                ->with('success', 'Performance data regenerated from actual order data successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.staff-performances.index')
                ->with('error', 'Error regenerating performance data: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time performance data for a specific date
     */
    public function getRealTimePerformance(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = StaffPerformance::with('staff.department');
        
        if ($startDate && $endDate) {
            $query->whereBetween('performance_date', [$startDate, $endDate]);
        } else {
            $query->whereDate('performance_date', $date);
        }
        
        $performanceData = $query->get();

        $summary = [
            'date' => $date,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_sales' => $performanceData->sum('actual_sales'),
            'total_orders' => $performanceData->sum('orders_processed'),
            'total_customers' => $performanceData->sum('customers_served'),
            'avg_satisfaction' => $performanceData->avg('customer_satisfaction'),
            'avg_rating' => $performanceData->avg('performance_rating'),
            'staff_count' => $performanceData->count(),
            'total_records' => $performanceData->count(),
            'avg_daily_sales' => $performanceData->avg('actual_sales'),
            'target_achievement_rate' => $this->calculateTargetAchievementRate($performanceData),
            'performance_data' => $performanceData
        ];

        return response()->json($summary);
    }

    /**
     * Time-series trends for last N days
     */
    public function getTrends(Request $request)
    {
        $days = (int) ($request->get('days', 30));
        $days = $days > 180 ? 180 : max($days, 7);

        $startDate = now()->subDays($days - 1)->startOfDay();

        $raw = StaffPerformance::select(
                DB::raw('DATE(performance_date) as date'),
                DB::raw('SUM(actual_sales) as total_sales'),
                DB::raw('SUM(orders_processed) as total_orders'),
                DB::raw('AVG(performance_rating) as avg_rating'),
                DB::raw('SUM(daily_sales_target) as total_target')
            )
            ->whereDate('performance_date', '>=', $startDate)
            ->groupBy(DB::raw('DATE(performance_date)'))
            ->orderBy(DB::raw('DATE(performance_date)'))
            ->get();

        // Normalize to include all dates
        $byDate = $raw->keyBy(function ($row) {
            return Carbon::parse($row->date)->toDateString();
        });

        $labels = [];
        $sales = [];
        $orders = [];
        $rating = [];
        $targetAchievement = [];

        for ($d = 0; $d < $days; $d++) {
            $date = $startDate->copy()->addDays($d)->toDateString();
            $labels[] = Carbon::parse($date)->format('M d');
            $row = $byDate->get($date);
            $sales[] = $row->total_sales ?? 0;
            $orders[] = $row->total_orders ?? 0;
            $rating[] = $row->avg_rating ? round($row->avg_rating, 2) : 0;
            $targetAchievement[] = ($row && $row->total_target > 0)
                ? round(($row->total_sales / $row->total_target) * 100, 1)
                : 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                'sales' => $sales,
                'orders' => $orders,
                'rating' => $rating,
                'targetAchievement' => $targetAchievement,
            ],
        ]);
    }

    /**
     * Order-based trends for last N days (real data from orders)
     */
    public function getOrderTrends(Request $request)
    {
        $days = (int) ($request->get('days', 30));
        $days = $days > 180 ? 180 : max($days, 7);

        $startDate = now()->subDays($days - 1)->startOfDay();

        $raw = DB::table('orders')
            ->select(
                DB::raw('DATE(Order_Date) as date'),
                DB::raw('SUM(Final_Amount) as total_sales'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->whereDate('Order_Date', '>=', $startDate)
            ->groupBy(DB::raw('DATE(Order_Date)'))
            ->orderBy(DB::raw('DATE(Order_Date)'))
            ->get();

        $byDate = $raw->keyBy(function ($row) {
            return \Carbon\Carbon::parse($row->date)->toDateString();
        });

        $labels = [];
        $sales = [];
        $orders = [];

        for ($d = 0; $d < $days; $d++) {
            $date = $startDate->copy()->addDays($d)->toDateString();
            $labels[] = \Carbon\Carbon::parse($date)->format('M d');
            $row = $byDate->get($date);
            $sales[] = $row->total_sales ?? 0;
            $orders[] = $row->total_orders ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                'sales' => $sales,
                'orders' => $orders,
            ],
        ]);
    }

    private function getStaffMonthlyStats($staffId)
    {
        return StaffPerformance::where('Staff_ID', $staffId)
            ->whereYear('performance_date', now()->year)
            ->selectRaw('
                MONTH(performance_date) as month,
                SUM(actual_sales) as total_sales,
                SUM(orders_processed) as total_orders,
                AVG(customer_satisfaction) as avg_satisfaction,
                AVG(performance_rating) as avg_rating
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function calculateTargetAchievementRate($performanceData)
    {
        if ($performanceData->isEmpty()) {
            return 0;
        }

        $totalAchievement = 0;
        $validRecords = 0;

        foreach ($performanceData as $record) {
            if ($record->daily_sales_target > 0) {
                $achievement = ($record->actual_sales / $record->daily_sales_target) * 100;
                $totalAchievement += $achievement;
                $validRecords++;
            }
        }

        return $validRecords > 0 ? round($totalAchievement / $validRecords, 1) : 0;
    }
}
