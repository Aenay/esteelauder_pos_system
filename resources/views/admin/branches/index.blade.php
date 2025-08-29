@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Branch Management</h1>
        <a href="{{ route('admin.branches.create') }}" class="bg-pink-600  hover:bg-pink-700  text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
            <i class="fas fa-plus mr-2"></i>New Branch
        </a>
    </div>

    <!-- Statistics Cards -->
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

    <!-- Branches Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">All Branches</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Manager</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($branches as $branch)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $branch->branch_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->branch_code }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $branch->city }}, {{ $branch->state }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->country }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($branch->manager_name)
                                <div class="text-sm text-gray-900 dark:text-white">{{ $branch->manager_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->manager_phone }}</div>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">Not assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $branch->status_badge !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $branch->staff_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $branch->orders_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.branches.show', $branch) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.branches.edit', $branch) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this branch?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-building text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                <p class="text-lg font-medium">No branches found</p>
                                <p class="text-sm">Create your first branch to get started</p>
                                <a href="{{ route('admin.branches.create') }}" class="mt-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Create Branch
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($branches->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $branches->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('admin.branches.analytics') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
            <i class="fas fa-chart-bar mr-2"></i>Branch Analytics
        </a>
    </div>
</div>
@endsection
