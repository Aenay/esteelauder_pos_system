@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Branch</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Add a new branch to your Esteé Lauder network</p>
            </div>
            <a href="{{ route('admin.branches.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Branches
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.branches.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>Basic Information
                        </h3>
                        
                        <div>
                            <label for="branch_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="branch_code" id="branch_code" value="{{ old('branch_code') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., BR001, KL001">
                            @error('branch_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="branch_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="branch_name" id="branch_name" value="{{ old('branch_name') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., Kuala Lumpur Central, Penang Mall">
                            @error('branch_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Address Information
                        </h3>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" id="address" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Full street address">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="e.g., Kuala Lumpur">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="state" id="state" value="{{ old('state') }}" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="e.g., Selangor">
                                @error('state')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Postal Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="e.g., 50000">
                                @error('postal_code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" id="country" value="{{ old('country', 'Malaysia') }}" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-phone mr-2 text-purple-500"></i>Contact Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch Phone
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., +60 3 1234 5678">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., klcentral@esteelauder.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Manager Information -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-user-tie mr-2 text-pink-500"></i>Manager Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="manager_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Manager Name
                            </label>
                            <input type="text" name="manager_name" id="manager_name" value="{{ old('manager_name') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., Sarah Johnson">
                            @error('manager_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="manager_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Manager Phone
                            </label>
                            <input type="tel" name="manager_phone" id="manager_phone" value="{{ old('manager_phone') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., +60 12 345 6789">
                            @error('manager_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="manager_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Manager Email
                            </label>
                            <input type="email" name="manager_email" id="manager_email" value="{{ old('manager_email') }}" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., sarah.j@esteelauder.com">
                            @error('manager_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-clock mr-2 text-yellow-500"></i>Opening Hours
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ $day }}</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Open</label>
                                    <input type="time" name="opening_hours[{{ strtolower($day) }}][open]" 
                                        class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Close</label>
                                    <input type="time" name="opening_hours[{{ strtolower($day) }}][close]" 
                                        class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                        <i class="fas fa-sticky-note mr-2 text-indigo-500"></i>Additional Notes
                    </h3>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            placeholder="Any additional information about this branch...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('admin.branches.index') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition-all duration-200 hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Create Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
