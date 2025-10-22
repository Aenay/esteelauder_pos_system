@extends('layouts.customer')

@section('title', 'Shop - Esteé Lauder')

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Shop</h1>
                    <p class="text-gray-600">Discover our premium beauty products</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('customer.cart.show') }}" class="relative bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Cart
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>
                    
                    <!-- Customer Info -->
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $customer->Customer_Name }}</p>
                        <p class="text-xs text-gray-500">{{ $customer->loyalty_tier }} Member</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text" id="product-search" placeholder="Search products..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow flex flex-col h-full" 
                     data-name="{{ strtolower($product->Product_Name) }}"
                     data-id="{{ $product->Product_ID }}">
                    <!-- Product Image -->
                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->Product_Name }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-1 flex flex-col flex-grow">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">{{ $product->Product_Name }}</h3>
                        <p class="text-2xl font-bold text-pink-600 mb-2">${{ number_format($product->Price, 2) }}</p>
                        <p class="text-sm text-gray-600 mb-4">
                            Stock: <span class="{{ $product->Quantity_on_Hand > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->Quantity_on_Hand }}
                            </span>
                        </p>
                        
                        <!-- Add to Cart Form -->
                        <div class="mt-auto">
                            <form class="add-to-cart-form" data-product-id="{{ $product->Product_ID }}">
                                <div class="flex items-center space-x-2 mb-3">
                                    <label for="quantity-{{ $product->Product_ID }}" class="text-sm font-medium text-gray-700">Qty:</label>
                                    <input type="number" 
                                           id="quantity-{{ $product->Product_ID }}" 
                                           name="quantity" 
                                           min="1" 
                                           max="{{ $product->Quantity_on_Hand }}"
                                           value="1" 
                                           class="w-16 px-2 py-1 border border-gray-300 rounded text-center">
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        {{ $product->Quantity_on_Hand == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus mr-2"></i>
                                    {{ $product->Quantity_on_Hand > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- No Products Message -->
        <div id="no-products" class="hidden text-center py-12">
            <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-600">Try adjusting your search terms</p>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load cart count on page load
    loadCartCount();
    
    // Product search
    $('#product-search').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        const productCards = $('.product-card');
        let visibleCount = 0;
        
        productCards.each(function() {
            const productName = $(this).data('name');
            if (productName.includes(query)) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });
        
        if (visibleCount === 0 && query !== '') {
            $('#no-products').removeClass('hidden');
        } else {
            $('#no-products').addClass('hidden');
        }
    });
    
    // Add to cart functionality
    $('.add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const productId = form.data('product-id');
        const quantity = form.find('input[name="quantity"]').val();
        const button = form.find('button[type="submit"]');
        
        // Disable button during request
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Adding...');
        
        $.ajax({
            url: '{{ route("customer.cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    loadCartCount();
                } else {
                    showMessage('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showMessage('error', response.message || 'An error occurred');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-cart-plus mr-2"></i>Add to Cart');
            }
        });
    });
    
    function loadCartCount() {
        $.ajax({
            url: '{{ route("customer.cart.get") }}',
            method: 'GET',
            success: function(response) {
                $('#cart-count').text(response.item_count);
            }
        });
    }
    
    function showMessage(type, message) {
        const alertClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const messageHtml = `
            <div class="${alertClass} text-white px-6 py-3 rounded-lg shadow-lg mb-2 flex items-center">
                <i class="fas ${icon} mr-2"></i>
                ${message}
            </div>
        `;
        
        $('#message-container').html(messageHtml);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $('#message-container').empty();
        }, 3000);
    }
});
</script>
@endpush

