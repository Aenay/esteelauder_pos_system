@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Loyalty Record</h1>
        <p class="mt-2 text-gray-600">Update loyalty points and tier information</p>
    </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.loyalty.update', $loyalty) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="Customer_ID" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <select name="Customer_ID" id="Customer_ID" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->Customer_ID }}" {{ $loyalty->Customer_ID == $customer->Customer_ID ? 'selected' : '' }}>
                                {{ $customer->Customer_Name }} ({{ $customer->Customer_Email }})
                            </option>
                        @endforeach
                    </select>
                    @error('Customer_ID')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="points_earned" class="block text-sm font-medium text-gray-700 mb-2">Total Points Earned</label>
                    <input type="number" name="points_earned" id="points_earned" min="0" value="{{ old('points_earned', $loyalty->points_earned) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    @error('points_earned')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="points_used" class="block text-sm font-medium text-gray-700 mb-2">Total Points Used</label>
                    <input type="number" name="points_used" id="points_used" min="0" max="{{ $loyalty->points_earned }}" value="{{ old('points_used', $loyalty->points_used) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    <p class="mt-1 text-sm text-gray-500">Cannot exceed total points earned ({{ $loyalty->points_earned }})</p>
                    @error('points_used')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tier_level" class="block text-sm font-medium text-gray-700 mb-2">Tier Level</label>
                    <select name="tier_level" id="tier_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="bronze" {{ $loyalty->tier_level == 'bronze' ? 'selected' : '' }}>🥉 Bronze (0-99 points)</option>
                        <option value="silver" {{ $loyalty->tier_level == 'silver' ? 'selected' : '' }}>🥈 Silver (100-499 points)</option>
                        <option value="gold" {{ $loyalty->tier_level == 'gold' ? 'selected' : '' }}>🥇 Gold (500-999 points)</option>
                        <option value="platinum" {{ $loyalty->tier_level == 'platinum' ? 'selected' : '' }}>💎 Platinum (1000+ points)</option>
                    </select>
                    @error('tier_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('notes', $loyalty->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Balance Display -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Balance (Calculated)</label>
                    <div class="text-2xl font-bold text-green-600" id="current_balance_display">
                        {{ number_format($loyalty->points_earned - $loyalty->points_used) }}
                    </div>
                    <p class="text-sm text-gray-500">This will be automatically calculated based on points earned minus points used</p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.loyalty.show', $loyalty) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        Update Loyalty Record
                    </button>
                </div>
            </form>
        </div>
    </div>
    -->
    <div class="text-center py-12">
        <p class="text-gray-600">Loyalty Record Edit is temporarily unavailable.</p>
    </div>
</div>
@endsection

