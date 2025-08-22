@extends('layouts.app')

@section('content')
    <div class="flex h-full">
        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-md">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-800">Customer Management</h1>
                </div>
            </header>
            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Customer List</h2>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-undo-alt mr-2"></i>Return Product
                            </a>
                            <a href="{{ route('admin.customers.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add New Customer
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200 text-gray-600">
                                <tr>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Phone</th>
                                    <th class="py-3 px-6 text-left">Address</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($customers as $customer)
                                    <tr class="border-b @if($loop->even) bg-gray-50 @endif">
                                        <td class="py-3 px-6 font-medium">{{ $customer->Customer_Name }}</td>
                                        <td class="py-3 px-6">{{ $customer->Customer_Phone }}</td>
                                        <td class="py-3 px-6">{{ $customer->Customer_Address }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ route('admin.customers.show', $customer->Customer_ID) }}" class="text-gray-500 hover:text-blue-700 mr-4" title="View Details"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.customers.edit', $customer->Customer_ID) }}" class="text-gray-500 hover:text-blue-700 mr-4" title="Edit Customer"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.customers.destroy', $customer->Customer_ID) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-700" title="Delete Customer"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($customers->isEmpty())
                                    <tr>
                                        <td colspan="4" class="py-3 px-6 text-center text-gray-500">No customers found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
