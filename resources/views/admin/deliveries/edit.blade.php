@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Delivery</h2>
                <p class="text-gray-600 mt-2">Reference: {{ $delivery->Delivery_Reference }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Delivery
                </a>
                <a href="{{ route('admin.deliveries.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Deliveries
                </a>
            </div>
        </div>

        <!-- Status Warning -->
        @if($delivery->Status === 'delivered')
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Delivery Already Completed</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This delivery has been marked as delivered and cannot be edited. You can only view the details.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form action="{{ route('admin.deliveries.update', $delivery) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="Supplier_ID" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                        <select name="Supplier_ID" id="Supplier_ID" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->Supplier_ID }}" 
                                        {{ old('Supplier_ID', $delivery->Supplier_ID) == $supplier->Supplier_ID ? 'selected' : '' }}>
                                    {{ $supplier->Supplier_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('Supplier_ID')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Expected_Delivery_Date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                        <input type="date" name="Expected_Delivery_Date" id="Expected_Delivery_Date" required
                               value="{{ old('Expected_Delivery_Date', $delivery->Expected_Delivery_Date) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Expected_Delivery_Date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="Status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="Status" id="Status" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ old('Status', $delivery->Status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_transit" {{ old('Status', $delivery->Status) === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="delivered" {{ old('Status', $delivery->Status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ old('Status', $delivery->Status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('Status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Actual_Delivery_Date" class="block text-sm font-medium text-gray-700 mb-2">Actual Delivery Date</label>
                        <input type="date" name="Actual_Delivery_Date" id="Actual_Delivery_Date"
                               value="{{ old('Actual_Delivery_Date', $delivery->Actual_Delivery_Date) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Actual_Delivery_Date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="Tracking_Number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                        <input type="text" name="Tracking_Number" id="Tracking_Number" 
                               value="{{ old('Tracking_Number', $delivery->Tracking_Number) }}"
                               placeholder="Enter tracking number"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Tracking_Number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Carrier" class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                        <input type="text" name="Carrier" id="Carrier" 
                               value="{{ old('Carrier', $delivery->Carrier) }}"
                               placeholder="e.g., FedEx, UPS, DHL"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Carrier')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="Notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="Notes" id="Notes" rows="3" 
                              placeholder="Additional notes about the delivery"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('Notes', $delivery->Notes) }}</textarea>
                    @error('Notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Products Information (Read-only) -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Products (Read-only)</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity Ordered</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity Received</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($delivery->deliveryDetails as $detail)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $detail->product->Product_Name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $detail->product->SKU }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $detail->Quantity_Ordered }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $detail->Quantity_Received }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">${{ number_format($detail->Unit_Cost, 2) }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">${{ number_format($detail->Total_Cost, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            <p><strong>Note:</strong> Product quantities and costs cannot be modified after creation. To update quantities received, use the "Update Quantities" feature on the delivery details page.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.deliveries.show', $delivery) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                        Update Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-fill actual delivery date when status changes to delivered
document.getElementById('Status').addEventListener('change', function() {
    const actualDateInput = document.getElementById('Actual_Delivery_Date');
    if (this.value === 'delivered' && !actualDateInput.value) {
        actualDateInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection

