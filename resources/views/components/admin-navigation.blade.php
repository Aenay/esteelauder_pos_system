<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="text-white font-bold text-xl">
                        Admin Panel
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 text-white' : '' }}">
                            Products
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.customers.*') ? 'bg-gray-900 text-white' : '' }}">
                            Customers
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-gray-900 text-white' : '' }}">
                            Orders
                        </a>
                        <a href="{{ route('admin.staff-performances.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.staff-performances.*') ? 'bg-gray-900 text-white' : '' }}">
                            Staff Performance
                        </a>
                        <a href="{{ route('admin.deliveries.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.deliveries.*') ? 'bg-gray-900 text-white' : '' }}">
                            Deliveries
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 text-white' : '' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.roles-permissions.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.roles-permissions.*') ? 'bg-gray-900 text-white' : '' }}">
                            Roles & Permissions
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="ml-3 relative">
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-300 text-sm">{{ Auth::user()->name }}</span>
                            <a href="{{ route('pos.index') }}" class="text-gray-300 hover:text-white text-sm">
                                Back to Main
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-white text-sm">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button type="button" class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="md:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 text-white' : '' }}">
                Products
            </a>
            <a href="{{ route('admin.customers.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.customers.*') ? 'bg-gray-900 text-white' : '' }}">
                Customers
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-gray-900 text-white' : '' }}">
                Orders
            </a>
            <a href="{{ route('admin.staff-performances.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.staff-performances.*') ? 'bg-gray-900 text-white' : '' }}">
                Staff Performance
            </a>
            <a href="{{ route('admin.deliveries.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.deliveries.*') ? 'bg-gray-900 text-white' : '' }}">
                Deliveries
            </a>
            <a href="{{ route('admin.users.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 text-white' : '' }}">
                Users
            </a>
            <a href="{{ route('admin.roles-permissions.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('admin.roles-permissions.*') ? 'bg-gray-900 text-white' : '' }}">
                Roles & Permissions
            </a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">{{ substr(Auth::user()->name, 0, 2) }}</span>
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                    Back to Main
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
