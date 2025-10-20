<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $products = Product::with('supplier')
            ->when($query, function ($q, $query) {
                return $q->where('Product_Name', 'like', "%{$query}%")
                         ->orWhere('SKU', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        return view('admin.products.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,SKU',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,Supplier_ID',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $dataToCreate = [
            'Product_Name' => $validated['name'],
            'SKU' => $validated['sku'],
            'Price' => $validated['price'],
            'Quantity_on_Hand' => $validated['stock'],
            'description' => $validated['description'],
            'Supplier_ID' => $validated['supplier_id'],
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $dataToCreate['image'] = $imagePath;
        }

        Product::create($dataToCreate);
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('Supplier_Name')->get();
        return view('admin.products.edit', compact('product', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,SKU,' . $product->Product_ID . ',Product_ID',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,Supplier_ID',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $dataToUpdate = [
            'Product_Name' => $validated['name'],
            'SKU' => $validated['sku'],
            'Price' => $validated['price'],
            'Quantity_on_Hand' => $validated['stock'],
            'description' => $validated['description'],
            'Supplier_ID' => $validated['supplier_id'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $dataToUpdate['image'] = $imagePath;
        }

        $product->update($dataToUpdate);
        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Delete the image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}