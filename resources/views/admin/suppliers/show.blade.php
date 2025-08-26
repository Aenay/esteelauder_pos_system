@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">{{ $supplier->Supplier_Name }}</h2>
                <p class="mt-2 text-gray-600">Supplier ID: {{ $supplier->Supplier_ID }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Supplier
                </a>
                <a href="{{ route('admin.suppliers.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    Back to Suppliers
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Supplier Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Basic Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Supplier Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->Supplier_Name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Supplier ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->Supplier_ID }}</dd>
                        </div>
                        @if($supplier->Supplier_Phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->Supplier_Phone }}</dd>
                        </div>
                        @endif
                        @if($supplier->Supplier_Address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->Supplier_Address }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->created_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $supplier->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Total Deliveries</dt>
                            <dd class="mt-1 text-3xl font-bold text-indigo-600">{{ $supplier->total_deliveries }}</dd>
                        </div>
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Active Deliveries</dt>
                            <dd class="mt-1 text-3xl font-bold text-green-600">{{ $supplier->active_deliveries }}</dd>
                        </div>
                        <div class="text-center">
                            <dt class="text-sm font-medium text-gray-500">Completed Deliveries</dt>
                            <dd class="mt-1 text-3xl font-bold text-purple-600">{{ $supplier->total_deliveries - $supplier->active_deliveries }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.deliveries.create') }}?supplier={{ $supplier->Supplier_ID }}" 
                           class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium">
                            Create New Delivery
                        </a>
                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-md text-sm font-medium">
                            Edit Supplier
                        </a>
                        @if($supplier->total_deliveries == 0)
                        <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md text-sm font-medium"
                                    onclick="return confirm('Are you sure you want to delete this supplier?')">
                                Delete Supplier
                            </button>
                        </form>
                        @else
                        <span class="w-full bg-gray-300 text-gray-600 text-center py-2 px-4 rounded-md text-sm font-medium cursor-not-allowed">
                            Cannot Delete (Has Deliveries)
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery History -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Delivery History</h3>
            </div>
            
            @if($supplier->deliveries->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->deliveries as $delivery)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $delivery->Delivery_Reference }}</div>
                                @if($delivery->Tracking_Number)
                                    <div class="text-sm text-gray-500">Track: {{ $delivery->Tracking_Number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $delivery->Expected_Delivery_Date->format('M d, Y') }}</div>
                                @if($delivery->is_overdue)
                                    <div class="text-sm text-red-600 font-medium">Overdue</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $delivery->status_badge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($delivery->Total_Amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-6 text-center text-gray-500">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">No deliveries yet</p>
                <p class="text-gray-500">This supplier hasn't had any deliveries yet</p>
                <a href="{{ route('admin.deliveries.create') }}?supplier={{ $supplier->Supplier_ID }}" 
                   class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                    Create First Delivery
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
