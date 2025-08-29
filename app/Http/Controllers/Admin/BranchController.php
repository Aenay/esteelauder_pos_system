<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        // Remove problematic relationship counting since branch_id columns don't exist
        // $branches = Branch::withCount(['staff', 'orders'])
        //     ->orderBy('branch_name')
        //     ->paginate(15);

        $branches = Branch::orderBy('branch_name')->paginate(15);

        $stats = [
            'total_branches' => Branch::count(),
            'active_branches' => Branch::active()->count(),
            'inactive_branches' => Branch::inactive()->count(),
            'total_staff' => 0, // Set to 0 since relationship doesn't exist
            'total_orders' => 0, // Set to 0 since relationship doesn't exist
        ];

        return view('admin.branches.index', compact('branches', 'stats'));
    }

    public function create()
    {
        $statuses = ['active', 'inactive', 'maintenance', 'closed'];
        $cities = Branch::distinct()->pluck('city')->filter()->sort()->values();
        $states = Branch::distinct()->pluck('state')->filter()->sort()->values();

        return view('admin.branches.create', compact('statuses', 'cities', 'states'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_code' => 'required|string|max:20|unique:branches,branch_code',
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
            'opening_hours' => 'nullable|array',
            'status' => 'required|in:active,inactive,maintenance,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        
        // Generate branch code if not provided
        if (empty($data['branch_code'])) {
            $data['branch_code'] = 'BR' . strtoupper(Str::random(6));
        }

        // Process opening hours
        if (isset($data['opening_hours'])) {
            $openingHours = [];
            foreach ($data['opening_hours'] as $day => $hours) {
                if (!empty($hours['open']) && !empty($hours['close'])) {
                    $openingHours[$day] = [
                        'open' => $hours['open'],
                        'close' => $hours['close'],
                        'closed' => false
                    ];
                } else {
                    $openingHours[$day] = ['closed' => true];
                }
            }
            $data['opening_hours'] = $openingHours;
        }

        Branch::create($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully!');
    }

    public function show(Branch $branch)
    {
        // Remove problematic relationship loading since branch_id columns don't exist
        // $branch->load(['staff', 'orders']);
        
        // $recentOrders = $branch->orders()
        //     ->with('customer')
        //     ->latest()
        //     ->take(10)
        //     ->get();

        // $staffMembers = $branch->staff()
        //     ->with('department')
        //     ->orderBy('name')
        //     ->get();

        // For now, pass empty collections to avoid errors
        $recentOrders = collect();
        $staffMembers = collect();

        return view('admin.branches.show', compact('branch', 'recentOrders', 'staffMembers'));
    }

    public function edit(Branch $branch)
    {
        $statuses = ['active', 'inactive', 'maintenance', 'closed'];
        $cities = Branch::distinct()->pluck('city')->filter()->sort()->values();
        $states = Branch::distinct()->pluck('state')->filter()->sort()->values();

        return view('admin.branches.edit', compact('branch', 'statuses', 'cities', 'states'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'branch_code' => 'required|string|max:20|unique:branches,branch_code,' . $branch->id,
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
            'opening_hours' => 'nullable|array',
            'status' => 'required|in:active,inactive,maintenance,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Process opening hours
        if (isset($data['opening_hours'])) {
            $openingHours = [];
            foreach ($data['opening_hours'] as $day => $hours) {
                if (!empty($hours['open']) && !empty($hours['close'])) {
                    $openingHours[$day] = [
                        'open' => $hours['open'],
                        'close' => $hours['close'],
                        'closed' => false
                    ];
                } else {
                    $openingHours[$day] = ['closed' => true];
                }
            }
            $data['opening_hours'] = $openingHours;
        }

        $branch->update($data);

        return redirect()->route('admin.branches.show', $branch)
            ->with('success', 'Branch updated successfully!');
    }

    public function destroy(Branch $branch)
    {
        // Remove relationship checks since branch_id columns don't exist
        // if ($branch->staff()->count() > 0) {
        //     return redirect()->back()
        //         ->with('error', 'Cannot delete branch with assigned staff members.');
        // }

        // if ($branch->orders()->count() > 0) {
        //     return redirect()->back()
        //         ->with('error', 'Cannot delete branch with existing orders.');
        // }

        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully!');
    }

    public function analytics()
    {
        // Remove problematic relationship counting since branch_id columns don't exist
        // $branches = Branch::withCount(['staff', 'orders'])
        //     ->orderBy('orders_count', 'desc')
        //     ->get();

        $branches = Branch::orderBy('branch_name')->get();

        $stats = [
            'total_branches' => Branch::count(),
            'active_branches' => Branch::active()->count(),
            'total_staff' => 0, // Set to 0 since relationship doesn't exist
            'total_orders' => 0, // Set to 0 since relationship doesn't exist
            'avg_staff_per_branch' => 0, // Set to 0 since relationship doesn't exist
            'avg_orders_per_branch' => 0, // Set to 0 since relationship doesn't exist
        ];

        // $topPerformingBranches = Branch::withCount('orders')
        //     ->orderBy('orders_count', 'desc')
        //     ->take(5)
        //     ->get();

        $topPerformingBranches = Branch::take(5)->get();

        $branchStatusDistribution = Branch::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.branches.analytics', compact(
            'branches', 
            'stats', 
            'topPerformingBranches', 
            'branchStatusDistribution'
        ));
    }
}
