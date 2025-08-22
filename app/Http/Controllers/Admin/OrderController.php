<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update($validated);
        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully');
    }
}
