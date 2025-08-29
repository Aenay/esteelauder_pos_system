@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $branch->branch_name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Branch Code: {{ $branch->branch_code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.branches.edit', $branch) }}" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Branch
                </a>
                <a href="{{ route('admin.branches.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Branches
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            {!! $branch->status_badge !!}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Branch Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Branch Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Branch Details</h4>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Name:</span> {{ $branch->branch_name }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Code:</span> {{ $branch->branch_code }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Status:</span> {!! $branch->status_badge !!}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium">Created:</span> {{ $branch->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Information</h4>
                            <div class="space-y-2">
                                @if($branch->phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Phone:</span> {{ $branch->phone }}
                                    </p>
                                @endif
                                @if($branch->email)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Email:</span> {{ $branch->email }}
                                    </p>
                                @endif
                                @if($branch->manager_name)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Manager:</span> {{ $branch->manager_name }}
                                    </p>
                                @endif
                                @if($branch->manager_phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Manager Phone:</span> {{ $branch->manager_phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Address
                    </h3>
                    
                    <div class="space-y-2">
                        <p class="text-gray-900 dark:text-white">{{ $branch->address }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ $branch->city }}, {{ $branch->state }} {{ $branch->postal_code }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ $branch->country }}</p>
                    </div>
                </div>

                <!-- Opening Hours Card -->
                @if($branch->opening_hours)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-clock mr-2 text-yellow-500"></i>Opening Hours
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            @if(isset($branch->opening_hours[$day]))
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $day }}</span>
                                    @if($branch->opening_hours[$day]['closed'])
                                        <span class="text-red-500 text-sm font-medium">Closed</span>
                                    @else
                                        <span class="text-green-600 dark:text-green-400 text-sm">
                                            {{ $branch->opening_hours[$day]['open'] }} - {{ $branch->opening_hours[$day]['close'] }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Notes Card -->
                @if($branch->notes)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-sticky-note mr-2 text-indigo-500"></i>Notes
                    </h3>
                    
                    <p class="text-gray-700 dark:text-gray-300">{{ $branch->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Quick Stats
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Staff Members</span>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">N/A</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">N/A</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Branch Age</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $branch->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Note
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Staff and orders data is not available since branch relationships are not configured.
                    </p>
                </div>

                @if($recentOrders->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-shopping-cart mr-2 text-pink-500"></i>Recent Orders
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($recentOrders->take(5) as $order)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Order #{{ $order->order_number }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $order->customer->name ?? 'Walk-in Customer' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                    RM {{ number_format($order->total_amount, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('M d') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($recentOrders->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.orders.index') }}?branch={{ $branch->id }}" 
                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            View All Orders →
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Staff Members -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>Note
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Staff data is not available since branch relationships are not configured.
                    </p>
                </div>

                @if($staffMembers->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-users mr-2 text-indigo-500"></i>Staff Members
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($staffMembers->take(5) as $staff)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">{{ substr($staff->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $staff->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $staff->department->name ?? 'No Department' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($staffMembers->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            View All Staff →
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline" 
                onsubmit="return confirm('Are you sure you want to delete this branch? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Branch
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
