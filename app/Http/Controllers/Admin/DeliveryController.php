<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Order;
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
        $type = request()->query('type', 'supplier');

        $deliveries = Delivery::with(['supplier', 'order.customer', 'deliveryDetails.product'])
            ->when($type !== 'all', function ($q) use ($type) {
                $q->where('delivery_type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $base = Delivery::query();
        if ($type !== 'all') {
            $base->where('delivery_type', $type);
        }

        $stats = [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('Status', 'pending')->count(),
            'in_transit' => (clone $base)->where('Status', 'in_transit')->count(),
            'delivered' => (clone $base)->where('Status', 'delivered')->count(),
            'overdue' => (clone $base)->where('Status', 'pending')
                ->where('Expected_Delivery_Date', '<', now()->toDateString())
                ->count(),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'stats', 'type'));
    }

    /**
     * Show the form for creating a new delivery
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        $products = Product::orderBy('Product_Name')->get();
        $orders = Order::with('customer')->orderBy('Order_Date', 'desc')->limit(100)->get();
        
        return view('admin.deliveries.create', compact('suppliers', 'products', 'orders'));
    }

    /**
     * Store a newly created delivery
     */
    public function store(Request $request)
    {
        // Base validation
        $request->validate([
            'delivery_type' => 'required|in:supplier,customer',
            'Expected_Delivery_Date' => 'required|date|after:today',
            'Notes' => 'nullable|string|max:500',
            'Carrier' => 'nullable|string|max:100',
        ]);

        // Conditional validation
        if ($request->delivery_type === 'supplier') {
            $request->validate([
                'Supplier_ID' => 'required|exists:suppliers,Supplier_ID',
                'products' => 'required|array|min:1',
                'products.*.Product_ID' => 'required|exists:products,Product_ID',
                'products.*.Quantity_Ordered' => 'required|integer|min:1',
                'products.*.Unit_Cost' => 'required|numeric|min:0',
                'products.*.Notes' => 'nullable|string|max:200',
            ]);
        } else { // customer delivery
            $request->validate([
                'Order_ID' => 'required|exists:orders,Order_ID',
            ]);
        }

        DB::beginTransaction();
        try {
            // Generate tracking number
            $trackingNumber = 'TRK-' . strtoupper(Str::random(12));

            // Create delivery
            $delivery = Delivery::create([
                'Supplier_ID' => $request->delivery_type === 'supplier' ? $request->Supplier_ID : null,
                'Order_ID' => $request->delivery_type === 'customer' ? $request->Order_ID : null,
                'delivery_type' => $request->delivery_type,
                'Delivery_Reference' => 'DEL-' . strtoupper(Str::random(8)),
                'Expected_Delivery_Date' => $request->Expected_Delivery_Date,
                'Status' => 'pending',
                'Notes' => $request->Notes,
                'Tracking_Number' => $trackingNumber,
                'Carrier' => $request->Carrier,
                'Total_Amount' => 0,
            ]);

            $totalAmount = 0;

            if ($request->delivery_type === 'supplier') {
                // Create delivery details from manual products
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
            } else {
                // Customer delivery: copy order items into delivery details
                $order = Order::with(['orderDetails.product'])->findOrFail($request->Order_ID);
                foreach ($order->orderDetails as $item) {
                    $unitCost = $item->product->Price ?? 0; // use product price as unit cost
                    $totalCost = $unitCost * $item->Quantity;
                    $totalAmount += $totalCost;

                    DeliveryDetail::create([
                        'Delivery_ID' => $delivery->Delivery_ID,
                        'Product_ID' => $item->Product_ID,
                        'Quantity_Ordered' => $item->Quantity,
                        'Quantity_Received' => 0,
                        'Unit_Cost' => $unitCost,
                        'Total_Cost' => $totalCost,
                        'Notes' => null,
                    ]);
                }
            }

            // Update delivery total amount
            $delivery->update(['Total_Amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('admin.deliveries.show', $delivery)
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
        $delivery->load(['supplier', 'order.customer', 'deliveryDetails.product']);
        
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

        $validationRules = [
            'Expected_Delivery_Date' => 'required|date',
            'Status' => 'required|in:pending,in_transit,delivered,cancelled',
            'Notes' => 'nullable|string|max:500',
            'Tracking_Number' => 'nullable|string|max:100',
            'Carrier' => 'nullable|string|max:100',
            'Actual_Delivery_Date' => 'nullable|date',
        ];

        // Only require Supplier_ID for supplier deliveries
        if ($delivery->delivery_type === 'supplier') {
            $validationRules['Supplier_ID'] = 'required|exists:suppliers,Supplier_ID';
        }

        $request->validate($validationRules);

        DB::beginTransaction();
        try {
            $updateData = $request->only([
                'Expected_Delivery_Date', 'Status', 'Notes',
                'Tracking_Number', 'Carrier', 'Actual_Delivery_Date'
            ]);

            // Only include Supplier_ID for supplier deliveries
            if ($delivery->delivery_type === 'supplier') {
                $updateData['Supplier_ID'] = $request->Supplier_ID;
            }

            $delivery->update($updateData);

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

        // Only supplier deliveries can update quantities received
        if ($delivery->delivery_type !== 'supplier') {
            return response()->json([
                'success' => false,
                'message' => 'Updating quantities is allowed only for supplier deliveries.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($request->quantities as $detailId => $quantity) {
                $detail = DeliveryDetail::findOrFail($detailId);

                // Ensure the detail belongs to the provided delivery
                if ($detail->Delivery_ID !== $delivery->Delivery_ID) {
                    throw new \Exception("Invalid detail ID for this delivery.");
                }
                
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
        // Only supplier deliveries affect inventory stock
        if ($delivery->delivery_type !== 'supplier') {
            return;
        }

        foreach ($delivery->deliveryDetails as $detail) {
            $product = $detail->product;
            $quantityReceived = $detail->Quantity_Received;
            
            if ($quantityReceived > 0) {
                $product->updateStock($quantityReceived, 'add');
            }
        }
    }
}
