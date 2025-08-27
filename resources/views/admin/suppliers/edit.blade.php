@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.suppliers.index') }}" class="text-purple-600 hover:text-purple-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Supplier</h1>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Supplier Name -->
                <div class="mb-6">
                    <label for="Supplier_Name" class="block text-sm font-medium text-gray-700 mb-2">
                        Supplier Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="Supplier_Name" 
                           id="Supplier_Name" 
                           value="{{ old('Supplier_Name', $supplier->Supplier_Name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('Supplier_Name') border-red-500 @enderror"
                           placeholder="Enter supplier name"
                           required>
                    @error('Supplier_Name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="mb-6">
                    <label for="Supplier_Phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" 
                           name="Supplier_Phone" 
                           id="Supplier_Phone" 
                           value="{{ old('Supplier_Phone', $supplier->Supplier_Phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('Supplier_Phone') border-red-500 @enderror"
                           placeholder="Enter phone number">
                    @error('Supplier_Phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="mb-6">
                    <label for="Supplier_Address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea name="Supplier_Address" 
                              id="Supplier_Address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('Supplier_Address') border-red-500 @enderror"
                              placeholder="Enter supplier address">{{ old('Supplier_Address', $supplier->Supplier_Address) }}</textarea>
                    @error('Supplier_Address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('notes') border-red-500 @enderror"
                              placeholder="Any additional information about the supplier">{{ old('notes', $supplier->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.suppliers.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>

        <!-- Supplier Info -->
        <div class="mt-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-purple-800 mb-3">Supplier Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-purple-700">Supplier ID:</span>
                    <span class="text-purple-600 ml-2">{{ $supplier->Supplier_ID }}</span>
                </div>
                <div>
                    <span class="font-medium text-purple-700">Created:</span>
                    <span class="text-purple-600 ml-2">{{ $supplier->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-medium text-purple-700">Last Updated:</span>
                    <span class="text-purple-600 ml-2">{{ $supplier->updated_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="font-medium text-purple-700">Total Deliveries:</span>
                    <span class="text-purple-600 ml-2">{{ $supplier->total_deliveries }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
