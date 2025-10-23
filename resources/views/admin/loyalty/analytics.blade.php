@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Loyalty Program Analytics -->
    <!--
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.loyalty.index') }}" class="text-pink-600 hover:text-pink-900 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Loyalty Program Analytics</h1>
    </div>

    <!-- Tier Distribution Chart -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Member Distribution by Tier</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($tierDistribution as $tier)
            <div class="text-center p-4 rounded-lg border">
                <div class="text-3xl mb-2">
                    @switch($tier->tier_level)
                        @case('bronze')
                            🥉
                            @break
                        @case('silver')
                            🥈
                            @break
                        @case('gold')
                            🥇
                            @break
                        @case('platinum')
                            💎
                            @break
                        @default
                            ⭐
                    @endswitch
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $tier->count }}</div>
                <div class="text-sm text-gray-600 capitalize">{{ $tier->tier_level }} Members</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Monthly Points Chart -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Monthly Points Activity ({{ now()->year }})</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points Earned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Points</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyPoints as $monthly)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::createFromDate(now()->year, $monthly->month, 1)->format('F') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">
                            {{ number_format($monthly->total_earned) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">
                            {{ number_format($monthly->total_used) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($monthly->total_earned - $monthly->total_used) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.loyalty.create') }}" 
               class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                Add New Loyalty Record
            </a>
            <a href="{{ route('admin.loyalty.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                View All Records
            </a>
        </div>
    </div>
</div>
    -->
    <div class="text-center py-12">
        <p class="text-gray-600">Loyalty Program Analytics is temporarily unavailable.</p>
    </div>
</div>
@endsection

