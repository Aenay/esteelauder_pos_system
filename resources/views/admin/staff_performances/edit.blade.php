@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Staff Performance</h1>
                <p class="text-gray-600">Update performance record for {{ $staffPerformance->staff ? ($staffPerformance->staff->Staff_Name ?? 'Unknown Staff') : 'Unknown Staff' }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.staff-performances.show', $staffPerformance) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Details
                </a>
                <a href="{{ route('admin.staff-performances.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Current Performance Info -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Performance Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Staff Member</h4>
                            <p class="font-medium text-gray-900">{{ $staffPerformance->staff ? ($staffPerformance->staff->Staff_Name ?? 'Unknown Staff') : 'Unknown Staff' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Department</h4>
                            <p class="font-medium text-gray-900">{{ $staffPerformance->staff && $staffPerformance->staff->department ? ($staffPerformance->staff->department->Department_Name ?? 'N/A') : 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Performance Date</h4>
                            <p class="font-medium text-gray-900">{{ $staffPerformance->performance_date ? $staffPerformance->performance_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Performance Metrics</h3>
                </div>
                <form action="{{ route('admin.staff-performances.update', $staffPerformance) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Daily Sales Target -->
                        <div>
                            <label for="daily_sales_target" class="block text-sm font-medium text-gray-700 mb-2">
                                Daily Sales Target ($)
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   name="daily_sales_target" 
                                   id="daily_sales_target" 
                                   value="{{ old('daily_sales_target', $staffPerformance->daily_sales_target) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('daily_sales_target')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actual Sales -->
                        <div>
                            <label for="actual_sales" class="block text-sm font-medium text-gray-700 mb-2">
                                Actual Sales ($)
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   name="actual_sales" 
                                   id="actual_sales" 
                                   value="{{ old('actual_sales', $staffPerformance->actual_sales) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('actual_sales')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Orders Processed -->
                        <div>
                            <label for="orders_processed" class="block text-sm font-medium text-gray-700 mb-2">
                                Orders Processed
                            </label>
                            <input type="number" 
                                   min="0" 
                                   name="orders_processed" 
                                   id="orders_processed" 
                                   value="{{ old('orders_processed', $staffPerformance->orders_processed) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('orders_processed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customers Served -->
                        <div>
                            <label for="customers_served" class="block text-sm font-medium text-gray-700 mb-2">
                                Customers Served
                            </label>
                            <input type="number" 
                                   min="0" 
                                   name="customers_served" 
                                   id="customers_served" 
                                   value="{{ old('customers_served', $staffPerformance->customers_served) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                            @error('customers_served')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Customer Satisfaction -->
                        <div>
                            <label for="customer_satisfaction" class="block text-sm font-medium text-gray-700 mb-2">
                                Customer Satisfaction (1-5)
                            </label>
                            <select name="customer_satisfaction" 
                                    id="customer_satisfaction" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('customer_satisfaction', $staffPerformance->customer_satisfaction) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}
                                    </option>
                                @endfor
                            </select>
                            @error('customer_satisfaction')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Performance Rating -->
                        <div>
                            <label for="performance_rating" class="block text-sm font-medium text-gray-700 mb-2">
                                Performance Rating (1-5)
                            </label>
                            <select name="performance_rating" 
                                    id="performance_rating" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('performance_rating', $staffPerformance->performance_rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}
                                    </option>
                                @endfor
                            </select>
                            @error('performance_rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Add any additional notes about this performance record...">{{ old('notes', $staffPerformance->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.staff-performances.show', $staffPerformance) }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                            Update Performance
                        </button>
                    </div>
                </form>
            </div>

            <!-- Performance Preview -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance Preview</h3>
                    <p class="text-sm text-gray-600">This shows how the updated performance will look</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Target Achievement</h4>
                            <p class="text-2xl font-bold text-purple-600" id="preview-achievement">
                                {{ $staffPerformance->daily_sales_target > 0 ? round(($staffPerformance->actual_sales / $staffPerformance->daily_sales_target) * 100, 1) : 0 }}%
                            </p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Sales Target</h4>
                            <p class="text-2xl font-bold text-blue-600" id="preview-target">
                                ${{ number_format($staffPerformance->daily_sales_target, 2) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Actual Sales</h4>
                            <p class="text-2xl font-bold text-green-600" id="preview-actual">
                                ${{ number_format($staffPerformance->actual_sales, 2) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Orders</h4>
                            <p class="text-2xl font-bold text-orange-600" id="preview-orders">
                                {{ $staffPerformance->orders_processed }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time preview updates
    const targetInput = document.getElementById('daily_sales_target');
    const actualInput = document.getElementById('actual_sales');
    const ordersInput = document.getElementById('orders_processed');
    
    const previewAchievement = document.getElementById('preview-achievement');
    const previewTarget = document.getElementById('preview-target');
    const previewActual = document.getElementById('preview-actual');
    const previewOrders = document.getElementById('preview-orders');
    
    function updatePreview() {
        const target = parseFloat(targetInput.value) || 0;
        const actual = parseFloat(actualInput.value) || 0;
        const orders = parseInt(ordersInput.value) || 0;
        
        // Update preview values
        previewTarget.textContent = `$${target.toFixed(2)}`;
        previewActual.textContent = `$${actual.toFixed(2)}`;
        previewOrders.textContent = orders;
        
        // Calculate and update achievement percentage
        if (target > 0) {
            const achievement = (actual / target) * 100;
            previewAchievement.textContent = `${achievement.toFixed(1)}%`;
        } else {
            previewAchievement.textContent = '0%';
        }
    }
    
    // Add event listeners for real-time updates
    targetInput.addEventListener('input', updatePreview);
    actualInput.addEventListener('input', updatePreview);
    ordersInput.addEventListener('input', updatePreview);
});
</script>
@endsection
