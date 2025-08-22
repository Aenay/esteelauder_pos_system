@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Customer Details</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Name:</p>
                <p class="text-gray-900">{{ $customer->Customer_Name }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Phone:</p>
                <p class="text-gray-900">{{ $customer->Customer_Phone }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Address:</p>
                <p class="text-gray-900">{{ $customer->Customer_Address }}</p>
            </div>
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.customers.edit', $customer->Customer_ID) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit Customer</a>
                <a href="{{ route('admin.customers.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Back to List</a>
            </div>
        </div>
    </div>
@endsection
