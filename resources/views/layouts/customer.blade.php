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
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-pink-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('customer.orders.index') }}" class="text-white text-xl font-bold">
                                <i class="fas fa-crown mr-2"></i>Estee Lauder Customer Portal
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @auth('customer')
                            <div class="flex items-center space-x-4">
                                <span class="text-white">
                                    <i class="fas fa-user mr-1"></i>
                                    Welcome, {{ Auth::guard('customer')->user()->Customer_Name }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('customer.orders.index') }}" class="text-white hover:text-pink-200 px-3 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-shopping-bag mr-1"></i>My Orders
                                    </a>
                                    <a href="{{ route('customer.loyalty') }}" class="text-white hover:text-pink-200 px-3 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-star mr-1"></i>Loyalty Points
                                    </a>
                                    <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-white hover:text-pink-200 px-3 py-2 rounded-md text-sm font-medium">
                                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex space-x-2">
                                <a href="{{ route('customer.login') }}" class="text-white hover:text-pink-200 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Login
                                </a>
                                <a href="{{ route('customer.register') }}" class="bg-pink-700 hover:bg-pink-800 text-white px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-user-plus mr-1"></i>Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
