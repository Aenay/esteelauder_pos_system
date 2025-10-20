<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    /**
     * Display the customer login view.
     */
    public function showLoginForm(): View
    {
        return view('customer.auth.login');
    }

    /**
     * Handle an incoming customer authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find customer by Customer_Email column
        $customer = Customer::where('Customer_Email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            Auth::guard('customer')->login($customer, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Display the customer registration view.
     */
    public function showRegisterForm(): View
    {
        return view('customer.auth.register');
    }

    /**
     * Handle an incoming customer registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'Customer_Name' => ['required', 'string', 'max:255'],
            'Customer_Email' => ['required', 'string', 'email', 'max:255', 'unique:customers,Customer_Email'],
            'Customer_Phone' => ['nullable', 'string', 'max:20'],
            'Customer_Address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'Customer_Name' => $request->Customer_Name,
            'Customer_Email' => $request->Customer_Email,
            'Customer_Phone' => $request->Customer_Phone,
            'Customer_Address' => $request->Customer_Address,
            'Customer_Type' => 'internal',
            'Registration_Date' => now(),
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect(route('customer.dashboard'));
    }

    /**
     * Destroy an authenticated customer session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
