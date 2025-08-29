@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Branch Analytics</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Comprehensive overview of branch performance and metrics</p>
            </div>
            <a href="{{ route('admin.branches.index') }}" 
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Branches
            </a>
        </div>

        <!-- Key Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-building text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Branches</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_branches'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Branches</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_branches'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-users text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Staff</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_staff'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-pink-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-pink-100 dark:bg-pink-900">
                        <i class="fas fa-shopping-cart text-pink-600 dark:text-pink-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    <i class="fas fa-chart-line mr-2 text-indigo-500"></i>Performance Metrics
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Average Staff per Branch</span>
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($stats['avg_staff_per_branch'], 1) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Average Orders per Branch</span>
                        <span class="text-xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($stats['avg_orders_per_branch'], 1) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Active Branch Rate</span>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $stats['total_branches'] > 0 ? number_format(($stats['active_branches'] / $stats['total_branches']) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                    <i class="fas fa-pie-chart mr-2 text-pink-500"></i>Status Distribution
                </h3>
                
                <div class="space-y-3">
                    @foreach($branchStatusDistribution as $status)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400 capitalize">{{ $status->status }}</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $status->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Performing Branches -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="fas fa-trophy mr-2 text-yellow-500"></i>Top Performing Branches
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($topPerformingBranches as $index => $branch)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($index === 0)
                                        <span class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold">1</span>
                                    @elseif($index === 1)
                                        <span class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">2</span>
                                    @elseif($index === 2)
                                        <span class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">3</span>
                                    @else
                                        <span class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->branch_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->branch_code }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $branch->city }}, {{ $branch->state }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $branch->orders_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $branch->staff_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $branch->status_badge !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- All Branches Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
                <i class="fas fa-list mr-2 text-blue-500"></i>All Branches Performance
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($branches as $branch)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->branch_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->branch_code }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $branch->city }}, {{ $branch->state }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->orders_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $branch->staff_count }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $branch->status_badge !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $maxOrders = $branches->max('orders_count');
                                    $performance = $maxOrders > 0 ? ($branch->orders_count / $maxOrders) * 100 : 0;
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full" 
                                             style="width: {{ $performance }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($performance, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.branches.create') }}" 
                class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
                <i class="fas fa-plus mr-2"></i>Add New Branch
            </a>
            <a href="{{ route('admin.branches.index') }}" 
                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
                <i class="fas fa-list mr-2"></i>View All Branches
            </a>
        </div>
    </div>
</div>
@endsection
