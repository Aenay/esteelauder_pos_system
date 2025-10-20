@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Supplier Order #{{ $supplierOrder->Order_ID }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.supplier-orders.edit', $supplierOrder) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Order
                    </a>
                    <a href="{{ route('admin.supplier-orders.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order ID</label>
                                <p class="text-lg font-semibold text-gray-900">#{{ $supplierOrder->Order_ID }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order Date</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $supplierOrder->Order_Date->format('M j, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $supplierOrder->customer->Customer_Name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($supplierOrder->payment_status === 'completed') bg-green-100 text-green-800
                                    @elseif($supplierOrder->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($supplierOrder->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Product</th>
                                        <th class="py-3 px-6 text-center text-sm font-medium text-gray-500">Quantity</th>
                                        <th class="py-3 px-6 text-right text-sm font-medium text-gray-500">Unit Cost</th>
                                        <th class="py-3 px-6 text-right text-sm font-medium text-gray-500">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($supplierOrder->orderDetails as $detail)
                                        <tr>
                                            <td class="py-3 px-6">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $detail->product->Product_Name }}</p>
                                                    <p class="text-sm text-gray-500">SKU: {{ $detail->product->SKU }}</p>
                                                </div>
                                            </td>
                                            <td class="py-3 px-6 text-center">{{ $detail->Quantity }}</td>
                                            <td class="py-3 px-6 text-right">${{ number_format($detail->product->Price, 2) }}</td>
                                            <td class="py-3 px-6 text-right">${{ number_format($detail->Quantity * $detail->product->Price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary & Delivery Info -->
                <div class="lg:col-span-1">
                    <!-- Order Summary -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">${{ number_format($supplierOrder->Subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <span class="font-medium">${{ number_format($supplierOrder->Discount_Amount, 2) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold">Total:</span>
                                    <span class="text-lg font-bold text-green-600">${{ number_format($supplierOrder->Final_Amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    @if($delivery)
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Delivery Reference</label>
                                    <p class="font-semibold text-gray-900">{{ $delivery->Delivery_Reference }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expected Delivery Date</label>
                                    <p class="font-semibold text-gray-900">{{ $delivery->Expected_Delivery_Date->format('M j, Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($delivery->Status === 'delivered') bg-green-100 text-green-800
                                        @elseif($delivery->Status === 'in_transit') bg-blue-100 text-blue-800
                                        @elseif($delivery->Status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $delivery->Status)) }}
                                    </span>
                                </div>
                                @if($delivery->Actual_Delivery_Date)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Actual Delivery Date</label>
                                        <p class="font-semibold text-gray-900">{{ $delivery->Actual_Delivery_Date->format('M j, Y') }}</p>
                                    </div>
                                @endif
                                @if($delivery->Tracking_Number)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tracking Number</label>
                                        <p class="font-semibold text-gray-900">{{ $delivery->Tracking_Number }}</p>
                                    </div>
                                @endif
                                @if($delivery->Carrier)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Carrier</label>
                                        <p class="font-semibold text-gray-900">{{ $delivery->Carrier }}</p>
                                    </div>
                                @endif
                                @if($delivery->Notes)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                                        <p class="text-gray-900">{{ $delivery->Notes }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center block">
                                    <i class="fas fa-truck mr-2"></i>View Delivery Details
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-4">Delivery Information</h3>
                            <p class="text-gray-500">No delivery information available for this order.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

