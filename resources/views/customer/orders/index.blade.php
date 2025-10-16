@extends('layouts.customer')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
            <p class="mt-2 text-gray-600">View your purchase history and order details</p>
        </div>

        <!-- Customer Info Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-user mr-2 text-pink-600"></i>
                            {{ $customer->Customer_Name }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $customer->Customer_Email }}</p>
                        <p class="text-sm text-gray-500">Member since {{ $customer->Registration_Date->format('M j, Y') }}</p>
                        @if($customer->loyaltyPoints)
                            <div class="mt-2 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i>
                                    {{ ucfirst($customer->loyaltyPoints->tier_level) }} Tier
                                </span>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ number_format($customer->loyaltyPoints->current_balance) }} points
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-pink-600">{{ $orders->count() }}</div>
                        <div class="text-sm text-gray-500">Total Orders</div>
                        @if($customer->loyaltyPoints)
                            <div class="mt-2">
                                <div class="text-lg font-semibold text-yellow-600">{{ number_format($customer->loyaltyPoints->current_balance) }}</div>
                                <div class="text-sm text-gray-500">Loyalty Points</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <li>
                            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                                <i class="fas fa-shopping-bag text-pink-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium text-gray-900">
                                                    Order #{{ $order->Order_ID }}
                                                </p>
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($order->payment_status === 'completed') bg-green-100 text-green-800
                                                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                            {{ $order->Order_Date->format('M j, Y') }}
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500">
                                                <i class="fas fa-user mr-1"></i>
                                                Staff: {{ optional($order->staff)->Staff_Name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-lg font-semibold text-gray-900">
                                                ${{ number_format($order->Final_Amount, 2) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $order->orderDetails->count() }} item(s)
                                            </div>
                                            @if($customer->loyaltyPoints && $order->isEligibleForLoyalty())
                                                <div class="text-sm text-yellow-600 font-medium">
                                                    <i class="fas fa-star mr-1"></i>
                                                    +{{ number_format($order->Final_Amount) }} pts
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('customer.orders.show', $order) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-pink-700 bg-pink-100 hover:bg-pink-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                                                <i class="fas fa-eye mr-1"></i>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Items Preview -->
                                <div class="mt-3">
                                    <div class="text-sm text-gray-600">
                                        <strong>Items:</strong>
                                        @foreach($order->orderDetails->take(3) as $detail)
                                            {{ $detail->product->Product_Name }}{{ $detail->Quantity > 1 ? ' (x' . $detail->Quantity . ')' : '' }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                        @if($order->orderDetails->count() > 3)
                                            and {{ $order->orderDetails->count() - 3 }} more...
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-shopping-bag text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No orders yet</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't made any purchases yet.</p>
                <div class="mt-6">
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
