<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierOrderController extends Controller
{
    /**
     * Display a listing of supplier orders
     */
    public function index()
    {
        $supplierOrders = Order::with(['customer', 'orderDetails.product'])
            ->where('customer_type', 'supplier')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.supplier-orders.index', compact('supplierOrders'));
    }

    /**
     * Show the form for creating a new supplier order
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        $products = Product::orderBy('Product_Name')->get();
        
        return view('admin.supplier-orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created supplier order
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,Supplier_ID',
            'expected_delivery_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,Product_ID',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $supplier = Supplier::findOrFail($request->supplier_id);
            
            // Calculate totals
            $subtotal = 0;
            foreach ($request->products as $productData) {
                $subtotal += $productData['quantity'] * $productData['unit_cost'];
            }

            // Create customer record for supplier if it doesn't exist
            $customer = \App\Models\Customer::firstOrCreate(
                ['Customer_Email' => 'supplier_' . $supplier->Supplier_ID . '@supplier.com'],
                [
                    'Customer_Name' => $supplier->Supplier_Name,
                    'Customer_Phone' => $supplier->Supplier_Phone,
                    'Customer_Address' => $supplier->Supplier_Address,
                    'Customer_Type' => 'supplier',
                    'Registration_Date' => now(),
                ]
            );

            // Create supplier order
            $order = Order::create([
                'Order_Date' => now(),
                'Staff_ID' => auth()->id(), // Current admin user
                'Customer_ID' => $customer->Customer_ID,
                'customer_type' => 'supplier',
                'Promotion_ID' => null,
                'Subtotal' => $subtotal,
                'Discount_Amount' => 0,
                'Final_Amount' => $subtotal,
                'payment_method' => 'purchase_order',
                'payment_status' => 'pending',
                'transaction_id' => 'SUP-' . time() . rand(1000, 9999),
            ]);

            // Create order details
            foreach ($request->products as $productData) {
                OrderDetail::create([
                    'Order_ID' => $order->Order_ID,
                    'Product_ID' => $productData['product_id'],
                    'Quantity' => $productData['quantity'],
                ]);
            }

            // Create delivery record
            $delivery = Delivery::create([
                'Supplier_ID' => $request->supplier_id,
                'Delivery_Reference' => 'DEL-' . strtoupper(Str::random(8)),
                'Expected_Delivery_Date' => $request->expected_delivery_date,
                'Status' => 'pending',
                'Notes' => $request->notes,
                'Total_Amount' => $subtotal,
            ]);

            // Create delivery details
            foreach ($request->products as $productData) {
                $totalCost = $productData['quantity'] * $productData['unit_cost'];
                
                DeliveryDetail::create([
                    'Delivery_ID' => $delivery->Delivery_ID,
                    'Product_ID' => $productData['product_id'],
                    'Quantity_Ordered' => $productData['quantity'],
                    'Quantity_Received' => 0,
                    'Unit_Cost' => $productData['unit_cost'],
                    'Total_Cost' => $totalCost,
                    'Notes' => null,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.supplier-orders.index')
                ->with('success', 'Supplier order created successfully with delivery tracking.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Display the specified supplier order
     */
    public function show(Order $supplierOrder)
    {
        $supplierOrder->load(['customer', 'orderDetails.product', 'staff']);
        
        // Get related delivery
        $delivery = Delivery::where('Supplier_ID', $supplierOrder->customer->Customer_ID)
            ->where('Expected_Delivery_Date', $supplierOrder->Order_Date)
            ->with('deliveryDetails.product')
            ->first();

        return view('admin.supplier-orders.show', compact('supplierOrder', 'delivery'));
    }

    /**
     * Show the form for editing the specified supplier order
     */
    public function edit(Order $supplierOrder)
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        $products = Product::orderBy('Product_Name')->get();
        
        $supplierOrder->load(['customer', 'orderDetails.product']);
        
        return view('admin.supplier-orders.edit', compact('supplierOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified supplier order
     */
    public function update(Request $request, Order $supplierOrder)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,refunded,failed',
            'notes' => 'nullable|string|max:500',
        ]);

        $supplierOrder->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.supplier-orders.show', $supplierOrder)
            ->with('success', 'Supplier order updated successfully');
    }

    /**
     * Remove the specified supplier order
     */
    public function destroy(Order $supplierOrder)
    {
        DB::beginTransaction();
        try {
            // Delete related delivery details and delivery
            $delivery = Delivery::where('Supplier_ID', $supplierOrder->customer->Customer_ID)
                ->where('Expected_Delivery_Date', $supplierOrder->Order_Date)
                ->first();
            
            if ($delivery) {
                $delivery->deliveryDetails()->delete();
                $delivery->delete();
            }

            // Delete order details and order
            $supplierOrder->orderDetails()->delete();
            $supplierOrder->delete();

            DB::commit();

            return redirect()->route('admin.supplier-orders.index')
                ->with('success', 'Supplier order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

