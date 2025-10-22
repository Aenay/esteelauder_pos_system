@extends('layouts.app')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-md">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Create Supplier Order</h1>
                <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                </a>
            </div>
        </div>
    </header>
    
    <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('admin.supplier-orders.store') }}" method="POST" id="supplier-order-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Supplier Selection -->
                    <div class="mb-6">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Supplier <span class="text-red-500">*</span>
                        </label>
                        <select name="supplier_id" id="supplier_id" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('supplier_id') border-red-500 @enderror" 
                                required>
                            <option value="">Choose a supplier...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->Supplier_ID }}" {{ old('supplier_id', request('supplier')) == $supplier->Supplier_ID ? 'selected' : '' }}>
                                    {{ $supplier->Supplier_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Delivery Date -->
                    <div class="mb-6">
                        <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Expected Delivery Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expected_delivery_date" id="expected_delivery_date" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('expected_delivery_date') border-red-500 @enderror"
                               value="{{ old('expected_delivery_date') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('expected_delivery_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('notes') border-red-500 @enderror"
                                  placeholder="Add any special instructions or notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Products Section -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                            <button type="button" id="add-product-btn" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Product
                            </button>
                        </div>

                        <div id="products-container">
                            <!-- Products will be added here dynamically -->
                        </div>

                        @error('products')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Summary</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Amount:</span>
                            <span id="total-amount" class="text-xl font-bold text-green-600">$0.00</span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Create Supplier Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Product Selection Modal -->
<div id="product-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Select Product</h3>
                    <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <input type="text" id="product-search" placeholder="Search products..." 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                
                <div id="product-list" class="max-h-60 overflow-y-auto">
                    <!-- Create New Product Option -->
                    <div class="create-new-product border border-green-200 rounded-lg p-3 mb-2 cursor-pointer hover:bg-green-50 bg-green-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-medium text-green-700"><i class="fas fa-plus-circle mr-2"></i>Create New Product</h4>
                                <p class="text-sm text-green-600">Add a product not in the system</p>
                            </div>
                        </div>
                    </div>
                    
                    @foreach($products as $product)
                        <div class="product-option border border-gray-200 rounded-lg p-3 mb-2 cursor-pointer hover:bg-gray-50" 
                             data-product-id="{{ $product->Product_ID }}" 
                             data-product-name="{{ $product->Product_Name }}" 
                             data-product-price="{{ $product->Price }}">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $product->Product_Name }}</h4>
                                    <p class="text-sm text-gray-600">SKU: {{ $product->SKU }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-green-600">${{ number_format($product->Price, 2) }}</p>
                                    <p class="text-sm text-gray-500">Stock: {{ $product->Quantity_on_Hand }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Product Modal -->
<div id="new-product-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Create New Product</h3>
                    <button type="button" id="close-new-product-modal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="new-product-name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" id="new-product-name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    
                    <div>
                        <label for="new-product-sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" id="new-product-sku" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    </div>
                    
                    <div>
                        <label for="new-product-price" class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                        <input type="number" id="new-product-price" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" step="0.01" min="0" required>
                    </div>

                    <div>
                        <label for="new-product-image" class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                        <input type="file" id="new-product-image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <div id="new-product-image-preview" class="mt-2">
                            <img id="new-product-image-preview-img" src="" class="hidden w-24 h-24 object-cover rounded border" alt="Preview">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancel-new-product" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="button" id="add-new-product" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                            Add Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let productCounter = 0;
    let tempProductId = -1; // For new products that don't exist in the database yet
    
    // Add product button click
    $('#add-product-btn').on('click', function() {
        $('#product-modal').removeClass('hidden');
    });
    
    // Close modal
    $('#close-modal').on('click', function() {
        $('#product-modal').addClass('hidden');
    });
    
    // Product search
    $('#product-search').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        
        // Always show the "Create New Product" option
        $('.create-new-product').show();
        
        // Filter existing products
        $('.product-option').each(function() {
            const productName = $(this).data('product-name').toLowerCase();
            if (productName.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Create New Product click
    $('.create-new-product').on('click', function() {
        $('#product-modal').addClass('hidden');
        $('#new-product-modal').removeClass('hidden');
    });

    // Image preview for new product
    $('#new-product-image').on('change', function(event) {
        const file = event.target.files[0];
        const previewImg = $('#new-product-image-preview-img');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.attr('src', e.target.result).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.attr('src', '').addClass('hidden');
        }
    });
    
    // Close new product modal
    $('#close-new-product-modal, #cancel-new-product').on('click', function() {
        $('#new-product-modal').addClass('hidden');
    });
    
    // Add new product
    $('#add-new-product').on('click', function() {
        const productName = $('#new-product-name').val().trim();
        const productSku = $('#new-product-sku').val().trim();
        const productPrice = parseFloat($('#new-product-price').val()) || 0;
        
        if (!productName || !productSku || productPrice <= 0) {
            alert('Please fill in all fields with valid values.');
            return;
        }
        
        // Create a temporary product ID for the form
        const newProductId = 'new_' + tempProductId;
        tempProductId--;
        
        // Add the new product to the order
        addNewProductToOrder(newProductId, productName, productSku, productPrice);
        
        // Clear form and close modal
        $('#new-product-name').val('');
        $('#new-product-sku').val('');
        $('#new-product-price').val('');
        $('#new-product-modal').addClass('hidden');
    });
    
    // Product selection
    $('.product-option').on('click', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = $(this).data('product-price');
        
        // Check if product is already added
        if ($(`input[name="products[${productId}][product_id]"]`).length > 0) {
            alert('This product is already added to the order.');
            return;
        }
        
        addProductToOrder(productId, productName, productPrice);
        $('#product-modal').addClass('hidden');
    });
    
    function addProductToOrder(productId, productName, productPrice) {
        const productHtml = `
            <div class="product-item border border-gray-200 rounded-lg p-4 mb-4" data-product-id="${productId}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${productName}</h4>
                        <p class="text-sm text-gray-600">Unit Cost: $${parseFloat(productPrice).toFixed(2)}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="products[${productId}][quantity]" 
                                   class="w-20 border border-gray-300 rounded px-2 py-1 text-center quantity-input" 
                                   min="1" value="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                            <input type="number" name="products[${productId}][unit_cost]" 
                                   class="w-24 border border-gray-300 rounded px-2 py-1 text-center unit-cost-input" 
                                   step="0.01" min="0" value="${productPrice}" required>
                        </div>
                        <div class="text-right">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                            <p class="font-semibold text-green-600 item-total">$${parseFloat(productPrice).toFixed(2)}</p>
                        </div>
                        <button type="button" class="remove-product text-red-600 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="products[${productId}][product_id]" value="${productId}">
            </div>
        `;
        
        $('#products-container').append(productHtml);
        updateTotal();
    }
    
    function addNewProductToOrder(newProductId, productName, productSku, productPrice) {
        const productHtml = `
            <div class="product-item border border-green-200 bg-green-50 rounded-lg p-4 mb-4" data-product-id="${newProductId}">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${productName} <span class="text-xs bg-green-600 text-white px-2 py-1 rounded-full">New</span></h4>
                        <p class="text-sm text-gray-600">SKU: ${productSku}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="products[${newProductId}][quantity]" 
                                   class="w-20 border border-gray-300 rounded px-2 py-1 text-center quantity-input" 
                                   min="1" value="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                            <input type="number" name="products[${newProductId}][unit_cost]" 
                                   class="w-24 border border-gray-300 rounded px-2 py-1 text-center unit-cost-input" 
                                   step="0.01" min="0" value="${productPrice}" required>
                        </div>
                        <div class="text-right">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                            <p class="font-semibold text-green-600 item-total">$${parseFloat(productPrice).toFixed(2)}</p>
                        </div>
                        <button type="button" class="remove-product text-red-600 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="products[${newProductId}][product_id]" value="${newProductId}">
                <input type="hidden" name="products[${newProductId}][is_new]" value="1">
                <input type="hidden" name="products[${newProductId}][name]" value="${productName}">
                <input type="hidden" name="products[${newProductId}][sku]" value="${productSku}">
            </div>
        `;
        
        const container = $('#products-container');
        container.append(productHtml);

        // Move the selected file input from modal into this product item so it submits
        const fileInput = $('#new-product-image');
        if (fileInput.length && fileInput[0].files && fileInput[0].files.length > 0) {
            const itemEl = container.find(`.product-item[data-product-id='${newProductId}']`);
            // Rename and hide the input, then append into product item
            fileInput.attr('name', `products[${newProductId}][image]`).addClass('hidden');
            itemEl.append(fileInput);
            // Recreate a fresh empty input back in modal for next product
            const replacement = $('<input type="file" id="new-product-image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">');
            $('#new-product-image-preview-img').attr('src', '').addClass('hidden');
            $('#new-product-image-preview').before(replacement);
            // Rebind preview handler
            replacement.on('change', function(event) {
                const file = event.target.files[0];
                const previewImg = $('#new-product-image-preview-img');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.attr('src', e.target.result).removeClass('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImg.attr('src', '').addClass('hidden');
                }
            });
        }

        updateTotal();
    }
    
    // Remove product
    $(document).on('click', '.remove-product', function() {
        $(this).closest('.product-item').remove();
        updateTotal();
    });
    
    // Update quantities and costs
    $(document).on('input', '.quantity-input, .unit-cost-input', function() {
        const item = $(this).closest('.product-item');
        const quantity = parseFloat(item.find('.quantity-input').val()) || 0;
        const unitCost = parseFloat(item.find('.unit-cost-input').val()) || 0;
        const total = quantity * unitCost;
        
        item.find('.item-total').text('$' + total.toFixed(2));
        updateTotal();
    });
    
    function updateTotal() {
        let total = 0;
        $('.item-total').each(function() {
            const itemTotal = parseFloat($(this).text().replace('$', '')) || 0;
            total += itemTotal;
        });
        
        $('#total-amount').text('$' + total.toFixed(2));
    }
    
    // Form validation
    $('#supplier-order-form').on('submit', function(e) {
        if ($('.product-item').length === 0) {
            e.preventDefault();
            alert('Please add at least one product to the order.');
            return false;
        }
    });
});
</script>
@endpush

