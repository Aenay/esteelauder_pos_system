@extends('layouts.customer')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Loyalty Program</h1>
            <p class="mt-2 text-gray-600">Track your loyalty points and tier status</p>
        </div>

    @if($loyalty)
            <!-- Loyalty Status Card -->
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg shadow-lg text-white mb-8">
                <div class="px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">{{ $customer->Customer_Name }}</h2>
                            <p class="text-pink-100 mt-1">Loyalty Member</p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold">{{ number_format($loyalty->current_balance) }}</div>
                            <div class="text-pink-100">Points</div>
                        </div>
                    </div>

                    <!-- Tier Badge -->
                    <div class="mt-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white">
                            <i class="fas fa-crown mr-2"></i>
                            {{ ucfirst($loyalty->tier_level) }} Tier
                        </span>
                    </div>
                </div>
            </div>

            <!-- Loyalty Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Current Tier -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Current Tier</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-pink-100 flex items-center justify-center">
                                    <i class="fas fa-star text-pink-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ ucfirst($loyalty->tier_level) }}
                                </p>
                                <p class="text-sm text-gray-500">Loyalty Tier</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Points Balance -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Points Balance</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-coins text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-xl font-semibold text-gray-900">
                                    {{ number_format($loyalty->current_balance) }}
                                </p>
                                <p class="text-sm text-gray-500">Available Points</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier Benefits -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Tier Benefits</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @if($loyalty->tier_level === 'bronze')
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">5% discount on all purchases</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Early access to new products</p>
                                </div>
                            </div>
                        @elseif($loyalty->tier_level === 'silver')
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">10% discount on all purchases</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Free shipping on orders over $50</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Exclusive member events</p>
                                </div>
                            </div>
                        @elseif($loyalty->tier_level === 'gold')
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">15% discount on all purchases</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Free shipping on all orders</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Personal beauty consultant</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-check text-yellow-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">VIP access to limited editions</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Earn points on every purchase</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-check text-gray-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-900">Access to member-only promotions</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Orders with Points -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Order #{{ $order->Order_ID }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $order->Order_Date->format('M j, Y') }} - ${{ number_format($order->Final_Amount, 2) }}
                                    </p>

                                    <div class="mt-1 flex items-center">
                                        @php
                                            $customerDelivery = $order->deliveries->where('delivery_type', 'customer')->sortByDesc('created_at')->first();
                                        @endphp

                                        @if($customerDelivery)
                                            {!! $customerDelivery->status_badge !!}
                                            @if($customerDelivery->Status !== 'delivered')
                                                <span class="ml-2 text-xs text-gray-500">
                                                    Expected {{ optional($customerDelivery->Expected_Delivery_Date)->format('M j, Y') }}
                                                    @if($customerDelivery->is_overdue)
                                                        <span class="ml-1 text-red-600 font-medium">Overdue</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="ml-2 text-xs text-gray-500">
                                                    Delivered {{ optional($customerDelivery->Actual_Delivery_Date)->format('M j, Y') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                No delivery
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-pink-600">
                                        +{{ number_format($order->Final_Amount) }} pts
                                    </p>
                                    <p class="text-xs text-gray-500">Earned</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- No Loyalty Points -->
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-star text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No loyalty points yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start shopping to earn loyalty points!</p>
                <div class="mt-6">
                    <a href="{{ route('customer.orders.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        View My Orders
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
