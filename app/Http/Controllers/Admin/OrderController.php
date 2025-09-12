<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderDetails.product', 'staff'])->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderDetails.product', 'staff']);
        return view('admin.orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        $order->load(['customer', 'orderDetails.product', 'staff']);
        return view('admin.orders.receipt', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['customer', 'orderDetails.product', 'staff']);
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
