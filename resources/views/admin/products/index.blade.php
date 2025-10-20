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
                    <a href="{{ route('admin.products.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Product
                    </a>
                </div>

                <!-- Search Form -->
                <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
                    <div class="flex items-center">
                        <input type="text" name="search" placeholder="Search by name or SKU" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ request('search') }}">
                        <button type="submit" class="ml-2 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Search</button>
                    </div>
                </form>

                <!-- Product Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Photo</th>
                                <th class="py-3 px-6 text-left">SKU</th>
                                <th class="py-3 px-6 text-left">Product Name</th>
                                <th class="py-3 px-6 text-left">Supplier</th>
                                <th class="py-3 px-6 text-left">Price</th>
                                <th class="py-3 px-6 text-center">Stock</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($products as $product)
                                <tr class="border-b">
                                    <td class="py-3 px-6">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->Product_Name }}" class="w-16 h-16 object-cover rounded-md">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                                <span class="text-xs text-gray-500">No Image</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">{{ $product->SKU }}</td>
                                    <td class="py-3 px-6 font-medium">{{ $product->Product_Name }}</td>
                                    <td class="py-3 px-6">
                                        @if($product->supplier)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                <i class="fas fa-truck mr-1"></i>
                                                {{ $product->supplier->Supplier_Name }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-sm">No supplier</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">${{ number_format($product->Price, 2) }}</td>
                                    <td class="py-3 px-6 text-center">{{ $product->Quantity_on_Hand }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:text-blue-700 mr-4"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this product?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-gray-500">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>
@endsection