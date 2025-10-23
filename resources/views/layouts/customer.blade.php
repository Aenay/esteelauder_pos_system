<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Esteé Lauder')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ec4899;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #be185d;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('customer.welcome') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-gem text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Esteé Lauder</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('customer.dashboard') }}" 
                       class="text-gray-700 hover:text-pink-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('customer.shop.index') }}" 
                       class="text-gray-700 hover:text-pink-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-bag mr-2"></i>Shop
                    </a>
                    <a href="{{ route('customer.orders.index') }}" 
                       class="text-gray-700 hover:text-pink-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-receipt mr-2"></i>Orders
                    </a>
                    <!-- Loyalty Navigation Link -->
                    <!--
                    <a href="{{ route('customer.loyalty') }}" 
                       class="text-gray-700 hover:text-pink-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-2"></i>Loyalty
                    </a>
                    -->
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('customer.cart.show') }}" class="relative text-gray-700 hover:text-pink-600">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="nav-cart-count" class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-pink-500">
                            <div class="w-8 h-8 bg-pink-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="ml-2 text-gray-700 font-medium">{{ Auth::guard('customer')->user()->Customer_Name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('customer.profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-edit mr-2"></i>Profile
                            </a>
                            <a href="{{ route('customer.orders.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-receipt mr-2"></i>Order History
                            </a>
                            <!-- Loyalty Points Menu Item -->
                            <!--
                            <a href="{{ route('customer.loyalty') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-star mr-2"></i>Loyalty Points
                            </a>
                            -->
                            <hr class="my-1">
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-pink-600 focus:outline-none focus:text-pink-600">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-gem text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold">Esteé Lauder</span>
                    </div>
                    <p class="text-gray-400 text-sm">Premium beauty products for the modern woman.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('customer.shop.index') }}" class="text-gray-400 hover:text-white">Shop</a></li>
                        <li><a href="{{ route('customer.orders.index') }}" class="text-gray-400 hover:text-white">Orders</a></li>
                        <!-- Loyalty Footer Link -->
                        <!--
                        <li><a href="{{ route('customer.loyalty') }}" class="text-gray-400 hover:text-white">Loyalty</a></li>
                        -->
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Shipping Info</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Connect</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Esteé Lauder. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
    
    <script>
        // Load cart count on page load
        $(document).ready(function() {
            loadCartCount();
        });
        
        function loadCartCount() {
            $.ajax({
                url: '{{ route("customer.cart.get") }}',
                method: 'GET',
                success: function(response) {
                    $('#nav-cart-count').text(response.item_count);
                }
            });
        }
    </script>
</body>
</html>