<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderByDesc('Is_Active')->orderBy('Promotion_Name')->paginate(15);
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Promotion_Name' => ['required', 'string', 'max:255'],
            'Description' => ['nullable', 'string', 'max:500'],
            'Discount_Type' => ['required', 'in:percentage,fixed'],
            'Discount_Value' => [
                'required', 
                'numeric', 
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('Discount_Type') === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                }
            ],
            'Start_Date' => ['nullable', 'date', 'after_or_equal:today'],
            'End_Date' => ['nullable', 'date', 'after:Start_Date'],
            'Is_Active' => ['sometimes', 'boolean'],
        ], [
            'Promotion_Name.required' => 'Promotion name is required.',
            'Discount_Type.required' => 'Please select a discount type.',
            'Discount_Value.required' => 'Discount value is required.',
            'Discount_Value.min' => 'Discount value must be at least 0.',
            'Start_Date.after_or_equal' => 'Start date must be today or in the future.',
            'End_Date.after' => 'End date must be after start date.',
        ]);
        
        $data['Is_Active'] = $request->boolean('Is_Active');
        Promotion::create($data);
        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully');
    }

    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'Promotion_Name' => ['required', 'string', 'max:255'],
            'Description' => ['nullable', 'string', 'max:500'],
            'Discount_Type' => ['required', 'in:percentage,fixed'],
            'Discount_Value' => [
                'required', 
                'numeric', 
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('Discount_Type') === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                }
            ],
            'Start_Date' => ['nullable', 'date'],
            'End_Date' => ['nullable', 'date', 'after:Start_Date'],
            'Is_Active' => ['sometimes', 'boolean'],
        ], [
            'Promotion_Name.required' => 'Promotion name is required.',
            'Discount_Type.required' => 'Please select a discount type.',
            'Discount_Value.required' => 'Discount value is required.',
            'Discount_Value.min' => 'Discount value must be at least 0.',
            'End_Date.after' => 'End date must be after start date.',
        ]);
        
        $data['Is_Active'] = $request->boolean('Is_Active');
        $promotion->update($data);
        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully');
    }

    public function destroy(Promotion $promotion)
    {
        // Check if promotion is used in any orders
        if ($promotion->orders()->exists()) {
            return redirect()->route('promotions.index')
                ->with('error', 'Cannot delete promotion: It is used in existing orders. Consider deactivating it instead.');
        }
        
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully');
    }

    public function toggle(Promotion $promotion)
    {
        $promotion->update(['Is_Active' => !$promotion->Is_Active]);
        $status = $promotion->Is_Active ? 'activated' : 'deactivated';
        return redirect()->route('promotions.index')->with('success', "Promotion {$status} successfully");
    }
}
