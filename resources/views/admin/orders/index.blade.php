@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">All Orders</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Order ID</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Staff</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-center">Total Amount</th>
                                <th class="py-3 px-6 text-center">Payment Method</th>
                                <th class="py-3 px-6 text-center">Payment Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($orders as $order)
                                <tr class="border-b">
                                    <td class="py-3 px-6">#{{ $order->Order_ID }}</td>
                                    <td class="py-3 px-6 font-medium">
                                        @if ($order->customer_type === 'internal' && $order->customer)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name }}</span>
                                                <span class="text-xs text-blue-600">
                                                    <i class="fas fa-crown mr-1"></i>Member
                                                </span>
                                            </div>
                                        @elseif ($order->customer_type === 'walk_in' && $order->customer)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name }}</span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>Walk-in Customer
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">External Customer</span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-shopping-cart mr-1"></i>Guest
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">{{ optional($order->staff)->Staff_Name ?? '—' }}</td>
                                    <td class="py-3 px-6">{{ $order->Order_Date->format('M j, Y') }}</td>
                                    <td class="py-3 px-6 text-center">${{ number_format($order->Final_Amount, 2) }}</td>
                                    <td class="py-3 px-6 text-center">
                                        @switch($order->payment_method)
                                            @case('card')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    <i class="fas fa-credit-card mr-1"></i> Card
                                                </span>
                                                @break
                                            @case('paypal')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                    <i class="fab fa-paypal mr-1"></i> PayPal
                                                </span>
                                                @break
                                            @case('apple')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-black text-white">
                                                    <i class="fab fa-apple-pay mr-1"></i> Apple Pay
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        @if($order->payment_status === 'completed')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Completed
                                            </span>
                                        @elseif($order->payment_status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                                {{ ucfirst($order->payment_status ?? 'N/A') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:text-blue-700 font-medium">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </a>
                                            @can('orders.edit')
                                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-green-600 hover:text-green-800 font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            @endcan
                                            @can('orders.delete')
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Delete this order? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    <i class="fas fa-trash mr-1"></i> Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
