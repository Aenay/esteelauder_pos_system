<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // 'customer', 'supplier', or 'all'
        
        $query = Order::with(['customer.loyaltyPoints', 'orderDetails.product', 'staff']);
        
        // Filter by order type
        if ($type === 'customer') {
            $query->where('customer_type', '!=', 'supplier');
        } elseif ($type === 'supplier') {
            $query->where('customer_type', 'supplier');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        // Get statistics
        $stats = [
            'total' => Order::count(),
            'customer_orders' => Order::where('customer_type', '!=', 'supplier')->count(),
            'supplier_orders' => Order::where('customer_type', 'supplier')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'type', 'stats'));
    }

    public function show(Order $order)
    {
        $order = Order::with(['customer.loyaltyPoints', 'orderDetails.product', 'staff'])
        ->latest() // ORDER BY created_at DESC
        ->first();

        return view('admin.orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        $order->load(['customer.loyaltyPoints', 'orderDetails.product', 'staff']);
        return view('admin.orders.receipt', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['customer.loyaltyPoints', 'orderDetails.product', 'staff']);
        $paymentMethods = ['cash', 'card', 'paypal', 'apple'];
        $paymentStatuses = ['pending', 'completed', 'refunded', 'failed'];
        return view('admin.orders.edit', compact('order', 'paymentMethods', 'paymentStatuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|in:cash,card,paypal,apple',
            'payment_status' => 'required|in:pending,completed,refunded,failed',
        ]);

        $order->update($validated);
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully');
    }

    public function destroy(Order $order)
    {
        // Wrap in transaction to ensure data integrity
        DB::transaction(function () use ($order) {
            // If there are related details and cascade is not set at DB level,
            // ensure manual cleanup (safe even if cascade exists)
            $order->orderDetails()->delete();

            // Finally delete the order
            $order->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully');
    }
}
