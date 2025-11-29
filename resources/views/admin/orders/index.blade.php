@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <!-- Order Type Filter -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Order Management</h2>
                    <div class="flex items-center space-x-4">
                        <!-- Statistics -->
                        <div class="flex items-center space-x-6 text-sm">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                                <div class="text-gray-600">Total Orders</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['customer_orders'] }}</div>
                                <div class="text-gray-600">Customer Orders</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['supplier_orders'] }}</div>
                                <div class="text-gray-600">Supplier Orders</div>
                            </div>
                        </div>
                        @can('manage-purchase-orders')
                        <a href="{{ route('admin.supplier-orders.create') }}" 
                           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Create Supplier Order
                        </a>
                        @endcan
                    </div>
                </div>
                
                <!-- Filter Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <a href="{{ route('admin.orders.index', ['type' => 'all']) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $type === 'all' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-list mr-2"></i>All Orders
                        </a>
                        <a href="{{ route('admin.orders.index', ['type' => 'customer']) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $type === 'customer' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-users mr-2"></i>Customer Orders
                        </a>
                        <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" 
                           class="py-2 px-1 border-b-2 font-medium text-sm {{ $type === 'supplier' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <i class="fas fa-truck mr-2"></i>Supplier Orders
                        </a>
                    </nav>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">
                    @if($type === 'customer')
                        Customer Orders
                    @elseif($type === 'supplier')
                        Supplier Orders
                    @else
                        All Orders
                    @endif
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Order ID</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-left">Customer/Supplier</th>
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
                                    <td class="py-3 px-6">
                                        @if ($order->customer_type === 'supplier')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                <i class="fas fa-truck mr-1"></i>Supplier
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                <i class="fas fa-user mr-1"></i>Customer
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 font-medium">
                                        @if ($order->customer_type === 'supplier')
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name ?? 'Supplier Order' }}</span>
                                                <span class="text-xs text-green-600">
                                                    <i class="fas fa-truck mr-1"></i>Supplier Order
                                                </span>
                                            </div>
                                        @elseif ($order->customer_type === 'internal' && $order->customer)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name }}</span>
                                                <span class="text-xs text-blue-600">
                                                    <i class="fas fa-crown mr-1"></i>Member
                                                </span>
                                            </div>
                                        @elseif ($order->customer_type === 'external' && $order->customer)
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name }}</span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-shopping-cart mr-1"></i>Online Customer
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900">Walk-in Customer</span>
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-user mr-1"></i>Guest
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
                                            {{-- @can('orders.edit')
                                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-green-600 hover:text-green-800 font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            @endcan --}}
                                            @can('orders.delete')
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Delete this order? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    <i class="fas fa-trash mr-1"></i> Cancel
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
