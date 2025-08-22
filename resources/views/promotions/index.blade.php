@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Promotion Management</h1>
                <a href="{{ route('promotions.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add New Promotion
                </a>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                @if(session('success'))
                    <div class="mb-4 inline-flex items-center text-green-700 bg-green-100 border border-green-200 px-3 py-2 rounded">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 inline-flex items-center text-red-700 bg-red-100 border border-red-200 px-3 py-2 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200 text-gray-600">
                            <tr>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-center">Value</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-center">Active Window</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($promotions as $promotion)
                                <tr class="border-b">
                                    <td class="py-3 px-6 font-medium">{{ $promotion->Promotion_Name }}</td>
                                    <td class="py-3 px-6">{{ ucfirst($promotion->Discount_Type) }}</td>
                                    <td class="py-3 px-6 text-center">
                                        {{ strtolower($promotion->Discount_Type) === 'percentage' ? $promotion->Discount_Value.'%' : '$'.number_format($promotion->Discount_Value, 2) }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="{{ $promotion->Is_Active ? 'bg-green-200 text-green-700' : 'bg-gray-200 text-gray-700' }} py-1 px-3 rounded-full text-xs">
                                            {{ $promotion->Is_Active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center text-xs text-gray-500">
                                        {{ $promotion->Start_Date ? $promotion->Start_Date->format('Y-m-d') : '—' }} — {{ $promotion->End_Date ? $promotion->End_Date->format('Y-m-d') : '—' }}
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('promotions.edit', $promotion) }}" 
                                               class="text-blue-500 hover:text-blue-700 p-1 rounded hover:bg-blue-50" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('promotions.toggle', $promotion) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-{{ $promotion->Is_Active ? 'yellow' : 'green' }}-500 hover:text-{{ $promotion->Is_Active ? 'yellow' : 'green' }}-700 p-1 rounded hover:bg-{{ $promotion->Is_Active ? 'yellow' : 'green' }}-50"
                                                        title="{{ $promotion->Is_Active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $promotion->Is_Active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this promotion? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $promotions->links() }}</div>
            </div>
        </div>
    </main>
@endsection
