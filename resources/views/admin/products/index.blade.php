@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Product Management</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Product List</h2>
                    <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Product
                    </a>
                </div>
                <!-- Product Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Photo</th>
                                <th class="py-3 px-6 text-left">SKU</th>
                                <th class="py-3 px-6 text-left">Product Name</th>
                                <th class="py-3 px-6 text-left">Price</th>
                                <th class="py-3 px-6 text-center">Stock</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($products as $product)
                                <tr class="border-b">
                                    <td class="py-3 px-6">
                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-16 h-16 object-cover rounded-md">
                                    </td>
                                    <td class="py-3 px-6">{{ $product['sku'] }}</td>
                                    <td class="py-3 px-6 font-medium">{{ $product['name'] }}</td>
                                    <td class="py-3 px-6">${{ number_format($product['price'], 2) }}</td>
                                    <td class="py-3 px-6 text-center">{{ $product['stock'] }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="#" class="text-blue-500 hover:text-blue-700 mr-4"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
