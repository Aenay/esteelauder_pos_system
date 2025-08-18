@extends('layouts.app')

@section('content')
    <div class="flex h-full">
        <!-- Product Selection -->
        <main class="w-2/3 p-6 flex flex-col bg-gray-100">
            <div class="relative mb-6">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                <input type="text" placeholder="Search for products..." class="w-full pl-10 pr-4 py-3 border rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-pink-500">
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col items-center text-center cursor-pointer hover:shadow-xl hover:scale-105 transition-transform" data-id="{{ $product['id'] }}" data-name="{{ $product['name'] }}" data-price="{{ $product['price'] }}">
                            <img src="{{ $product['image'] }}" class="w-24 h-24 object-cover mb-3 rounded-md">
                            <h3 class="font-semibold text-sm">{{ $product['name'] }}</h3>
                            <p class="text-gray-600 font-bold mt-1">${{ number_format($product['price'], 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
        <!-- Order Details -->
        <aside class="w-1/3 bg-white p-6 shadow-lg flex flex-col">
            <h2 class="text-2xl font-bold mb-4 border-b pb-4">Current Order</h2>
            <div id="cart-items" class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                <div class="text-center text-gray-500 pt-10"><i class="fas fa-shopping-cart fa-2x mb-2"></i><p>Your cart is empty</p></div>
            </div>
            <div class="border-t pt-4 mt-4">
                <div class="mb-4">
                    <label for="promotion" class="block text-sm font-medium text-gray-700 mb-1">Promotion</label>
                    <select id="promotion" class="w-full p-2 border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="0" data-type="none" data-value="0">No Promotion</option>
                        <option value="1" data-type="percentage" data-value="10">10% Off Total Order</option>
                    </select>
                </div>
                <div class="space-y-2 text-md">
                    <div class="flex justify-between"><span>Subtotal</span><span id="subtotal">$0.00</span></div>
                    <div class="flex justify-between text-pink-600"><span>Discount</span><span id="discount">-$0.00</span></div>
                    <div class="flex justify-between font-bold text-xl border-t pt-2 mt-2"><span>Total</span><span id="total">$0.00</span></div>
                </div>
                <button id="complete-sale" class="w-full bg-pink-600 text-white font-bold py-3 rounded-lg mt-6 hover:bg-pink-700 transition-colors disabled:bg-gray-400" disabled>Complete Sale</button>
            </div>
        </aside>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const cart = {};
            function renderCart() {
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
                        const itemHtml = `<div class="cart-item flex items-center justify-between py-3 border-b" data-id="${id}"><div class="w-2/3"><p class="font-semibold text-sm">${item.name}</p></div><div class="flex items-center space-x-2"><span>${item.quantity}</span></div><div class="w-1/6 text-right font-semibold">${itemTotal.toFixed(2)}</div></div>`;
                        cartItemsContainer.append(itemHtml);
                    }
                }
                const selectedPromo = $('#promotion option:selected');
                const promoType = selectedPromo.data('type');
                const promoValue = parseFloat(selectedPromo.data('value'));
                let discount = 0;
                if (promoType === 'percentage') discount = subtotal * (promoValue / 100);
                const total = subtotal - discount;
                $('#subtotal').text(`${subtotal.toFixed(2)}`);
                $('#discount').text(`-${discount.toFixed(2)}`);
                $('#total').text(`${total.toFixed(2)}`);
                $('#complete-sale').prop('disabled', total <= 0);
            }
            $('.product-card').on('click', function() {
                const id = $(this).data('id'), name = $(this).data('name'), price = parseFloat($(this).data('price'));
                if (cart[id]) { cart[id].quantity++; } else { cart[id] = { name, price, quantity: 1 }; }
                renderCart();
            });
            $('#promotion').on('change', renderCart);
            renderCart();
        });
    </script>
@endsection
