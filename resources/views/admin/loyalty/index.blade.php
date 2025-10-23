@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Loyalty Program Management Content -->
    <!--
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Loyalty Program Management</h1>
            <p class="mt-2 text-gray-600">Manage loyalty tiers, points, and member status</p>
        </div>
        <a href="{{ route('admin.loyalty.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
            Add New Loyalty Record
        </a>
    </div>

    <!-- Automatic Loyalty System Info -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Automatic Loyalty Points System</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-purple-700">
                    <div>
                        <span class="font-medium">🎯 Rate:</span> 1 point for every $10 spent
                    </div>
                    <div>
                        <span class="font-medium">👥 Eligibility:</span> Internal members only
                    </div>
                    <div>
                        <span class="font-medium">❌ Excluded:</span> External customers
                    </div>
                </div>
                <p class="text-sm text-purple-600 mt-2">
                    <strong>IMPORTANT:</strong> Only internal members (Customer_Type = 'internal') are eligible for loyalty points. 
                    External customers (Customer_Type = 'external') are automatically excluded from the loyalty system.
                </p>
                
                <!-- Test Calculator -->
                <div class="mt-4 p-4 bg-white rounded-lg border border-purple-200">
                    <h4 class="font-medium text-purple-800 mb-3">Test Point Calculation</h4>
                    <div class="flex items-center space-x-3">
                        <input type="number" id="testAmount" placeholder="Enter amount" 
                               class="px-3 py-2 border border-purple-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" 
                               step="0.01" min="0.01">
                        <button onclick="testLoyaltyCalculation()" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            Calculate
                        </button>
                    </div>
                    <div id="calculationResult" class="mt-3 text-sm text-purple-700 hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function testLoyaltyCalculation() {
        const amount = document.getElementById('testAmount').value;
        if (!amount || amount <= 0) {
            alert('Please enter a valid amount');
            return;
        }

        fetch('{{ route("admin.loyalty.test-calculation") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ amount: parseFloat(amount) })
        })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('calculationResult');
            resultDiv.innerHTML = `
                <div class="bg-purple-50 p-3 rounded-md">
                    <div class="font-medium">Amount: $${data.amount}</div>
                    <div class="font-medium text-purple-600">Points Awarded: ${data.points_awarded}</div>
                    <div class="text-xs text-purple-600">${data.calculation}</div>
                    ${data.points_awarded > 0 ? 
                        `<div class="text-xs text-purple-600">Next point at: $${data.next_threshold} ($${data.remaining_for_next_point.toFixed(2)} more needed)</div>` : 
                        `<div class="text-xs text-purple-600">$${data.remaining_for_next_point.toFixed(2)} more needed for first point</div>`
                    }
                </div>
            `;
            resultDiv.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error calculating points');
        });
    }
    </script>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_customers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                    <span class="text-2xl">🥉</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bronze Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['bronze_members'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                    <span class="text-2xl">🥈</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Silver Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['silver_members'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <span class="text-2xl">🥇</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gold+ Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['gold_members'] + $stats['platinum_members'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loyalty Records Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Loyalty Records</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points Earned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($loyaltyRecords as $loyalty)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $loyalty->customer->Customer_Name }}</div>
                            <div class="text-sm text-gray-500">{{ $loyalty->customer->Customer_Email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $loyalty->tier_color }}">
                                {{ $loyalty->tier_icon }} {{ ucfirst($loyalty->tier_level) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($loyalty->points_earned) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($loyalty->points_used) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($loyalty->current_balance) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loyalty->last_activity_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.loyalty.show', $loyalty) }}" class="text-pink-600 hover:text-pink-900 mr-3">View</a>
                            <a href="{{ route('admin.loyalty.edit', $loyalty) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.loyalty.destroy', $loyalty) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $loyaltyRecords->links() }}
        </div>
    </div>
    -->
    <div class="text-center py-12">
        <p class="text-gray-600">Loyalty Program Management is temporarily unavailable.</p>
    </div>
</div>
@endsection
