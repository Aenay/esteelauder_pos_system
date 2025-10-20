@extends('layouts.customer')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->Order_ID }}</h1>
                    <p class="mt-2 text-gray-600">Ordered on {{ $order->Order_Date->format('F j, Y') }}</p>
                </div>
                <a href="{{ route('customer.orders.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Details -->
            <div class="lg:col-span-2">
                <!-- Order Items -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Order Items</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($order->orderDetails as $detail)
                            <div class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($detail->product->image)
                                            <img class="h-16 w-16 rounded-lg object-cover" 
                                                 src="{{ asset('storage/' . $detail->product->image) }}" 
                                                 alt="{{ $detail->product->Product_Name }}">
                                        @else
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ $detail->product->Product_Name }}
                                                </h3>
                                                <p class="text-sm text-gray-500">SKU: {{ $detail->product->SKU }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-medium text-gray-900">
                                                    ${{ number_format($detail->product->Price * $detail->Quantity, 2) }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    ${{ number_format($detail->product->Price, 2) }} × {{ $detail->Quantity }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">${{ number_format($order->Subtotal, 2) }}</span>
                            </div>
                            @if($order->Discount_Amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Discount:</span>
                                    <span class="font-medium text-green-600">-${{ number_format($order->Discount_Amount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-semibold border-t pt-3">
                                <span>Total:</span>
                                <span class="text-pink-600">${{ number_format($order->Final_Amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information Sidebar -->
            <div class="space-y-6">
                <!-- Order Status -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Order Status</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($order->payment_status === 'completed')
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600"></i>
                                    </div>
                                @elseif($order->payment_status === 'pending')
                                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-600"></i>
                                    </div>
                                @else
                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-question text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($order->payment_status) }}
                                </p>
                                <p class="text-sm text-gray-500">Payment Status</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Payment Information</h2>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div>
                            <span class="text-sm text-gray-600">Payment Method:</span>
                            <p class="text-sm font-medium text-gray-900 capitalize">
                                @switch($order->payment_method)
                                    @case('card')
                                        <i class="fas fa-credit-card text-blue-600 mr-1"></i> Credit Card
                                        @break
                                    @case('paypal')
                                        <i class="fab fa-paypal text-blue-600 mr-1"></i> PayPal
                                        @break
                                    @case('apple')
                                        <i class="fab fa-apple-pay text-black mr-1"></i> Apple Pay
                                        @break
                                    @default
                                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                                @endswitch
                            </p>
                        </div>
                        @if($order->transaction_id)
                            <div>
                                <span class="text-sm text-gray-600">Transaction ID:</span>
                                <p class="text-sm font-medium text-gray-900 font-mono">{{ $order->transaction_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Status -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Delivery Status</h2>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @php
                            $latestDelivery = $order->deliveries->sortByDesc('Expected_Delivery_Date')->first();
                        @endphp
                        @if($latestDelivery)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    {!! $latestDelivery->status_badge !!}
                                    <span class="text-sm text-gray-700">Expected: {{ optional($latestDelivery->Expected_Delivery_Date)->format('M j, Y') }}</span>
                                </div>
                            </div>
                            @if($latestDelivery->Tracking_Number || $latestDelivery->Carrier)
                                <div class="text-sm text-gray-700">
                                    @if($latestDelivery->Tracking_Number)
                                        <div>Tracking #: <span class="font-mono">{{ $latestDelivery->Tracking_Number }}</span></div>
                                    @endif
                                    @if($latestDelivery->Carrier)
                                        <div>Carrier: {{ $latestDelivery->Carrier }}</div>
                                    @endif
                                </div>
                            @endif
                            @if($latestDelivery->Actual_Delivery_Date)
                                <div class="text-sm text-green-700">Delivered on {{ $latestDelivery->Actual_Delivery_Date->format('M j, Y') }}</div>
                            @elseif(optional($latestDelivery->Expected_Delivery_Date) && $latestDelivery->Expected_Delivery_Date->isPast())
                                <div class="text-sm text-red-600">This delivery is past the expected date.</div>
                            @endif
                        @else
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-truck text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">No delivery scheduled yet</p>
                                    <p class="text-sm text-gray-500">We’ll notify you when shipping is arranged.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Staff Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Staff Information</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center">
                                    <i class="fas fa-user text-pink-600"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ optional($order->staff)->Staff_Name ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-500">Sales Associate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
