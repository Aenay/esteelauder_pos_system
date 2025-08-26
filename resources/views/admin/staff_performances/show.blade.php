@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Staff Performance Details</h1>
                <p class="text-gray-600">View detailed performance information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.staff-performances.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                <a href="{{ route('admin.staff-performances.edit', $staffPerformance) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <!-- Performance Overview Card -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Overview</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Staff Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Staff Information</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-lg font-bold text-blue-600">
                                        {{ $staffPerformance->staff ? substr($staffPerformance->staff->Staff_Name ?? 'UN', 0, 2) : 'UN' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $staffPerformance->staff ? ($staffPerformance->staff->Staff_Name ?? 'Unknown Staff') : 'Unknown Staff' }}</p>
                                    <p class="text-sm text-gray-500">{{ $staffPerformance->staff && $staffPerformance->staff->department ? ($staffPerformance->staff->department->Department_Name ?? 'N/A') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Date -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Performance Date</h4>
                        <p class="text-lg font-medium text-gray-900">{{ $staffPerformance->performance_date ? $staffPerformance->performance_date->format('F d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Sales Target -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Daily Sales Target</h3>
                <p class="text-3xl font-bold text-blue-600">${{ number_format($staffPerformance->daily_sales_target ?? 0, 2) }}</p>
            </div>

            <!-- Actual Sales -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Actual Sales</h3>
                <p class="text-3xl font-bold text-green-600">${{ number_format($staffPerformance->actual_sales ?? 0, 2) }}</p>
            </div>

            <!-- Target Achievement -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Target Achievement</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $staffPerformance->target_achievement ?? 0 }}%</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min($staffPerformance->target_achievement ?? 0, 100) }}%"></div>
                </div>
            </div>

            <!-- Orders Processed -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Orders Processed</h3>
                <p class="text-3xl font-bold text-orange-600">{{ $staffPerformance->orders_processed ?? 0 }}</p>
            </div>
        </div>

        <!-- Detailed Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Satisfaction -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Customer Satisfaction</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-yellow-600">{{ $staffPerformance->customer_satisfaction ?? 0 }}/5</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= ($staffPerformance->customer_satisfaction ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Customer satisfaction rating based on service quality and order accuracy.</p>
                </div>
            </div>

            <!-- Performance Rating -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance Rating</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex px-4 py-2 text-2xl font-semibold rounded-full 
                            {{ ($staffPerformance->performance_rating ?? 0) >= 4 ? 'bg-green-100 text-green-800' : 
                               (($staffPerformance->performance_rating ?? 0) >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $staffPerformance->performance_rating ?? 0 }}/5
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">Overall performance rating based on sales, customer satisfaction, and efficiency.</p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Customers Served</h4>
                        <p class="text-lg font-medium text-gray-900">{{ $staffPerformance->customers_served ?? 0 }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Notes</h4>
                        <p class="text-lg font-medium text-gray-900">{{ $staffPerformance->notes ?? 'No notes available' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.staff-performances.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Back to List
            </a>
            <a href="{{ route('admin.staff-performances.edit', $staffPerformance) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg">
                Edit Performance
            </a>
        </div>
    </div>
</main>
@endsection
