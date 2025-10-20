@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Edit Supplier Order #{{ $supplierOrder->Order_ID }}</h1>
                <a href="{{ route('admin.supplier-orders.show', $supplierOrder) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Order
                </a>
            </div>
        </div>
    </header>
    
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('admin.supplier-orders.update', $supplierOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Order Information (Read-only) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                                <p class="text-lg font-semibold text-green-600">${{ number_format($supplierOrder->Final_Amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-6">
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Status <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_status" id="payment_status" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('payment_status') border-red-500 @enderror" 
                                required>
                            <option value="pending" {{ old('payment_status', $supplierOrder->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('payment_status', $supplierOrder->payment_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="refunded" {{ old('payment_status', $supplierOrder->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="failed" {{ old('payment_status', $supplierOrder->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('notes') border-red-500 @enderror"
                                  placeholder="Add any notes or comments...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Items (Read-only) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
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

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.supplier-orders.show', $supplierOrder) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

