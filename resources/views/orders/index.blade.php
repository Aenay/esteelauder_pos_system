@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Order History</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">All Orders</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Order ID</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-center">Total Amount</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($orders as $order)
                                <tr class="border-b">
                                    <td class="py-3 px-6">#{{ $order['id'] }}</td>
                                    <td class="py-3 px-6 font-medium">{{ $order['customer'] }}</td>
                                    <td class="py-3 px-6">{{ $order['date'] }}</td>
                                    <td class="py-3 px-6 text-center">${{ number_format($order['amount'], 2) }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="{{ $order['status'] == 'Completed' ? 'bg-green-200 text-green-700' : 'bg-yellow-200 text-yellow-700' }} py-1 px-3 rounded-full text-xs">
                                            {{ $order['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <a href="#" class="text-blue-500 hover:text-blue-700"><i class="fas fa-eye"></i></a>
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
