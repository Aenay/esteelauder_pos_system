@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Order Header -->
        <div class="border-b pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->Order_ID }}</h1>
                    <p class="text-gray-600 mt-2">Placed on {{ $order->Order_Date->format('F j, Y \a\t g:i A') }}</p>
                    <p class="text-gray-600 mt-1">Staff: <span class="font-medium text-gray-900">{{ optional($order->staff)->Staff_Name ?? 'N/A' }}</span></p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-pink-600">${{ number_format($order->Final_Amount, 2) }}</div>
                    <div class="text-sm text-gray-500">Total Amount</div>
                </div>
            </div>
        </div>

        <!-- Order Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Customer Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Customer Information</h2>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Name:</span>
                        <span class="ml-2 text-gray-900">{{ $order->customer->Customer_Name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <span class="ml-2 text-gray-900">{{ $order->customer->Customer_Email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Phone:</span>
                        <span class="ml-2 text-gray-900">{{ $order->customer->Customer_Phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Customer Type:</span>
                        <span class="ml-2 text-gray-900 capitalize">{{ $order->customer_type ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Payment Information</h2>
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Payment Method:</span>
                        <span class="ml-2 text-gray-900 capitalize">
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
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Payment Status:</span>
                        <span class="ml-2">
                            @if($order->payment_status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Completed
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($order->payment_status ?? 'N/A') }}
                                </span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Transaction ID:</span>
                        <span class="ml-2 text-gray-900 font-mono text-sm">{{ $order->transaction_id ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Order Items</h2>
            <div class="bg-white border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderDetails as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($detail->product->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->Product_Name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $detail->product->Product_Name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->product->SKU }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->Quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($detail->product->Price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($detail->product->Price * $detail->Quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Order Summary</h2>
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

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
            <div class="space-x-3">
                <a href="{{ route('admin.orders.receipt', $order) }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-receipt mr-2"></i> Print Receipt
                </a>
                
                <a href="#" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Email Receipt
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .container { max-width: none; }
    .bg-gray-50 { background-color: #f9fafb !important; }
    .shadow-lg { box-shadow: none !important; }
    .rounded-lg { border-radius: 0 !important; }
}
</style>
@endsection
