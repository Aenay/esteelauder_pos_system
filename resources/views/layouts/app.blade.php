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
    <!-- jQuery CDN (Primary) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- jQuery Local Fallback (if CDN fails) -->
    <script>
        window.jQuery || document.write('<script src="{{ asset('js/jquery-3.7.1.min.js') }}"><\/script>');
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-link {
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: #db2777;
            color: white;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-200 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-200 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 dark:bg-gray-950 text-white flex flex-col">
            <div class="h-20 flex items-center justify-center bg-gray-900 dark:bg-gray-800">
                <h1 class="text-2xl font-bold">Estee Lauder</h1>
                <!-- <button id="darkModeToggle" class="ml-4 px-3 py-1 rounded bg-gray-700 dark:bg-gray-600 text-white hover:bg-pink-500 dark:hover:bg-pink-600 transition-colors" title="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button> -->
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/pos') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('pos') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-cash-register w-6"></i><span>POS</span>
                </a>

                @can('view-products')
                <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('products') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-box-open w-6"></i><span>Products</span>
                </a>
                @endcan
                @can('orders.view')
                <a href="{{ route('admin.orders.index')  }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('orders') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-receipt w-6"></i><span>Orders</span>
                </a>
                @endcan
                @can('view-staff-performance')
                <a href="{{ route('admin.staff-performances.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('admin.staff-performances.*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-chart-line w-6"></i><span>Staff Performance</span>
                </a>
                @endcan
                <!-- @can('assign-roles')
                <a href="{{ route('admin.roles-permissions.index') }}" class="flex items-center px-4 py-2 rounded {{ request()->routeIs('admin.roles-permissions.*') ? 'bg-pink-600 text-white' : 'text-gray-300 hover:bg-pink-700 hover:text-white' }}">
                    <i class="fas fa-user-shield w-5"></i>
                    <span class="ml-3">Roles & Permissions</span>
                </a>
                @endcan -->
                @can('view-promotions')
                <a href="{{ route('promotions.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('promotions') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-tags w-6"></i><span>Promotions</span>
                </a>
                @endcan
                @can('manage-customers')
                <a href="{{ route('admin.customers.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/customers*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-users w-6"></i><span>Customers</span>
                </a>
                @endcan
                @can('view-suppliers')
                <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/suppliers*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-truck w-6"></i><span>Suppliers</span>
                </a>
                @endcan
                @can('view-deliveries')
                <a href="{{ route('admin.deliveries.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/deliveries*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-shipping-fast w-6"></i><span>Deliveries</span>
                </a>
                @endcan
                <!-- @can('view-branches')
                <a href="{{ route('admin.branches.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/branches*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-building w-6"></i><span>Branches</span>
                </a>
                @endcan -->
                <!-- @can('view-loyalty')
                <a href="{{ route('admin.loyalty.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/loyalty*') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-star w-6"></i><span>Loyalty Points</span>
                </a>
                @endcan -->
                @can('manage-users')
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->is('admin/users') ? 'active' : '' }} dark:hover:bg-pink-600 dark:active:bg-pink-700">
                    <i class="fas fa-user-shield w-6"></i><span>Admin</span>
                </a>
                @endcan
            </nav>
            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-700 dark:border-gray-800">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full" src="https://placehold.co/100x100/fce7f3/4a044e?text=JD" alt="User Avatar">
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <div class="flex items-center space-x-3 mt-1">
                            <a href="{{ route('profile.edit') }}" class="text-xs text-gray-400 dark:text-gray-300 hover:text-white transition-colors" title="Profile">
                                <i class="fas fa-user-circle mr-1"></i>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="text-xs text-pink-400 dark:text-pink-300 hover:text-pink-300 dark:hover:text-pink-200 transition-colors" title="Logout" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt mr-1"></i>
                                    Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-6">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>
@stack('scripts')
</body>

</html>