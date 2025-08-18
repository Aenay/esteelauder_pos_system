@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Promotion Management</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Promotion List</h2>
                    <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New Promotion
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-center">Value</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($promotions as $promotion)
                                <tr class="border-b">
                                    <td class="py-3 px-6 font-medium">{{ $promotion['name'] }}</td>
                                    <td class="py-3 px-6">{{ $promotion['type'] }}</td>
                                    <td class="py-3 px-6 text-center">{{ $promotion['value'] }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="{{ $promotion['status'] == 'Active' ? 'bg-green-200 text-green-700' : 'bg-gray-200 text-gray-700' }} py-1 px-3 rounded-full text-xs">
                                            {{ $promotion['status'] }}
                                        </span>
                                    </td>
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
