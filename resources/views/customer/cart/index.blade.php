@extends('layouts.customer')

@section('title', 'Shopping Cart - Esteé Lauder')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Shopping Cart</h1>
                    <p class="text-gray-600">Review your items before checkout</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.shop.index') }}" class="text-pink-600 hover:text-pink-700">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Cart Items</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($cart as $productId => $item)
                        <div class="p-6 cart-item" data-product-id="{{ $productId }}">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-500 text-xs">No Image</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-500">Stock: {{ $item['stock'] }}</p>
                                    <p class="text-lg font-semibold text-pink-600">${{ number_format($item['price'], 2) }}</p>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="quantity-btn decrease-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    
                                    <input type="number" 
                                           class="quantity-input w-16 text-center border border-gray-300 rounded py-1" 
                                           value="{{ $item['quantity'] }}" 
                                           min="0" 
                                           max="{{ $item['stock'] }}">
                                    
                                    <button type="button" class="quantity-btn increase-btn bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                                
                                <!-- Item Total -->
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900 item-total">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </p>
                                </div>
                                
                                <!-- Remove Button -->
                                <div class="flex-shrink-0">
                                    <button type="button" class="remove-item-btn text-red-600 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md sticky top-4">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>
                    
                    <div class="p-6">
                        <!-- Customer Info -->
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Customer</h3>
                            <p class="text-sm text-gray-600">{{ $customer->Customer_Name }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->loyalty_tier }} Member</p>
                            @if($customer->loyalty_points > 0)
                                <p class="text-xs text-pink-600">{{ $customer->loyalty_points }} loyalty points</p>
                            @endif
                        </div>
                        
                        <!-- Order Totals -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="subtotal">${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">$0.00</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total</span>
                                    <span class="text-pink-600" id="total">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Method
                            </label>
                            <select id="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                <option value="card">Credit/Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="apple">Apple Pay</option>
                            </select>
                        </div>
                        
                        <!-- Checkout Button -->
                        <button id="checkout-btn" class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 transition-colors font-medium">
                            <i class="fas fa-credit-card mr-2"></i>
                            Proceed to Checkout
                        </button>
                        
                        <!-- Continue Shopping -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('customer.shop.index') }}" class="text-pink-600 hover:text-pink-700 text-sm">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="text-center py-12">
            <div class="max-w-md mx-auto">
                <i class="fas fa-shopping-cart text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('customer.shop.index') }}" 
                   class="inline-flex items-center bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quantity controls
    $('.quantity-btn').on('click', function() {
        const item = $(this).closest('.cart-item');
        const productId = item.data('product-id');
        const input = item.find('.quantity-input');
        const isIncrease = $(this).hasClass('increase-btn');
        const currentValue = parseInt(input.val());
        const maxValue = parseInt(input.attr('max'));
        
        let newValue = currentValue;
        if (isIncrease && currentValue < maxValue) {
            newValue = currentValue + 1;
        } else if (!isIncrease && currentValue > 0) {
            newValue = currentValue - 1;
        }
        
        if (newValue !== currentValue) {
            updateCartItem(productId, newValue);
        }
    });
    
    // Quantity input change
    $('.quantity-input').on('change', function() {
        const item = $(this).closest('.cart-item');
        const productId = item.data('product-id');
        const newValue = parseInt($(this).val());
        const maxValue = parseInt($(this).attr('max'));
        
        if (newValue < 0) {
            $(this).val(0);
            updateCartItem(productId, 0);
        } else if (newValue > maxValue) {
            $(this).val(maxValue);
            showMessage('error', 'Quantity cannot exceed available stock');
        } else {
            updateCartItem(productId, newValue);
        }
    });
    
    // Remove item
    $('.remove-item-btn').on('click', function() {
        const item = $(this).closest('.cart-item');
        const productId = item.data('product-id');
        
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            removeCartItem(productId);
        }
    });
    
    // Checkout
    $('#checkout-btn').on('click', function() {
        const paymentMethod = $('#payment_method').val();
        const button = $(this);
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
        
        $.ajax({
            url: '{{ route("customer.cart.checkout") }}',
            method: 'POST',
            data: {
                payment_method: paymentMethod,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    setTimeout(function() {
                        window.location.href = '{{ route("customer.orders.index") }}';
                    }, 2000);
                } else {
                    showMessage('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showMessage('error', response.message || 'An error occurred during checkout');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-credit-card mr-2"></i>Proceed to Checkout');
            }
        });
    });
    
    function updateCartItem(productId, quantity) {
        $.ajax({
            url: '{{ route("customer.cart.update") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (quantity === 0) {
                        // Remove item from UI
                        $(`.cart-item[data-product-id="${productId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            updateTotals();
                            checkEmptyCart();
                        });
                    } else {
                        // Update item in UI
                        updateItemInUI(productId, quantity);
                        updateTotals();
                    }
                } else {
                    showMessage('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showMessage('error', response.message || 'An error occurred');
            }
        });
    }
    
    function removeCartItem(productId) {
        $.ajax({
            url: '{{ route("customer.cart.remove") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $(`.cart-item[data-product-id="${productId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateTotals();
                        checkEmptyCart();
                    });
                } else {
                    showMessage('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showMessage('error', response.message || 'An error occurred');
            }
        });
    }
    
    function updateItemInUI(productId, quantity) {
        const item = $(`.cart-item[data-product-id="${productId}"]`);
        const price = parseFloat(item.find('.text-pink-600').text().replace('$', ''));
        const itemTotal = price * quantity;
        
        item.find('.quantity-input').val(quantity);
        item.find('.item-total').text('$' + itemTotal.toFixed(2));
    }
    
    function updateTotals() {
        let subtotal = 0;
        $('.item-total').each(function() {
            subtotal += parseFloat($(this).text().replace('$', ''));
        });
        
        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#total').text('$' + subtotal.toFixed(2));
    }
    
    function checkEmptyCart() {
        if ($('.cart-item').length === 0) {
            location.reload();
        }
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
        
        setTimeout(function() {
            $('#message-container').empty();
        }, 3000);
    }
});
</script>
@endpush

