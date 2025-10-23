@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Loyalty Record</h1>
        <p class="mt-2 text-gray-600">Add a new loyalty record for a customer</p>
    </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.loyalty.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="Customer_ID" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <select name="Customer_ID" id="Customer_ID" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->Customer_ID }}" {{ old('Customer_ID') == $customer->Customer_ID ? 'selected' : '' }}>
                                {{ $customer->Customer_Name }} ({{ $customer->Customer_Email }})
                            </option>
                        @endforeach
                    </select>
                    @error('Customer_ID')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="points_earned" class="block text-sm font-medium text-gray-700 mb-2">Points to Award</label>
                    <input type="number" name="points_earned" id="points_earned" min="0" value="{{ old('points_earned', 0) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    @error('points_earned')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="tier_level" class="block text-sm font-medium text-gray-700 mb-2">Initial Tier Level</label>
                    <select name="tier_level" id="tier_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <option value="bronze" {{ old('tier_level') == 'bronze' ? 'selected' : '' }}>🥉 Bronze (0-99 points)</option>
                        <option value="silver" {{ old('tier_level') == 'silver' ? 'selected' : '' }}>🥈 Silver (100-499 points)</option>
                        <option value="gold" {{ old('tier_level') == 'gold' ? 'selected' : '' }}>🥇 Gold (500-999 points)</option>
                        <option value="platinum" {{ old('tier_level') == 'platinum' ? 'selected' : '' }}>💎 Platinum (1000+ points)</option>
                    </select>
                    @error('tier_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.loyalty.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        Create Loyalty Record
                    </button>
                </div>
            </form>
        </div>
    </div>
    -->
    <div class="text-center py-12">
        <p class="text-gray-600">Create New Loyalty Record is temporarily unavailable.</p>
    </div>
</div>
@endsection
