@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Staff Performance Analytics</h1>
                <p class="text-gray-600">Comprehensive performance analysis and insights</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.staff-performances.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Performance List
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <!-- Analytics Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Total Staff</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalStaff ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Active staff members</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Avg Performance</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($avgPerformance ?? 0, 1) }}/5</p>
                <p class="text-xs text-gray-500 mt-1">Overall rating</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Top Department</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $topDepartment ?? 'N/A' }}</p>
                <p class="text-xs text-gray-500 mt-1">Best performing</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Success Rate</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($successRate ?? 0, 1) }}%</p>
                <p class="text-xs text-gray-500 mt-1">Target achievement</p>
            </div>
        </div>

        <!-- Performance Trends Chart -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Trends (Last 30 Days)</h3>
            </div>
            <div class="p-6">
                <canvas id="performanceTrendsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Removed extra charts per request -->

        <!-- Removed top performers table per request -->

        <!-- Performance Insights -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Performance Insights</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">Best Performing Staff</span>
                            </div>
                            <span class="text-sm font-bold text-blue-600">{{ $bestPerformer ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">Highest Satisfaction</span>
                            </div>
                            <span class="text-sm font-bold text-green-600">{{ $highestSatisfaction ?? 'N/A' }}/5</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-yellow-800">Most Orders</span>
                            </div>
                            <span class="text-sm font-bold text-yellow-600">{{ $mostOrders ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recommendations</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(($avgPerformance ?? 0) < 4)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Consider additional training programs to improve overall performance ratings.</span>
                        </div>
                        @endif
                        
                        @if(($successRate ?? 0) < 80)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Review sales targets to ensure they are realistic and achievable.</span>
                        </div>
                        @endif
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Implement regular performance reviews and feedback sessions.</span>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-700">Recognize and reward top performers to maintain motivation.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart colors
    const colors = {
        primary: '#3B82F6',
        secondary: '#10B981',
        accent: '#8B5CF6',
        warning: '#F59E0B',
        danger: '#EF4444',
        success: '#10B981',
        info: '#06B6D4'
    };

    // Performance Trends Chart - real order data
    (async () => {
        const resp = await fetch(`{{ route('admin.staff-performances.order-trends') }}?days=30`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await resp.json();

        const performanceTrendsCtx = document.getElementById('performanceTrendsChart').getContext('2d');
        new Chart(performanceTrendsCtx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Sales ($)',
                    data: data.datasets.sales,
                    borderColor: colors.primary,
                    backgroundColor: colors.primary + '20',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 0
                }, {
                    label: 'Orders Processed',
                    data: data.datasets.orders,
                    borderColor: colors.secondary,
                    backgroundColor: colors.secondary + '20',
                    tension: 0.35,
                    fill: true,
                    yAxisID: 'y1',
                    borderWidth: 2,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: { callback: (val) => '$' + val }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }
                    }
                },
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });
    })();

    // Removed extra charts JS per request
});
</script>
@endsection
