

@extends('layouts.customer')

@section('content')
<div class="w-full max-w-2xl mx-auto py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Account</h1>
        <p class="text-gray-500">View and edit your personal information and password.</p>
    </div>

    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                {{-- <div class="text-red-700 font-semibold mb-2">
                    <i class="fas fa-exclamation-circle mr-1"></i> Please fix the following errors:
                </div> --}}
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h2>
            <div class="mb-4">
                <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="full-name" name="Customer_Name" value="{{ old('Customer_Name', $customer->Customer_Name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                @error('Customer_Name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="Customer_Email" value="{{ old('Customer_Email', $customer->Customer_Email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                @error('Customer_Email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (Optional)</label>
                <input type="tel" id="phone" name="Customer_Phone" value="{{ old('Customer_Phone', $customer->Customer_Phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Enter your phone number">
                @error('Customer_Phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-2">
                <span class="text-xs text-gray-400">Member since: {{ $customer->Registration_Date ? $customer->Registration_Date->format('M d, Y') : 'N/A' }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Address</h2>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Primary Address (Optional)</label>
                <textarea id="address" name="Customer_Address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Enter your full address">{{ old('Customer_Address', $customer->Customer_Address) }}</textarea>
                @error('Customer_Address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Change Password</h2>
            <div class="mb-4">
                <label for="current-password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" id="current-password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Enter your current password">
            </div>
            <div class="mb-4">
                <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" id="new-password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Create a new password">
                @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" id="confirm-password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500" placeholder="Confirm your new password">
            </div>
        </div>

        <button type="submit" class="w-full py-3 bg-pink-600 text-white font-semibold rounded-lg shadow hover:bg-pink-700 transition">Save Changes</button>
    </form>
</div>
@endsection
