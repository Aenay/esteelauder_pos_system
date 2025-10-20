<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerDashboardController extends Controller
{
    /**
     * Display the customer dashboard
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to view your dashboard.');
        }

        // Load customer with relationships
        $customer->load(['orders.orderDetails.product', 'loyaltyPoints']);
        
        // Get recent orders (last 5)
        $recentOrders = $customer->orders()
            ->with(['orderDetails.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get cart information
        $cart = Session::get('customer_cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }
        
        // Get order statistics
        $orderStats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->sum('Final_Amount'),
            'loyalty_points' => $customer->loyalty_points,
            'loyalty_tier' => $customer->loyalty_tier,
        ];
        
        return view('customer.dashboard.index', compact(
            'customer', 
            'recentOrders', 
            'cartCount', 
            'cartTotal', 
            'orderStats'
        ));
    }
}

