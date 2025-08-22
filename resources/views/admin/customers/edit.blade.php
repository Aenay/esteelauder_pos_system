@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Customer</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.customers.update', $customer->Customer_ID) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="Customer_Name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
                    <input type="text" name="Customer_Name" id="Customer_Name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('Customer_Name', $customer->Customer_Name) }}" required>
                </div>
                <div class="mb-4">
                    <label for="Customer_Phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                    <input type="text" name="Customer_Phone" id="Customer_Phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('Customer_Phone', $customer->Customer_Phone) }}">
                </div>
                <div class="mb-4">
                    <label for="Customer_Address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                    <textarea name="Customer_Address" id="Customer_Address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('Customer_Address', $customer->Customer_Address) }}</textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Customer</button>
                    <a href="{{ route('admin.customers.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
