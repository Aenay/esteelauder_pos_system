@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <div class="bg-white shadow">
        <div class="px-6 py-5">
            <h1 class="text-2xl font-bold text-gray-800">Edit Promotion</h1>
        </div>
    </div>
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('promotions.update', $promotion) }}" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input name="Promotion_Name" class="mt-1 w-full border rounded p-2" required value="{{ old('Promotion_Name', $promotion->Promotion_Name) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="Description" class="mt-1 w-full border rounded p-2" rows="3">{{ old('Description', $promotion->Description) }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                        <select name="Discount_Type" class="mt-1 w-full border rounded p-2" required>
                            <option value="percentage" {{ old('Discount_Type', $promotion->Discount_Type)=='percentage'?'selected':'' }}>Percentage</option>
                            <option value="fixed" {{ old('Discount_Type', $promotion->Discount_Type)=='fixed'?'selected':'' }}>Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Value</label>
                        <input name="Discount_Value" type="number" step="0.01" min="0" class="mt-1 w-full border rounded p-2" required value="{{ old('Discount_Value', $promotion->Discount_Value) }}">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center space-x-2">
                            <input type="checkbox" name="Is_Active" value="1" class="rounded" {{ old('Is_Active', $promotion->Is_Active) ? 'checked' : '' }}>
                            <span class="text-sm">Active</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input name="Start_Date" type="date" class="mt-1 w-full border rounded p-2" value="{{ old('Start_Date', optional($promotion->Start_Date)->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input name="End_Date" type="date" class="mt-1 w-full border rounded p-2" value="{{ old('End_Date', optional($promotion->End_Date)->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('promotions.index') }}" class="px-4 py-2 rounded border">Cancel</a>
                    <button class="px-4 py-2 rounded bg-pink-600 text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
