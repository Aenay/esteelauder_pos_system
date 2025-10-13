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

        // Pull loyalty point record directly from the database
        $loyalty = \App\Models\LoyaltyPoint::where('Customer_ID', $customer->Customer_ID)->first();

        // If customer has no loyalty record yet, create one with default values
        if (!$loyalty) {
            $loyalty = \App\Models\LoyaltyPoint::create([
                'Customer_ID'      => $customer->Customer_ID,
                'points_earned'    => 0,
                'points_used'      => 0,
                'current_balance'  => 0,
                'tier_level'       => 'bronze',
                'last_activity_date' => now(),
            ]);
        }

        // Ensure the $customer instance has the loyaltyPoints relation populated
        // so the view can use $customer->loyaltyPoints and the model accessors.
        // Assign directly (works at runtime) and call setRelation if available for completeness.
        $customer->loyaltyPoints = $loyalty;
        if (method_exists($customer, 'setRelation')) {
            $customer->setRelation('loyaltyPoints', $loyalty);
        }

        // Fetch the customer’s 5 latest orders
        $recentOrders = \App\Models\Order::where('Customer_ID', $customer->Customer_ID)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Send both loyalty and recent order data to the view
        return view('customer.loyalty.index', compact('customer', 'loyalty', 'recentOrders'));
    }
}
