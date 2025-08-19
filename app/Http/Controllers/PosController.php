<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pos.index', compact('products'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->get();
        return response()->json($products);
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1
            ];
        }

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart']);
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            Session::put('cart', $cart);
        }
        return response()->json(['message' => 'Cart updated']);
    }

    public function removeFromCart(Request $request)
    {
        if ($request->id) {
            $cart = Session::get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                Session::put('cart', $cart);
            }
        }
        return response()->json(['message' => 'Product removed from cart']);
    }

    public function clearCart()
    {
        Session::forget('cart');
        return response()->json(['message' => 'Cart cleared']);
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart');
        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Create order logic here
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'total_amount' => collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            }),
            'status' => 'completed'
        ]);

        // Clear the cart after successful checkout
        Session::forget('cart');

        return response()->json([
            'message' => 'Order completed successfully',
            'order_id' => $order->id
        ]);
    }
}
