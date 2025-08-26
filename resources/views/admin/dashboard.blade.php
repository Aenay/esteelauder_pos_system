@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-600">Overview of your business performance and key metrics</p>
        </div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <!-- Today's Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Today's Sales</h3>
                <p class="text-3xl font-bold text-blue-600">${{ number_format($todayStats['sales'], 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Total revenue today</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Today's Orders</h3>
                <p class="text-3xl font-bold text-green-600">{{ $todayStats['orders'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Orders processed</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">New Customers</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $todayStats['customers'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Registered today</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $todayStats['products'] }}</p>
                <p class="text-xs text-gray-500 mt-1">In inventory</p>
            </div>
        </div>

        <!-- Staff Performance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Total Staff</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ $staffStats['total_staff'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Active employees</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Active Today</h3>
                <p class="text-3xl font-bold text-green-600">{{ $staffStats['active_today'] }}</p>
                <p class="text-xs text-gray-500 mt-1">With performance records</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-gray-500 text-sm font-medium">Top Performer</h3>
                @if($staffStats['top_performer'])
                    <p class="text-xl font-bold text-blue-600">{{ $staffStats['top_performer']->Staff_Name }}</p>
                    <p class="text-sm text-gray-500">{{ $staffStats['top_performer']->department->Department_Name ?? 'N/A' }}</p>
                @else
                    <p class="text-xl font-bold text-gray-400">No data</p>
                @endif
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Weekly Sales Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Weekly Sales Trend</h3>
                <canvas id="weeklySalesChart" width="400" height="200"></canvas>
            </div>

            <!-- Staff Performance Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Staff Performance Overview</h3>
                <canvas id="staffPerformanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Top Performers and Recent Orders -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Performing Staff -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Top Performing Staff</h3>
                <div class="space-y-3">
                    @forelse($topPerformers as $index => $staff)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $staff->Staff_Name ?? 'Unknown Staff' }}</p>
                                <p class="text-sm text-gray-500">{{ $staff->department->Department_Name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">${{ number_format($staff->orders_sum_final_amount ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ $staff->orders_count ?? 0 }} orders</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No performance data available</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-lg mb-4">Recent Orders</h3>
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Order #{{ $order->Order_ID }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $order->staff ? $order->staff->Staff_Name : 'Unknown Staff' }} • 
                                {{ $order->customer ? $order->customer->Customer_Name : 'Unknown Customer' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">${{ number_format($order->Final_Amount, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ $order->Order_Date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No recent orders</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-bold text-lg mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800">Add Product</span>
                </a>
                <a href="{{ route('admin.customers.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-800">Add Customer</span>
                </a>
                <a href="{{ route('admin.staff-performances.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-800">Performance</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-orange-800">View Orders</span>
                </a>
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

    // Weekly Sales Chart
    const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
    const weeklySalesChart = new Chart(weeklySalesCtx, {
        type: 'line',
        data: {
            labels: @json($weeklySales->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })),
            datasets: [{
                label: 'Daily Sales ($)',
                data: @json($weeklySales->pluck('total_sales')),
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
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
                    text: 'Weekly Sales Performance'
                }
            }
        }
    });

    // Staff Performance Chart
    const staffPerformanceCtx = document.getElementById('staffPerformanceChart').getContext('2d');
    const staffPerformanceChart = new Chart(staffPerformanceCtx, {
        type: 'bar',
        data: {
            labels: @json($topPerformers->pluck('Staff_Name')),
            datasets: [{
                label: 'Total Sales ($)',
                data: @json($topPerformers->pluck('orders_sum_final_amount')),
                backgroundColor: colors.success,
                borderColor: colors.success,
                borderWidth: 1,
                borderRadius: 4
            }, {
                label: 'Orders Count',
                data: @json($topPerformers->pluck('orders_count')),
                backgroundColor: colors.info,
                borderColor: colors.info,
                borderWidth: 1,
                borderRadius: 4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                    text: 'Staff Performance Comparison'
                }
            }
        }
    });

    // Add chart resize handlers
    window.addEventListener('resize', function() {
        weeklySalesChart.resize();
        staffPerformanceChart.resize();
    });
});
</script>
@endsection
