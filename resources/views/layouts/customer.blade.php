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
                                <div class="relative inline-block text-left">
                                    <button id="profileButton" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-3 py-2 bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 focus:outline-none" aria-expanded="true" aria-haspopup="true">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ Auth::guard('customer')->user()->Customer_Name }}
                                        <i class="fas fa-caret-down ml-2"></i>
                                    </button>

                                    <!-- Dropdown panel -->
                                    <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="profileButton">
                                            <a href="{{ route('customer.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                <i class="fas fa-edit mr-2"></i>Edit profile
                                            </a>
                                            <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                <i class="fas fa-shopping-bag mr-2"></i>My Orders
                                            </a>
                                            <a href="{{ route('customer.loyalty') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                <i class="fas fa-star mr-2"></i>Loyalty Points
                                            </a>
                                            <form method="POST" action="{{ route('customer.logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
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

        <script>
            // Simple toggle for the profile dropdown
            document.addEventListener('click', function (e) {
                var btn = document.getElementById('profileButton');
                var dropdown = document.getElementById('profileDropdown');
                if (!btn) return;
                if (btn.contains(e.target)) {
                    dropdown.classList.toggle('hidden');
                } else if (!dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>

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
