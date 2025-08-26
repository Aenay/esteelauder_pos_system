<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries
     */
    public function index()
    {
        $deliveries = Delivery::with(['supplier', 'deliveryDetails.product'])
            ->orderBy('Expected_Delivery_Date', 'asc')
            ->paginate(15);

        $stats = [
            'total' => Delivery::count(),
            'pending' => Delivery::where('Status', 'pending')->count(),
            'in_transit' => Delivery::where('Status', 'in_transit')->count(),
            'delivered' => Delivery::where('Status', 'delivered')->count(),
            'overdue' => Delivery::where('Status', 'pending')
                ->where('Expected_Delivery_Date', '<', now()->toDateString())
                ->count(),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'stats'));
    }

    /**
     * Show the form for creating a new delivery
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        $products = Product::orderBy('Product_Name')->get();
        
        return view('admin.deliveries.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created delivery
     */
    public function store(Request $request)
    {
        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Expected_Delivery_Date' => 'required|date|after:today',
            'Notes' => 'nullable|string|max:500',
            'Tracking_Number' => 'nullable|string|max:100',
            'Carrier' => 'nullable|string|max:100',
            'products' => 'required|array|min:1',
            'products.*.Product_ID' => 'required|exists:products,Product_ID',
            'products.*.Quantity_Ordered' => 'required|integer|min:1',
            'products.*.Unit_Cost' => 'required|numeric|min:0',
            'products.*.Notes' => 'nullable|string|max:200',
        ]);

        DB::beginTransaction();
        try {
            // Create delivery
            $delivery = Delivery::create([
                'Supplier_ID' => $request->Supplier_ID,
                'Delivery_Reference' => 'DEL-' . strtoupper(Str::random(8)),
                'Expected_Delivery_Date' => $request->Expected_Delivery_Date,
                'Status' => 'pending',
                'Notes' => $request->Notes,
                'Tracking_Number' => $request->Tracking_Number,
                'Carrier' => $request->Carrier,
                'Total_Amount' => 0,
            ]);

            $totalAmount = 0;

            // Create delivery details
            foreach ($request->products as $productData) {
                $totalCost = $productData['Quantity_Ordered'] * $productData['Unit_Cost'];
                $totalAmount += $totalCost;

                DeliveryDetail::create([
                    'Delivery_ID' => $delivery->Delivery_ID,
                    'Product_ID' => $productData['Product_ID'],
                    'Quantity_Ordered' => $productData['Quantity_Ordered'],
                    'Quantity_Received' => 0,
                    'Unit_Cost' => $productData['Unit_Cost'],
                    'Total_Cost' => $totalCost,
                    'Notes' => $productData['Notes'] ?? null,
                ]);
            }

            // Update delivery total amount
            $delivery->update(['Total_Amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('admin.deliveries.index')
                ->with('success', 'Delivery created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating delivery: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['supplier', 'deliveryDetails.product']);
        
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery
     */
    public function edit(Delivery $delivery)
    {
        if ($delivery->Status === 'delivered') {
            return redirect()->route('admin.deliveries.show', $delivery)
                ->with('error', 'Cannot edit a delivered delivery');
        }

        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        $products = Product::orderBy('Product_Name')->get();
        $delivery->load('deliveryDetails.product');

        return view('admin.deliveries.edit', compact('delivery', 'suppliers', 'products'));
    }

    /**
     * Update the specified delivery
     */
    public function update(Request $request, Delivery $delivery)
    {
        if ($delivery->Status === 'delivered') {
            return redirect()->route('admin.deliveries.show', $delivery)
                ->with('error', 'Cannot edit a delivered delivery');
        }

        $request->validate([
            'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
            'Expected_Delivery_Date' => 'required|date',
            'Status' => 'required|in:pending,in_transit,delivered,cancelled',
            'Notes' => 'nullable|string|max:500',
            'Tracking_Number' => 'nullable|string|max:100',
            'Carrier' => 'nullable|string|max:100',
            'Actual_Delivery_Date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $delivery->update($request->only([
                'Supplier_ID', 'Expected_Delivery_Date', 'Status', 'Notes',
                'Tracking_Number', 'Carrier', 'Actual_Delivery_Date'
            ]));

            // If status changed to delivered, update stock
            if ($request->Status === 'delivered' && $delivery->Status !== 'delivered') {
                $this->updateStockFromDelivery($delivery);
            }

            DB::commit();

            return redirect()->route('admin.deliveries.show', $delivery)
                ->with('success', 'Delivery updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating delivery: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified delivery
     */
    public function destroy(Delivery $delivery)
    {
        if ($delivery->Status === 'delivered') {
            return redirect()->route('admin.deliveries.index')
                ->with('error', 'Cannot delete a delivered delivery');
        }

        try {
            $delivery->delete();
            return redirect()->route('admin.deliveries.index')
                ->with('success', 'Delivery deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting delivery: ' . $e->getMessage());
        }
    }

    /**
     * Update product quantities received
     */
    public function updateQuantities(Request $request, Delivery $delivery)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->quantities as $detailId => $quantity) {
                $detail = DeliveryDetail::findOrFail($detailId);
                
                if ($quantity > $detail->Quantity_Ordered) {
                    throw new \Exception("Quantity received cannot exceed quantity ordered for product: " . $detail->product->Product_Name);
                }

                $detail->update(['Quantity_Received' => $quantity]);
            }

            // Check if all products are fully received
            $allReceived = $delivery->deliveryDetails()
                ->where('Quantity_Received', '>=', DB::raw('Quantity_Ordered'))
                ->count() === $delivery->deliveryDetails()->count();

            if ($allReceived && $delivery->Status !== 'delivered') {
                $delivery->update([
                    'Status' => 'delivered',
                    'Actual_Delivery_Date' => now(),
                ]);
                
                $this->updateStockFromDelivery($delivery);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quantities updated successfully!',
                'delivery_status' => $delivery->fresh()->Status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating quantities: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update stock when delivery is received
     */
    private function updateStockFromDelivery(Delivery $delivery)
    {
        foreach ($delivery->deliveryDetails as $detail) {
            $product = $detail->product;
            $quantityReceived = $detail->Quantity_Received;
            
            if ($quantityReceived > 0) {
                $product->updateStock($quantityReceived, 'add');
            }
        }
    }
}
