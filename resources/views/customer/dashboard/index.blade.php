@extends('layouts.customer')

@section('title', 'Dashboard - Esteé Lauder')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-pink-600 to-pink-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Welcome back, {{ $customer->Customer_Name }}!</h1>
                    <p class="text-pink-100 mt-2">Manage your account, view orders, and continue shopping</p>
                </div>
                <!-- <div class="text-right">
                    <div class="text-2xl font-bold">{{ $customer->loyalty_tier }} Member</div>
                    <div class="text-pink-100">{{ $orderStats['loyalty_points'] }} loyalty points</div>
                </div> -->
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('customer.shop.index') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-pink-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Continue Shopping</h3>
                        <p class="text-gray-600">Browse our premium beauty products</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('customer.cart.show') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Shopping Cart</h3>
                        <p class="text-gray-600">{{ $cartCount }} items - ${{ number_format($cartTotal, 2) }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('customer.orders.index') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Order History</h3>
                        <p class="text-gray-600">{{ $orderStats['total_orders'] }} orders placed</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Account Statistics -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Overview</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Orders</span>
                            <span class="font-semibold text-gray-900">{{ $orderStats['total_orders'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Spent</span>
                            <span class="font-semibold text-gray-900">${{ number_format($orderStats['total_spent'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Loyalty Points</span>
                            <span class="font-semibold text-pink-600">{{ $orderStats['loyalty_points'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Member Tier</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                {{ ucfirst($orderStats['loyalty_tier']) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Profile Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('customer.profile.edit') }}" 
                           class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <i class="fas fa-user-edit mr-3 text-gray-400"></i>
                            <span>Edit Profile</span>
                        </a>
                        <!-- Loyalty Program Link -->
                        <!--
                        <a href="{{ route('customer.loyalty') }}" 
                           class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <i class="fas fa-star mr-3 text-gray-400"></i>
                            <span>Loyalty Program</span>
                        </a>
                        -->
                        <a href="#" 
                           class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <i class="fas fa-headset mr-3 text-gray-400"></i>
                            <span>Customer Support</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Recent Orders</h2>
                        <a href="{{ route('customer.orders.index') }}" 
                           class="text-pink-600 hover:text-pink-700 font-medium">
                            View All Orders
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="font-semibold text-gray-900">Order #{{ $order->Order_ID }}</h3>
                                                <span class="text-sm text-gray-500">{{ $order->Order_Date->format('M j, Y') }}</span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                                <span>{{ $order->orderDetails->count() }} items</span>
                                                <span>•</span>
                                                <span>${{ number_format($order->Final_Amount, 2) }}</span>
                                                <span>•</span>
                                                <span class="capitalize">{{ $order->payment_status }}</span>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600">
                                                @foreach($order->orderDetails->take(2) as $detail)
                                                    <span>{{ $detail->product->Product_Name }}</span>
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                                @if($order->orderDetails->count() > 2)
                                                    <span>and {{ $order->orderDetails->count() - 2 }} more...</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="text-pink-600 hover:text-pink-700 font-medium">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                            <p class="text-gray-600 mb-4">Start shopping to see your orders here</p>
                            <a href="{{ route('customer.shop.index') }}" 
                               class="inline-flex items-center bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition-colors">
                                <i class="fas fa-store mr-2"></i>
                                Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

