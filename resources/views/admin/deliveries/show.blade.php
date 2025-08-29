@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Delivery Details</h2>
                <p class="text-gray-600 mt-2">Reference: {{ $delivery->Delivery_Reference }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.deliveries.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Deliveries
                </a>
                @if($delivery->Status !== 'delivered')
                    <a href="{{ route('admin.deliveries.edit', $delivery) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Delivery
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'in_transit' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'delivered' => 'bg-green-100 text-green-800 border-green-200',
                    'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                ];
                $statusColor = $statusColors[$delivery->Status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                {{ ucfirst(str_replace('_', ' ', $delivery->Status)) }}
            </span>
        </div>

        <!-- Main Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Supplier Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Supplier Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Name:</span>
                            <p class="text-gray-900">{{ $delivery->supplier->Supplier_Name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Phone:</span>
                            <p class="text-gray-900">{{ $delivery->supplier->Supplier_Phone }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Email:</span>
                            <p class="text-gray-900">{{ $delivery->supplier->Supplier_Email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Expected Date:</span>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($delivery->Expected_Delivery_Date)->format('M d, Y') }}</p>
                        </div>
                        @if($delivery->Actual_Delivery_Date)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Actual Date:</span>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($delivery->Actual_Delivery_Date)->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($delivery->Tracking_Number)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Tracking:</span>
                                <p class="text-gray-900">{{ $delivery->Tracking_Number }}</p>
                            </div>
                        @endif
                        @if($delivery->Carrier)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Carrier:</span>
                                <p class="text-gray-900">{{ $delivery->Carrier }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Summary</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($delivery->Total_Amount, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Products:</span>
                            <p class="text-gray-900">{{ $delivery->deliveryDetails->count() }}</p>
                        </div>
                        @if($delivery->Notes)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Notes:</span>
                                <p class="text-gray-900">{{ $delivery->Notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Products</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Ordered</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Received</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($delivery->deliveryDetails as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $detail->product->Product_Name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->product->SKU }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->Quantity_Ordered }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($delivery->Status !== 'delivered')
                                            <input type="number" 
                                                   class="quantity-input w-20 border-gray-300 rounded text-sm"
                                                   data-detail-id="{{ $detail->id }}"
                                                   value="{{ $detail->Quantity_Received }}"
                                                   min="0" 
                                                   max="{{ $detail->Quantity_Ordered }}">
                                        @else
                                            <span class="text-sm text-gray-900">{{ $detail->Quantity_Received }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($detail->Unit_Cost, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($detail->Total_Cost, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $received = $detail->Quantity_Received;
                                            $ordered = $detail->Quantity_Ordered;
                                            if ($received == 0) {
                                                $status = 'pending';
                                                $color = 'bg-yellow-100 text-yellow-800';
                                            } elseif ($received >= $ordered) {
                                                $status = 'complete';
                                                $color = 'bg-green-100 text-green-800';
                                            } else {
                                                $status = 'partial';
                                                $color = 'bg-blue-100 text-blue-800';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $detail->Notes ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($delivery->Status !== 'delivered')
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="update-quantities" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                            Update Quantities Received
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-between">
            <div>
                @if($delivery->Status !== 'delivered')
                    <form action="{{ route('admin.deliveries.destroy', $delivery) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Are you sure you want to delete this delivery?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                            Delete Delivery
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@if($delivery->Status !== 'delivered')
<script>
document.getElementById('update-quantities').addEventListener('click', function() {
    const quantities = {};
    document.querySelectorAll('.quantity-input').forEach(input => {
        quantities[input.dataset.detailId] = parseInt(input.value) || 0;
    });

    fetch('{{ route("admin.deliveries.update-quantities", $delivery) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantities: quantities })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Quantities updated successfully!');
            if (data.delivery_status === 'delivered') {
                location.reload();
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantities');
    });
});
</script>
@endif
@endsection

