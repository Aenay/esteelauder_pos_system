<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to edit your profile.');
        }

        return view('customer.profile.edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (! $customer) {
            return redirect()->route('customer.login')->with('error', 'Please log in to edit your profile.');
        }

        $data = $request->validate([
            'Customer_Name' => 'required|string|max:255',
            'Customer_Phone' => 'nullable|string|max:50',
            'Customer_Address' => 'nullable|string|max:500',
            'Customer_Email' => 'nullable|email|max:255',
            'password' => 'nullable|confirmed|min:8',
            'current_password' => 'nullable|string',
        ]);

        // Password change logic
        if (!empty($data['password'])) {
            // Require current password to match
            if (empty($data['current_password']) || !Hash::check($data['current_password'], $customer->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }
        } else {
            unset($data['password']);
        }
        unset($data['current_password']);

        $customer->fill($data);
        $customer->save();

        return redirect()->route('customer.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
