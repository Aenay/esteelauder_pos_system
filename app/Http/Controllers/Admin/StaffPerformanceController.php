<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPerformance;
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
        return view('admin.staff_performances.analytics');
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
