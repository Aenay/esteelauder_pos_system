@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Create New Delivery</h2>
                <p class="text-gray-600 mt-2">Create a delivery from a supplier or fulfill a customer order</p>
            </div>
            <a href="{{ route('admin.deliveries.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Deliveries
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form action="{{ route('admin.deliveries.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="delivery_type" class="block text-sm font-medium text-gray-700 mb-2">Delivery Type *</label>
                        <select name="delivery_type" id="delivery_type" required 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="supplier" {{ old('delivery_type', 'supplier') === 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="customer" {{ old('delivery_type') === 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>
                    <div id="supplier-field-wrapper">
                        <label for="Supplier_ID" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                        <select name="Supplier_ID" id="Supplier_ID" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->Supplier_ID }}" {{ old('Supplier_ID') == $supplier->Supplier_ID ? 'selected' : '' }}>
                                    {{ $supplier->Supplier_Name }}
                                </option>
                            @endforeach
                        </select>
                        @error('Supplier_ID')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="Expected_Delivery_Date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                        <input type="date" name="Expected_Delivery_Date" id="Expected_Delivery_Date" required
                               value="{{ old('Expected_Delivery_Date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Expected_Delivery_Date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Customer Order Selection -->
                <div id="customer-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" style="display:none;">
                    <div>
                        <label for="Order_ID" class="block text-sm font-medium text-gray-700 mb-2">Customer Order *</label>
                        <select name="Order_ID" id="Order_ID" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Order</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->Order_ID }}" {{ old('Order_ID') == $order->Order_ID ? 'selected' : '' }}>
                                    #{{ $order->Order_ID }} — {{ optional($order->customer)->Customer_Name }} — {{ \Carbon\Carbon::parse($order->Order_Date)->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('Order_ID')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-2">When a customer order is selected, products are auto-populated from the order.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="Tracking_Number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                        <input type="text" name="Tracking_Number" id="Tracking_Number" 
                               value="{{ old('Tracking_Number') }}"
                               placeholder="Enter tracking number"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Tracking_Number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="Carrier" class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                        <input type="text" name="Carrier" id="Carrier" 
                               value="{{ old('Carrier') }}"
                               placeholder="e.g., FedEx, UPS, DHL"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('Carrier')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="Notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="Notes" id="Notes" rows="3" 
                              placeholder="Additional notes about the delivery"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('Notes') }}</textarea>
                    @error('Notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Products Section -->
                <div class="mb-6" id="products-section">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Products</h3>
                    <div id="products-container">
                        <div class="product-row bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                                    <select name="products[0][Product_ID]" required 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->Product_ID }}">
                                                {{ $product->Product_Name }} ({{ $product->SKU }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                    <input type="number" name="products[0][Quantity_Ordered]" required min="1"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost *</label>
                                    <input type="number" name="products[0][Unit_Cost]" required min="0" step="0.01"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <input type="text" name="products[0][Notes]" 
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="add-product" 
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Another Product
                    </button>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.deliveries.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                        Create Delivery
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let productCount = 1;

function toggleDeliveryMode(type) {
    const supplierWrapper = document.getElementById('supplier-field-wrapper');
    const supplierSelect = document.getElementById('Supplier_ID');
    const customerFields = document.getElementById('customer-fields');
    const orderSelect = document.getElementById('Order_ID');
    const productsSection = document.getElementById('products-section');
    const addProductBtn = document.getElementById('add-product');

    const setProductInputsRequired = (required) => {
        document.querySelectorAll('#products-container .product-row').forEach(row => {
            row.querySelectorAll('select[name*="[Product_ID]"], input[name*="[Quantity_Ordered]"], input[name*="[Unit_Cost]"]').forEach(el => {
                if (required) {
                    el.setAttribute('required', 'required');
                } else {
                    el.removeAttribute('required');
                }
            });
        });
    };

    if (type === 'customer') {
        // Show customer order selector
        customerFields.style.display = '';
        orderSelect?.setAttribute('required', 'required');

        // Hide supplier and products manual inputs
        supplierWrapper.style.display = 'none';
        supplierSelect?.removeAttribute('required');

        productsSection.style.display = 'none';
        addProductBtn.style.display = 'none';
        setProductInputsRequired(false);
    } else {
        // Supplier delivery
        customerFields.style.display = 'none';
        orderSelect?.removeAttribute('required');

        supplierWrapper.style.display = '';
        supplierSelect?.setAttribute('required', 'required');

        productsSection.style.display = '';
        addProductBtn.style.display = '';
        setProductInputsRequired(true);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const deliveryTypeSelect = document.getElementById('delivery_type');
    if (deliveryTypeSelect) {
        toggleDeliveryMode(deliveryTypeSelect.value);
        deliveryTypeSelect.addEventListener('change', function() {
            toggleDeliveryMode(this.value);
        });
    }
});

document.getElementById('add-product').addEventListener('click', function() {
    const container = document.getElementById('products-container');
    const newRow = document.createElement('div');
    newRow.className = 'product-row bg-gray-50 p-4 rounded-lg mb-4';
    
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                <select name="products[${productCount}][Product_ID]" required 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->Product_ID }}">
                            {{ $product->Product_Name }} ({{ $product->SKU }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                <input type="number" name="products[${productCount}][Quantity_Ordered]" required min="1"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost *</label>
                <input type="number" name="products[${productCount}][Unit_Cost]" required min="0" step="0.01"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <input type="text" name="products[${productCount}][Notes]" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="button" class="ml-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg remove-product">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    productCount++;
    
    // Add remove functionality to new row
    newRow.querySelector('.remove-product').addEventListener('click', function() {
        newRow.remove();
    });
});

// Add remove functionality to initial row
document.querySelector('.remove-product')?.addEventListener('click', function() {
    if (document.querySelectorAll('.product-row').length > 1) {
        this.closest('.product-row').remove();
    }
});
</script>
@endsection

