<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomerCartController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Display the customer shop page
     */
    public function index()
    {
        $products = Product::where('Quantity_on_Hand', '>', 0)->get();
        $customer = Auth::guard('customer')->user();
        
        return view('customer.shop.index', compact('products', 'customer'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,Product_ID',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->Quantity_on_Hand < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available. Available: ' . $product->Quantity_on_Hand
            ], 400);
        }

        $cart = Session::get('customer_cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            if ($newQuantity > $product->Quantity_on_Hand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $product->Quantity_on_Hand
                ], 400);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->Product_ID,
                'name' => $product->Product_Name,
                'price' => $product->Price,
                'quantity' => $request->quantity,
                'image' => $product->image,
                'stock' => $product->Quantity_on_Hand
            ];
        }

        Session::put('customer_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,Product_ID',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('customer_cart', []);
        $productId = $request->product_id;

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::findOrFail($productId);
            if ($request->quantity > $product->Quantity_on_Hand) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. Available: ' . $product->Quantity_on_Hand
                ], 400);
            }
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $request->quantity;
            }
        }

        Session::put('customer_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,Product_ID'
        ]);

        $cart = Session::get('customer_cart', []);
        unset($cart[$request->product_id]);
        Session::put('customer_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        Session::forget('customer_cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get cart contents
     */
    public function getCart()
    {
        $cart = Session::get('customer_cart', []);
        $total = 0;
        $itemCount = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }

        return response()->json([
            'cart' => $cart,
            'total' => $total,
            'item_count' => $itemCount
        ]);
    }

    /**
     * Display cart page
     */
    public function showCart()
    {
        $cart = Session::get('customer_cart', []);
        $customer = Auth::guard('customer')->user();
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.cart.index', compact('cart', 'total', 'customer'));
    }

    /**
     * Process customer checkout
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('customer_cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        $customer = Auth::guard('customer')->user();
        
        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'Order_Date' => now(),
                'Staff_ID' => null, // Customer self-service order
                'Customer_ID' => $customer->Customer_ID,
                'customer_type' => 'internal',
                'Promotion_ID' => null,
                'Subtotal' => $subtotal,
                'Discount_Amount' => 0,
                'Final_Amount' => $subtotal,
                'payment_method' => $request->input('payment_method', 'card'),
                'payment_status' => 'completed',
                'transaction_id' => 'CUST-' . time() . rand(1000, 9999),
            ]);

            // Create order details and update stock
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);
                
                if ($product->Quantity_on_Hand < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->Product_Name}.");
                }

                OrderDetail::create([
                    'Order_ID' => $order->Order_ID,
                    'Product_ID' => $productId,
                    'Quantity' => $item['quantity'],
                ]);

                $product->Quantity_on_Hand -= $item['quantity'];
                $product->save();
            }

            // Award loyalty points if customer is eligible
            $loyaltyPointsAwarded = 0;
            $loyaltyMessage = '';
            
            if ($this->loyaltyService->isCustomerEligibleForLoyalty($customer->Customer_ID)) {
                $loyaltyPointsAwarded = $this->loyaltyService->awardPointsForPurchase($order);
                if ($loyaltyPointsAwarded > 0) {
                    $loyaltyMessage = " and {$loyaltyPointsAwarded} loyalty points awarded!";
                }
            }

            DB::commit();
            Session::forget('customer_cart');

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully!' . $loyaltyMessage,
                'order_id' => $order->Order_ID,
                'loyalty_points_awarded' => $loyaltyPointsAwarded
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}

