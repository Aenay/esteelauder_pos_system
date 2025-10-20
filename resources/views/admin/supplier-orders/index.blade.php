@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Supplier Orders</h1>
                <a href="{{ route('admin.supplier-orders.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Supplier Order
                </a>
            </div>
        </div>
    </header>
    
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">All Supplier Orders</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200 text-gray-600">
                        <tr>
                            <th class="py-3 px-6 text-left">Order ID</th>
                            <th class="py-3 px-6 text-left">Supplier</th>
                            <th class="py-3 px-6 text-left">Date</th>
                            <th class="py-3 px-6 text-center">Total Amount</th>
                            <th class="py-3 px-6 text-center">Payment Status</th>
                            <th class="py-3 px-6 text-center">Delivery Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($supplierOrders as $order)
                            <tr class="border-b">
                                <td class="py-3 px-6">#{{ $order->Order_ID }}</td>
                                <td class="py-3 px-6 font-medium">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-900">{{ $order->customer->Customer_Name }}</span>
                                        <span class="text-xs text-green-600">
                                            <i class="fas fa-truck mr-1"></i>Supplier Order
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3 px-6">{{ $order->Order_Date->format('M j, Y') }}</td>
                                <td class="py-3 px-6 text-center">${{ number_format($order->Final_Amount, 2) }}</td>
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
                                    @php
                                        $delivery = \App\Models\Delivery::where('Supplier_ID', $order->customer->Customer_ID)
                                            ->where('Expected_Delivery_Date', $order->Order_Date)
                                            ->first();
                                    @endphp
                                    @if($delivery)
                                        @if($delivery->Status === 'delivered')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Delivered
                                            </span>
                                        @elseif($delivery->Status === 'in_transit')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                <i class="fas fa-truck mr-1"></i> In Transit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i> No Delivery
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.supplier-orders.show', $order) }}" 
                                           class="text-blue-500 hover:text-blue-700 font-medium">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.supplier-orders.edit', $order) }}" 
                                           class="text-green-600 hover:text-green-800 font-medium">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.supplier-orders.destroy', $order) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Delete this supplier order? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-truck text-4xl text-gray-400 mb-2"></i>
                                        <p>No supplier orders found.</p>
                                        <a href="{{ route('admin.supplier-orders.create') }}" 
                                           class="mt-2 text-green-600 hover:text-green-700">
                                            Create your first supplier order
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection

