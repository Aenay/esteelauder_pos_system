<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
            .sidebar-link { transition: background-color 0.2s, color 0.2s; }
            .sidebar-link:hover, .sidebar-link.active { background-color: #db2777; color: white; }
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #555; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-200">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-800 text-white flex flex-col">
                <div class="h-20 flex items-center justify-center bg-gray-900">
                    <h1 class="text-2xl font-bold">Estee Lauder</h1>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ url('/dashboard') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-6"></i><span>Dashboard</span>
                    </a>
                    <a href="{{ url('/pos') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('pos') ? 'active' : '' }}">
                        <i class="fas fa-cash-register w-6"></i><span>POS</span>
                    </a>
                    <a href="{{ url('/products') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('products') ? 'active' : '' }}">
                        <i class="fas fa-box-open w-6"></i><span>Products</span>
                    </a>
                    <a href="{{ url('/orders') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('orders') ? 'active' : '' }}">
                        <i class="fas fa-receipt w-6"></i><span>Orders</span>
                    </a>
                    <a href="{{ url('/promotions') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('promotions') ? 'active' : '' }}">
                        <i class="fas fa-tags w-6"></i><span>Promotions</span>
                    </a>
                    <a href="#" class="sidebar-link flex items-center px-4 py-2 rounded-lg">
                        <i class="fas fa-users w-6"></i><span>Customers</span>
                    </a>
                </nav>
            </aside>
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                @yield('content')
            </div>
        </div>
    </body>
</html>