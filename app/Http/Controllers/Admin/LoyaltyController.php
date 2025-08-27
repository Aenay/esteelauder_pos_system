<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoyaltyController extends Controller
{
    /**
     * Display a listing of loyalty records
     */
    public function index()
    {
        $loyaltyRecords = LoyaltyPoint::with('customer')
            ->orderBy('last_activity_date', 'desc')
            ->paginate(15);

        $stats = [
            'total_customers' => LoyaltyPoint::count(),
            'bronze_members' => LoyaltyPoint::where('tier_level', 'bronze')->count(),
            'silver_members' => LoyaltyPoint::where('tier_level', 'silver')->count(),
            'gold_members' => LoyaltyPoint::where('tier_level', 'gold')->count(),
            'platinum_members' => LoyaltyPoint::where('tier_level', 'platinum')->count(),
            'total_points_issued' => LoyaltyPoint::sum('points_earned'),
            'total_points_redeemed' => LoyaltyPoint::sum('points_used'),
            'automatic_points_awarded' => LoyaltyPoint::where('notes', 'like', 'Purchase reward for Order%')->sum('points_earned'),
        ];

        return view('admin.loyalty.index', compact('loyaltyRecords', 'stats'));
    }

    /**
     * Show the form for creating a new loyalty record
     */
    public function create()
    {
        $customers = Customer::whereDoesntHave('loyaltyPoints')->get();
        return view('admin.loyalty.create', compact('customers'));
    }

    /**
     * Store a newly created loyalty record
     */
    public function store(Request $request)
    {
        $request->validate([
            'Customer_ID' => 'required|exists:customers,Customer_ID|unique:loyalty_points,Customer_ID',
            'points_earned' => 'required|integer|min:0',
            'tier_level' => 'required|in:bronze,silver,gold,platinum',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $loyalty = LoyaltyPoint::create([
                'Customer_ID' => $request->Customer_ID,
                'points_earned' => $request->points_earned,
                'current_balance' => $request->points_earned,
                'tier_level' => $request->tier_level,
                'last_activity_date' => now(),
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.loyalty.index')
                ->with('success', 'Loyalty record created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating loyalty record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified loyalty record
     */
    public function show(LoyaltyPoint $loyalty)
    {
        $loyalty->load('customer');
        return view('admin.loyalty.show', compact('loyalty'));
    }

    /**
     * Show the form for editing the specified loyalty record
     */
    public function edit(LoyaltyPoint $loyalty)
    {
        $customers = Customer::all();
        return view('admin.loyalty.edit', compact('loyalty', 'customers'));
    }

    /**
     * Update the specified loyalty record
     */
    public function update(Request $request, LoyaltyPoint $loyalty)
    {
        $request->validate([
            'Customer_ID' => 'required|exists:customers,Customer_ID',
            'points_earned' => 'required|integer|min:0',
            'points_used' => 'required|integer|min:0',
            'tier_level' => 'required|in:bronze,silver,gold,platinum',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate that points_used doesn't exceed points_earned
        if ($request->points_used > $request->points_earned) {
            return back()->withErrors(['points_used' => 'Points used cannot exceed points earned.']);
        }

        DB::beginTransaction();
        try {
            $loyalty->update([
                'Customer_ID' => $request->Customer_ID,
                'points_earned' => $request->points_earned,
                'points_used' => $request->points_used,
                'current_balance' => $request->points_earned - $request->points_used,
                'tier_level' => $request->tier_level,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.loyalty.show', $loyalty)
                ->with('success', 'Loyalty record updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating loyalty record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified loyalty record
     */
    public function destroy(LoyaltyPoint $loyalty)
    {
        try {
            $loyalty->delete();
            return redirect()->route('admin.loyalty.index')
                ->with('success', 'Loyalty record deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting loyalty record: ' . $e->getMessage());
        }
    }

    /**
     * Add points to a customer's loyalty account
     */
    public function addPoints(Request $request, LoyaltyPoint $loyalty)
    {
        $request->validate([
            'points_to_add' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $loyalty->addPoints($request->points_to_add, $request->notes);

        return redirect()->route('admin.loyalty.show', $loyalty)
            ->with('success', 'Points added successfully!');
    }

    /**
     * Use points from a customer's loyalty account
     */
    public function usePoints(Request $request, LoyaltyPoint $loyalty)
    {
        $request->validate([
            'points_to_use' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($loyalty->usePoints($request->points_to_use, $request->notes)) {
            return redirect()->route('admin.loyalty.show', $loyalty)
                ->with('success', 'Points used successfully!');
        } else {
            return back()->withErrors(['points_to_use' => 'Insufficient points balance.']);
        }
    }

    /**
     * Test loyalty point calculation
     */
    public function testCalculation(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = $request->amount;
        $points = floor($amount / 10);
        
        return response()->json([
            'amount' => $amount,
            'points_awarded' => $points,
            'calculation' => "1 point for every $10 spent",
            'next_threshold' => $points > 0 ? ($points + 1) * 10 : 10,
            'remaining_for_next_point' => $points > 0 ? (($points + 1) * 10) - $amount : 10 - $amount
        ]);
    }

    /**
     * Show loyalty analytics
     */
    public function analytics()
    {
        $tierDistribution = LoyaltyPoint::selectRaw('tier_level, COUNT(*) as count')
            ->groupBy('tier_level')
            ->get();

        $monthlyPoints = LoyaltyPoint::selectRaw('MONTH(created_at) as month, SUM(points_earned) as total_earned, SUM(points_used) as total_used')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.loyalty.analytics', compact('tierDistribution', 'monthlyPoints'));
    }
}

