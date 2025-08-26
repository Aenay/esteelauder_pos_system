<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('deliveries')->get();
        
        // Calculate statistics
        $activeDeliveries = $suppliers->sum('active_deliveries');
        $totalDeliveries = $suppliers->sum('total_deliveries');
        
        return view('admin.suppliers.index', compact('suppliers', 'activeDeliveries', 'totalDeliveries'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Supplier_Name' => 'required|string|max:255',
            'Supplier_Phone' => 'nullable|string|max:20',
            'Supplier_Address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Supplier::create($validated);
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('deliveries');
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'Supplier_Name' => 'required|string|max:255',
            'Supplier_Phone' => 'nullable|string|max:20',
            'Supplier_Address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($validated);
        return redirect()->route('admin.suppliers.show', $supplier)
            ->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has any deliveries
        if ($supplier->total_deliveries > 0) {
            return redirect()->route('admin.suppliers.show', $supplier)
                ->with('error', 'Cannot delete supplier with delivery history');
        }

        try {
            $supplier->delete();
            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.suppliers.show', $supplier)
                ->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }
}
