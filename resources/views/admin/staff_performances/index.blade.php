@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Staff Performance Tracking</h1>
                <p class="text-gray-600">Monitor and manage staff performance metrics from real order data</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.staff-performances.analytics') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Analytics
                </a>
                <form action="{{ route('admin.staff-performances.regenerate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center" onclick="return confirm('This will regenerate performance data from actual orders. Continue?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Regenerate from Orders
                    </button>
                </form>
                <a href="{{ route('admin.staff-performances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Performance Record
                </a>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <!-- Performance Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Today's Total Sales</h3>
                <p class="text-3xl font-bold text-blue-600">${{ number_format($performanceStats['today_total_sales'] ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">From actual orders</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Today's Orders</h3>
                <p class="text-3xl font-bold text-green-600">{{ $performanceStats['today_total_orders'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Real order count</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Avg Satisfaction</h3>
                <p class="text-3xl font-bold text-purple-600">{{ number_format($performanceStats['monthly_avg_satisfaction'] ?? 0, 1) }}/5</p>
                <p class="text-xs text-gray-500 mt-1">Calculated from data</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Avg Rating</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($performanceStats['monthly_avg_rating'] ?? 0, 1) }}/5</p>
                <p class="text-xs text-gray-500 mt-1">Performance based</p>
            </div>
        </div>

        <!-- Data Source Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">Real-Time Data Integration</h4>
                    <p class="text-sm text-blue-700 mt-1">
                        Performance metrics are now automatically calculated from actual order data. 
                        Use "Regenerate from Orders" to update performance records based on the latest sales data.
                    </p>
                </div>
            </div>
        </div>

        <!-- Performance Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Performance Trends Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Performance Trends (Last 30 Days)</h3>
                <canvas id="performanceTrendsChart" width="400" height="200"></canvas>
            </div>

            <!-- Sales vs Targets Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Sales vs Targets Comparison</h3>
                <canvas id="salesVsTargetsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Additional Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Satisfaction Ratings Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Customer Satisfaction Distribution</h3>
                <canvas id="satisfactionChart" width="400" height="200"></canvas>
            </div>

            <!-- Department Performance Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Department Performance Overview</h3>
                <canvas id="departmentPerformanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Top Performers Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="font-bold text-xl mb-4">Top Performers This Month (Based on Real Sales)</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($topPerformers as $index => $staff)
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-2xl font-bold text-blue-600">{{ $index + 1 }}</span>
                    </div>
                    <h4 class="font-medium text-gray-900">{{ $staff->Staff_Name ?? 'Unknown Staff' }}</h4>
                    <p class="text-sm text-gray-500">{{ $staff->department->Department_Name ?? 'N/A' }}</p>
                    <p class="text-lg font-bold text-blue-600 mt-2">${{ number_format($staff->orders_sum_final_amount ?? 0, 2) }}</p>
                    <p class="text-sm text-gray-500">{{ $staff->orders_count ?? 0 }} orders</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Performance Records Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Performance Records (From Real Order Data)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Achievement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satisfaction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($staffPerformances as $performance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">
                                                {{ $performance->staff ? substr($performance->staff->Staff_Name ?? 'UN', 0, 2) : 'UN' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $performance->staff ? ($performance->staff->Staff_Name ?? 'Unknown Staff') : 'Unknown Staff' }}</div>
                                        <div class="text-sm text-gray-500">{{ $performance->staff && $performance->staff->department ? ($performance->staff->department->Department_Name ?? 'N/A') : 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $performance->performance_date ? $performance->performance_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($performance->daily_sales_target ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($performance->actual_sales ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $performance->target_achievement ?? 0 }}%</span>
                                    <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($performance->target_achievement ?? 0, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $performance->orders_processed ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $performance->customer_satisfaction ?? 0 }}/5</span>
                                    <div class="ml-2 flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= ($performance->customer_satisfaction ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ ($performance->performance_rating ?? 0) >= 4 ? 'bg-green-100 text-green-800' : 
                                       (($performance->performance_rating ?? 0) >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $performance->performance_rating ?? 0 }}/5
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.staff-performances.show', $performance) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('admin.staff-performances.edit', $performance) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('admin.staff-performances.destroy', $performance) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                No performance records found. <a href="{{ route('admin.staff-performances.create') }}" class="text-blue-600 hover:text-blue-800">Create the first one</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($staffPerformances->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $staffPerformances->links() }}
            </div>
            @endif
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

    // Performance Trends Chart
    const performanceTrendsCtx = document.getElementById('performanceTrendsChart').getContext('2d');
    const performanceTrendsChart = new Chart(performanceTrendsCtx, {
        type: 'line',
        data: {
            labels: @json($staffPerformances->take(10)->pluck('performance_date')->map(function($date) { return $date ? $date->format('M d') : 'N/A'; })->reverse()),
            datasets: [{
                label: 'Sales Amount ($)',
                data: @json($staffPerformances->take(10)->pluck('actual_sales')->reverse()),
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                tension: 0.4,
                fill: true
            }, {
                label: 'Orders Processed',
                data: @json($staffPerformances->take(10)->pluck('orders_processed')->reverse()),
                borderColor: colors.secondary,
                backgroundColor: colors.secondary + '20',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Sales Amount ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders Count'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Performance Trends Over Time'
                }
            }
        }
    });

    // Sales vs Targets Chart
    const salesVsTargetsCtx = document.getElementById('salesVsTargetsChart').getContext('2d');
    const salesVsTargetsChart = new Chart(salesVsTargetsCtx, {
        type: 'bar',
        data: {
            labels: @json($staffPerformances->take(8)->pluck('staff.Staff_Name')->map(function($name) { return $name ?? 'Unknown Staff'; })->reverse()),
            datasets: [{
                label: 'Actual Sales',
                data: @json($staffPerformances->take(8)->pluck('actual_sales')->reverse()),
                backgroundColor: colors.success,
                borderColor: colors.success,
                borderWidth: 1
            }, {
                label: 'Sales Target',
                data: @json($staffPerformances->take(8)->pluck('daily_sales_target')->reverse()),
                backgroundColor: colors.warning,
                borderColor: colors.warning,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales Amount ($)'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Sales Performance vs Targets'
                }
            }
        }
    });

    // Customer Satisfaction Distribution Chart
    const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
    const satisfactionChart = new Chart(satisfactionCtx, {
        type: 'doughnut',
        data: {
            labels: ['1-2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                data: [
                    @json($staffPerformances->where('customer_satisfaction', '<=', 2)->count()),
                    @json($staffPerformances->where('customer_satisfaction', '=', 3)->count()),
                    @json($staffPerformances->where('customer_satisfaction', '=', 4)->count()),
                    @json($staffPerformances->where('customer_satisfaction', '=', 5)->count())
                ],
                backgroundColor: [
                    colors.danger,
                    colors.warning,
                    colors.info,
                    colors.success
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Customer Satisfaction Ratings'
                }
            }
        }
    });

    // Department Performance Chart
    const departmentPerformanceCtx = document.getElementById('departmentPerformanceChart').getContext('2d');
    const departmentPerformanceChart = new Chart(departmentPerformanceCtx, {
        type: 'radar',
        data: {
            labels: @json($staffPerformances->groupBy('staff.department.Department_Name')->keys()),
            datasets: [{
                label: 'Average Sales',
                data: @json($staffPerformances->groupBy('staff.department.Department_Name')->map(function($group) { return $group->avg('actual_sales'); })->values()),
                borderColor: colors.primary,
                backgroundColor: colors.primary + '40',
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: colors.primary
            }, {
                label: 'Average Satisfaction',
                data: @json($staffPerformances->groupBy('staff.department.Department_Name')->map(function($group) { return $group->avg('customer_satisfaction') * 1000; })->values()),
                borderColor: colors.accent,
                backgroundColor: colors.accent + '40',
                pointBackgroundColor: colors.accent,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: colors.accent
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Performance Metrics'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Department Performance Comparison'
                }
            }
        }
    });

    // Add chart resize handlers
    window.addEventListener('resize', function() {
        performanceTrendsChart.resize();
        salesVsTargetsChart.resize();
        satisfactionChart.resize();
        departmentPerformanceChart.resize();
    });
});
</script>
@endsection
