<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index()
    {
        // Get the authenticated customer
        $customer = Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to view your orders.');
        }

        // Load customer with loyalty points
        $customer->load('loyaltyPoints');

        // Get customer's orders with related data
        $orders = Order::with(['orderDetails.product', 'staff'])
            ->where('Customer_ID', $customer->Customer_ID)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders.index', compact('orders', 'customer'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Get the authenticated customer
        $customer = Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to view your orders.');
        }

        // Ensure the order belongs to the authenticated customer
        if ($order->Customer_ID !== $customer->Customer_ID) {
            abort(403, 'You can only view your own orders.');
        }

        // Load the order with all related data
        $order->load(['customer.loyaltyPoints', 'orderDetails.product', 'staff']);

        return view('customer.orders.show', compact('order', 'customer'));
    }

    /**
     * Display customer's loyalty information.
     */
    public function loyalty()
    {
        // Get the authenticated customer
        $customer = Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to view your loyalty information.');
        }

        // Load loyalty points
        $customer->load('loyaltyPoints');

        return view('customer.loyalty.index', compact('customer'));
    }
}
