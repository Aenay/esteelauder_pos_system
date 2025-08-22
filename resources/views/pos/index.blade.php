@extends('layouts.app')

@section('content')
    <div class="flex h-full pos-container">
        <!-- Product Selection -->
        <main class="w-2/3 p-6 flex flex-col bg-gray-100 pos-main">
            <div class="relative mb-6">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                <input type="text" id="product-search" placeholder="Search for products..." class="w-full pl-10 pr-4 py-3 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>
            <div id="product-list" class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col items-center text-center cursor-pointer hover:shadow-xl hover:scale-105 transition-transform" 
                            data-id="{{ $product->Product_ID }}"
                            data-name="{{ $product->Product_Name }}"
                            data-price="{{ $product->Price }}"
                            data-stock="{{ $product->Quantity_on_Hand }}">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->Product_Name }}" class="w-24 h-24 object-cover mb-3 rounded-md">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded-md flex items-center justify-center mb-3">
                                    <span class="text-xs text-gray-500">No Image</span>
                                </div>
                            @endif
                            <h3 class="font-semibold text-sm">{{ $product->Product_Name }}</h3>
                            <p class="text-gray-600 font-bold mt-1">${{ number_format($product->Price, 2) }}</p>
                            <p class="text-sm {{ $product->Quantity_on_Hand > 0 ? 'text-green-600' : 'text-red-600' }}">
                                Stock: {{ $product->Quantity_on_Hand }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
        <!-- Order Details -->
        <aside class="w-1/3 bg-white shadow-lg flex flex-col pos-sidebar">
            <div class="p-6 flex-shrink-0">
                <h2 class="text-2xl font-bold mb-4 border-b pb-4">Current Order</h2>
                
                <!-- Customer Type Section -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="customer_type" value="external" class="form-radio text-pink-600" checked>
                            <span class="ml-2 text-gray-700">External Customer</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="customer_type" value="internal" class="form-radio text-pink-600">
                            <span class="ml-2 text-gray-700">Internal Customer (Member)</span>
                        </label>
                    </div>
                </div>

                <!-- Customer Fields Section -->
                <div class="mb-4">
                    <div id="external-customer-fields">
                        <label for="external_customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name (Optional)</label>
                        <input type="text" id="external_customer_name" class="w-full p-2 border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="e.g., Walk-in Customer">
                    </div>

                    <div id="internal-customer-fields" class="hidden">
                        <label for="internal_customer_id" class="block text-sm font-medium text-gray-700 mb-1">Selected Member</label>
                        <div class="flex items-center">
                            <input type="text" id="internal_customer_display_name" class="w-full p-2 border rounded-lg bg-gray-100 cursor-not-allowed" readonly placeholder="No member selected">
                            <input type="hidden" id="internal_customer_id">
                            <button type="button" id="select-member-btn" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Select Member</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Items Section - Scrollable area -->
            <div class="cart-section px-6">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Cart Items</h3>
                <div id="cart-items" class="cart-items-container custom-scrollbar">
                    <div class="text-center text-gray-500 pt-10"><i class="fas fa-shopping-cart fa-2x mb-2"></i><p>Your cart is empty</p></div>
                </div>
            </div>

            <!-- Bottom Section - Fixed at bottom -->
            <div class="bottom-section px-6 pb-6 space-y-4">
                <!-- Promotion Section -->
                <div>
                    <label for="promotion" class="block text-sm font-medium text-gray-700 mb-1">Promotion</label>
                    <select id="promotion" class="w-full p-2 border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="0" data-type="none" data-value="0">No Promotion</option>
                        <option value="1" data-type="percentage" data-value="10">10% Off Total Order</option>
                    </select>
                </div>

                <!-- Totals Section -->
                <div class="border-t pt-4 space-y-2 text-md">
                    <div class="flex justify-between"><span>Subtotal</span><span id="subtotal">$0.00</span></div>
                    <div class="flex justify-between text-pink-600"><span>Discount</span><span id="discount">-$0.00</span></div>
                    <div class="flex justify-between font-bold text-xl border-t pt-2 mt-2"><span>Total</span><span id="total">$0.00</span></div>
                </div>
                
                <!-- Payment Method Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="card" class="sr-only" checked>
                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center hover:border-pink-500 transition-colors payment-method-card">
                                <i class="fas fa-credit-card text-2xl text-gray-600 mb-2"></i>
                                <div class="text-sm font-medium">Card</div>
                            </div>
                        </label>
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="paypal" class="sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center hover:border-pink-500 transition-colors payment-method-card">
                                <i class="fab fa-paypal text-2xl text-blue-600 mb-2"></i>
                                <div class="text-sm font-medium">PayPal</div>
                            </div>
                        </label>
                        <label class="payment-method-option cursor-pointer">
                            <input type="radio" name="payment_method" value="apple" class="sr-only">
                            <div class="border-2 border-gray-200 rounded-lg p-3 text-center hover:border-pink-500 transition-colors payment-method-card">
                                <i class="fab fa-apple-pay text-2xl text-black mb-2"></i>
                                <div class="text-sm font-medium">Apple Pay</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Complete Sale Button -->
                <button id="complete-sale" class="w-full bg-pink-600 text-white font-bold py-3 rounded-lg hover:bg-pink-700 transition-colors disabled:bg-gray-400" disabled>Complete Sale</button>
            </div>
        </aside>
    </div>

    <!-- Customer Selection Modal -->
    <div id="customer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold">Select Customer</h3>
                    <button type="button" id="close-customer-modal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <input type="text" id="customer-search" placeholder="Search customers by name, email, or phone..." class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div id="customer-list" class="max-h-96 overflow-y-auto">
                        <!-- Customer list will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .payment-method-option input[type="radio"]:checked + .payment-method-card {
            border-color: #ec4899;
            background-color: #fdf2f8;
        }
        
        .payment-method-card {
            transition: all 0.2s ease-in-out;
        }
        
        .payment-method-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Ensure proper layout at all zoom levels */
        .pos-container {
            min-height: 100vh;
            height: 100vh;
            overflow: hidden;
        }

        .pos-main {
            height: 100vh;
            overflow-y: auto;
        }

        .pos-sidebar {
            height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            overflow: hidden;
        }

        .cart-section {
            min-height: 0;
            overflow: hidden;
        }

        .cart-items-container {
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .bottom-section {
            flex-shrink: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding-top: 1rem;
        }

        /* Ensure cart items don't overlap */
        .cart-item {
            margin-bottom: 0.5rem;
            padding: 0.75rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .cart-item:last-child {
            margin-bottom: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .pos-sidebar {
                height: auto;
                min-height: 100vh;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            let cart = {};

            function renderCart(cartData) {
                cart = cartData;
                const cartItemsContainer = $('#cart-items');
                cartItemsContainer.empty();
                let subtotal = 0;
                if (Object.keys(cart).length === 0) {
                    cartItemsContainer.html(`<div class="text-center text-gray-500 pt-10"><i class="fas fa-shopping-cart fa-2x mb-2"></i><p>Your cart is empty</p></div>`);
                } else {
                    for (const id in cart) {
                        const item = cart[id];
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;
                        const itemHtml = `
                            <div class="cart-item flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0" data-id="${id}">
                                <div class="flex-1 min-w-0 mr-3">
                                    <p class="font-semibold text-sm text-gray-900 truncate">${item.name}</p>
                                    <p class="text-xs text-gray-500">SKU: ${item.sku} | Stock: ${item.stock}</p>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <button class="update-quantity-btn text-pink-500 hover:text-pink-700 w-6 h-6 rounded-full border border-pink-300 hover:bg-pink-50 flex items-center justify-center" data-id="${id}" data-action="decrement">-</button>
                                    <span class="text-sm font-medium min-w-[2rem] text-center">${item.quantity}</span>
                                    <button class="update-quantity-btn text-pink-500 hover:text-pink-700 w-6 h-6 rounded-full border border-pink-300 hover:bg-pink-50 flex items-center justify-center" data-id="${id}" data-action="increment">+</button>
                                    <button class="remove-item-btn text-red-500 hover:text-red-700 text-sm ml-2 w-6 h-6 rounded-full border border-red-300 hover:bg-red-50 flex items-center justify-center" data-id="${id}"><i class="fas fa-trash text-xs"></i></button>
                                </div>
                                <div class="text-right font-semibold text-sm min-w-[4rem]">$${itemTotal.toFixed(2)}</div>
                            </div>`;
                        cartItemsContainer.append(itemHtml);
                    }
                }
                const selectedPromo = $('#promotion option:selected');
                const promoType = selectedPromo.data('type');
                const promoValue = parseFloat(selectedPromo.data('value'));
                let discount = 0;
                if (promoType === 'percentage') discount = subtotal * (promoValue / 100);
                const total = subtotal - discount;
                $('#subtotal').text(`$${subtotal.toFixed(2)}`);
                $('#discount').text(`-$${discount.toFixed(2)}`);
                $('#total').text(`$${total.toFixed(2)}`);
                $('#complete-sale').prop('disabled', total <= 0);
            }

            function getCart() {
                $.ajax({
                    url: "{{ route('ajax.cart.get') }}",
                    method: 'GET',
                    success: function(response) {
                        renderCart(response);
                    }
                });
            }

            function addToCart(productId) {
                const productCard = $(`.product-card[data-id="${productId}"]`);
                const currentStock = parseInt(productCard.data('stock'));
                const currentQuantity = cart[productId] ? cart[productId].quantity : 0;
                
                if (currentStock <= 0) {
                    alert('This product is out of stock.');
                    return;
                }
                
                if (currentQuantity >= currentStock) {
                    alert('Cannot add more items. Maximum stock reached.');
                    return;
                }
                
                $.ajax({
                    url: "{{ route('ajax.cart.add') }}",
                    method: 'POST',
                    data: { 
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        renderCart(response.cart);
                    },
                    error: function(response) {
                        alert(response.responseJSON.error);
                    }
                });
            }

            function updateCart(productId, quantity) {
                if (quantity < 0) {
                    alert('Quantity cannot be negative.');
                    return;
                }
                
                const productCard = $(`.product-card[data-id="${productId}"]`);
                const currentStock = parseInt(productCard.data('stock'));
                
                if (quantity > currentStock) {
                    alert('Cannot update quantity. Requested amount exceeds available stock.');
                    return;
                }
                
                $.ajax({
                    url: "{{ route('ajax.cart.update') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        renderCart(response.cart);
                    },
                    error: function(response) {
                        alert(response.responseJSON.error);
                    }
                });
            }

            function removeFromCart(productId) {
                $.ajax({
                    url: "{{ route('ajax.cart.remove') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: productId
                    },
                    success: function(response) {
                        renderCart(response.cart);
                    }
                });
            }

            function attachProductCardHandler() {
                 $('#product-list').on('click', '.product-card', function() {
                    const id = $(this).data('id');
                    addToCart(id);
                });
            }

            // Customer selection modal functions
            function openCustomerModal() {
                $('#customer-modal').removeClass('hidden');
                loadCustomers();
            }

            function closeCustomerModal() {
                $('#customer-modal').addClass('hidden');
                $('#customer-search').val('');
                $('#customer-list').empty();
            }

            function loadCustomers(searchQuery = '') {
                $.ajax({
                    url: "{{ route('ajax.customers.search') }}",
                    method: 'GET',
                    data: { query: searchQuery },
                    success: function(customers) {
                        const customerList = $('#customer-list');
                        customerList.empty();
                        
                        if (customers.length === 0) {
                            customerList.html('<p class="text-gray-500 text-center py-4">No customers found</p>');
                            return;
                        }
                        
                        customers.forEach(function(customer) {
                            const customerHtml = `
                                <div class="customer-item p-3 border-b hover:bg-gray-50 cursor-pointer" data-id="${customer.Customer_ID}" data-name="${customer.Customer_Name}">
                                    <div class="font-semibold">${customer.Customer_Name}</div>
                                    <div class="text-sm text-gray-600">
                                        ${customer.Customer_Email ? customer.Customer_Email : 'No email'} | 
                                        ${customer.Customer_Phone ? customer.Customer_Phone : 'No phone'}
                                    </div>
                                </div>`;
                            customerList.append(customerHtml);
                        });
                    }
                });
            }

            function selectCustomer(customerId, customerName) {
                $('#internal_customer_id').val(customerId);
                $('#internal_customer_display_name').val(customerName);
                closeCustomerModal();
            }

            function updatePaymentMethodSelection() {
                $('.payment-method-card').removeClass('border-pink-500 bg-pink-50').addClass('border-gray-200');
                const selectedMethod = $('input[name="payment_method"]:checked').val();
                $(`.payment-method-option input[value="${selectedMethod}"]`).closest('label').find('.payment-method-card').addClass('border-pink-500 bg-pink-50');
            }

            // Payment method selection event handlers
            $('input[name="payment_method"]').on('change', function() {
                updatePaymentMethodSelection();
            });

            $('#cart-items').on('click', '.update-quantity-btn', function() {
                const id = $(this).data('id');
                const action = $(this).data('action');
                let quantity = cart[id].quantity;

                if (action === 'increment') {
                    quantity++;
                } else if (action === 'decrement') {
                    quantity--;
                }
                updateCart(id, quantity);
            });

            $('#cart-items').on('click', '.remove-item-btn', function() {
                const id = $(this).data('id');
                removeFromCart(id);
            });

            $('#product-search').on('keyup', function() {
                const query = $(this).val();
                $.ajax({
                    url: "{{ route('ajax.products.search') }}",
                    method: 'GET',
                    data: { query: query },
                    success: function(products) {
                        const productList = $('#product-list .grid');
                        productList.empty();
                        if (products.length > 0) {
                            products.forEach(function(product) {
                                const productHtml = `
                                    <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col items-center text-center cursor-pointer hover:shadow-xl hover:scale-105 transition-transform" data-id="${product.Product_ID}" data-name="${product.Product_Name}" data-price="${product.Price}" data-stock="${product.Quantity_on_Hand}">
                                        ${product.image ? `<img src="/storage/${product.image}" alt="${product.Product_Name}" class="w-24 h-24 object-cover mb-3 rounded-md">` : '<div class="w-24 h-24 bg-gray-200 rounded-md flex items-center justify-center mb-3"><span class="text-xs text-gray-500">No Image</span></div>'}
                                        <h3 class="font-semibold text-sm">${product.Product_Name}</h3>
                                        <p class="text-gray-600 font-bold mt-1">$${parseFloat(product.Price).toFixed(2)}</p>
                                        <p class="text-sm ${product.Quantity_on_Hand > 0 ? 'text-green-600' : 'text-red-600'}">Stock: ${product.Quantity_on_Hand}</p>
                                    </div>`;
                                productList.append(productHtml);
                            });
                        } else {
                            productList.html('<p class="text-gray-500 col-span-full">No products found.</p>');
                        }
                    }
                });
            });

            $('#complete-sale').on('click', function() {
                const promotionId = $('#promotion').val();
                const subtotal = parseFloat($('#subtotal').text().replace('$', ''));
                const discount = parseFloat($('#discount').text().replace('-$', ''));
                const total = parseFloat($('#total').text().replace('$', ''));
                const paymentMethod = $('input[name="payment_method"]:checked').val();

                const customerType = $('input[name="customer_type"]:checked').val();
                let customerId = null;
                let customerName = null;

                if (customerType === 'internal') {
                    customerId = $('#internal_customer_id').val();
                    if (!customerId) {
                        alert('Please select an internal customer.');
                        return;
                    }
                } else {
                    customerName = $('#external_customer_name').val();
                }

                $.ajax({
                    url: "{{ route('pos.complete_sale') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        promotion_id: promotionId,
                        subtotal: subtotal,
                        discount: discount,
                        total: total,
                        payment_method: paymentMethod,
                        items: cart,
                        customer_type: customerType,
                        customer_id: customerId,
                        customer_name: customerName
                    },
                    success: function(response) {
                        alert(`Thank you for your purchase! Order #${response.order_id} completed for ${response.customer_name} via ${response.payment_method}`);
                        getCart();
                        // Reset customer fields
                        $('#external_customer_name').val('');
                        $('#internal_customer_id').val('');
                        $('#internal_customer_display_name').val('No member selected');
                        $('input[name="customer_type"][value="external"]').prop('checked', true);
                        $('#external-customer-fields').show();
                        $('#internal-customer-fields').hide();
                        // Reset payment method to card
                        $('input[name="payment_method"][value="card"]').prop('checked', true);
                        updatePaymentMethodSelection();
                    },
                    error: function(response) {
                        alert(response.responseJSON.message);
                    }
                });
            });

            $('input[name="customer_type"]').on('change', function() {
                const selectedType = $(this).val();
                if (selectedType === 'external') {
                    $('#external-customer-fields').show();
                    $('#internal-customer-fields').hide();
                    $('#internal_customer_id').val('');
                    $('#internal_customer_display_name').val('No member selected');
                } else {
                    $('#external-customer-fields').hide();
                    $('#external_customer_name').val('');
                    $('#internal-customer-fields').show();
                }
            });

            // Customer modal event handlers
            $('#select-member-btn').on('click', function() {
                openCustomerModal();
            });

            $('#close-customer-modal').on('click', function() {
                closeCustomerModal();
            });

            $('#customer-modal').on('click', function(e) {
                if (e.target === this) {
                    closeCustomerModal();
                }
            });

            $('#customer-search').on('keyup', function() {
                const query = $(this).val();
                loadCustomers(query);
            });

            $('#customer-list').on('click', '.customer-item', function() {
                const customerId = $(this).data('id');
                const customerName = $(this).data('name');
                selectCustomer(customerId, customerName);
            });

            $('#promotion').on('change', function() { renderCart(cart); });
            attachProductCardHandler();
            getCart();
            updatePaymentMethodSelection();
        });
    </script>
@endsection
