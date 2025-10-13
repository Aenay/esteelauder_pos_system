<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Estee Lauder POS') }} - Customer Portal</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 to-pink-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <!-- Logo -->
            <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-pink-600 shadow-lg mb-8">
                <i class="fas fa-crown text-white text-3xl"></i>
            </div>
            
            <!-- Welcome Message -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome to Estee Lauder Customer Portal
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Access your order history, track loyalty points, and manage your account
            </p>

            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Order History</h3>
                    <p class="text-gray-600">View all your past purchases and order details</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Loyalty Points</h3>
                    <p class="text-gray-600">Track your loyalty points and tier status</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100 mb-4">
                        <i class="fas fa-receipt text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Digital Receipts</h3>
                    <p class="text-gray-600">Access your digital receipts anytime</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth('customer')
                    <a href="{{ route('customer.orders.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        View My Orders
                    </a>
                    <a href="{{ route('customer.loyalty') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-pink-700 bg-pink-100 hover:bg-pink-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-star mr-2"></i>
                        Check Loyalty Points
                    </a>
                @else
                    <a href="{{ route('customer.login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign In
                    </a>
                    <a href="{{ route('customer.register') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </a>
                @endauth
            </div>

            <!-- Additional Info -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-500">
                    Need help? Contact our customer service at 
                    <a href="tel:+1-800-ESTEE-LAUDER" class="text-pink-600 hover:text-pink-500">
                        +1-800-ESTEE-LAUDER
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
