<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'purchaseDetails.product'])->get();
        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0'
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $validated['supplier_id'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'status' => 'pending'
        ]);

        foreach ($validated['products'] as $product) {
            $purchaseOrder->purchaseDetails()->create([
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price']
            ]);
        }

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Purchase order created successfully');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'purchaseDetails.product']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function receiveStock(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'This purchase order has already been processed');
        }

        foreach ($purchaseOrder->purchaseDetails as $detail) {
            $product = $detail->product;
            $product->stock += $detail->quantity;
            $product->save();
        }

        $purchaseOrder->update(['status' => 'received']);

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Stock received successfully');
    }
}
