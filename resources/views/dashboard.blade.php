@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            </div>
        </header>
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium">Today's Sales</h3>
                    <p class="text-3xl font-bold text-gray-800">$1,450.00</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium">New Customers</h3>
                    <p class="text-3xl font-bold text-gray-800">12</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium">Orders Today</h3>
                    <p class="text-3xl font-bold text-gray-800">32</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-gray-500 text-sm font-medium">Products in Stock</h3>
                    <p class="text-3xl font-bold text-gray-800">1,204</p>
                </div>
            </div>
            <!-- Sales Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="font-bold text-xl mb-4">Weekly Sales Overview</h3>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [1200, 1900, 1500, 2100, 1800, 2400, 2000],
                    backgroundColor: 'rgba(219, 39, 119, 0.2)',
                    borderColor: 'rgba(219, 39, 119, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
@endsection
