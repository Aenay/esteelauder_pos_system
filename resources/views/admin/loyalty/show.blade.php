@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('admin.loyalty.index') }}" class="text-pink-600 hover:text-pink-900 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Loyalty Record Details</h1>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.loyalty.edit', $loyalty) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Record
                </a>
                <form action="{{ route('admin.loyalty.destroy', $loyalty) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Are you sure you want to delete this record?')">
                        Delete Record
                    </button>
                </form>
            </div>
        </div>
        <p class="mt-2 text-gray-600">View detailed information about this loyalty record</p>
    </div>

        <!-- Customer Information Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Customer Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $loyalty->customer->Customer_Name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Customer Email</label>
                    <p class="text-lg text-gray-900">{{ $loyalty->customer->Customer_Email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Customer Phone</label>
                    <p class="text-lg text-gray-900">{{ $loyalty->customer->Customer_Phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Customer Type</label>
                    <p class="text-lg text-gray-900 capitalize">{{ $loyalty->customer->Customer_Type ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Loyalty Points Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Loyalty Points</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-pink-600">{{ number_format($loyalty->points_earned) }}</div>
                    <div class="text-sm text-gray-600">Total Points Earned</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($loyalty->points_used) }}</div>
                    <div class="text-sm text-gray-600">Total Points Used</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ number_format($loyalty->current_balance) }}</div>
                    <div class="text-sm text-gray-600">Current Balance</div>
                </div>
            </div>
        </div>

        <!-- Tier Information Card -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tier Information</h2>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">{{ $loyalty->tier_icon }}</span>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 capitalize">{{ $loyalty->tier_level }} Tier</div>
                        <div class="text-sm text-gray-600">Current Membership Level</div>
                    </div>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium {{ $loyalty->tier_color }}">
                    {{ $loyalty->tier_icon }} {{ ucfirst($loyalty->tier_level) }}
                </span>
            </div>
            
            <!-- Progress to Next Tier -->
            @if($loyalty->tier_level !== 'platinum')
            <div class="mt-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Progress to next tier</span>
                    <span>{{ round($loyalty->next_tier_progress, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-600 h-2 rounded-full" style="width: {{ $loyalty->next_tier_progress }}%"></div>
                </div>
            </div>
            @else
            <div class="mt-6 text-center">
                <div class="text-green-600 font-semibold">🎉 Platinum Member - Maximum Tier Achieved!</div>
            </div>
            @endif
        </div>

        <!-- Activity Information -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Activity Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Last Activity Date</label>
                    <p class="text-lg text-gray-900">{{ $loyalty->last_activity_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Member Since</label>
                    <p class="text-lg text-gray-900">{{ $loyalty->created_at->format('F d, Y') }}</p>
                </div>
            </div>
            @if($loyalty->notes)
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-600">Notes</label>
                <p class="text-lg text-gray-900">{{ $loyalty->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <a href="{{ route('admin.loyalty.edit', $loyalty) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Record
                </a>
                <form action="{{ route('admin.loyalty.destroy', $loyalty) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Are you sure you want to delete this loyalty record?')">
                        Delete Record
                    </button>
                </form>
            </div>
            <a href="{{ route('admin.loyalty.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a></div>
        </div>
    </div>
    -->
    <div class="text-center py-12">
        <p class="text-gray-600">Loyalty Record Details are temporarily unavailable.</p>
    </div>
</div>
@endsection

