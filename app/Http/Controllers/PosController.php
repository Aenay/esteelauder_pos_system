<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Staff;
use App\Models\Department;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $customers = Customer::all(); // Get all customers for internal selection
        $promotions = Promotion::where('Is_Active', true)
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('Start_Date')->orWhere('Start_Date', '<=', $today);
            })
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('End_Date')->orWhere('End_Date', '>=', $today);
            })
            ->orderBy('Promotion_Name')
            ->get();
        return view('pos.index', compact('products', 'customers', 'promotions'));
    }

    public function getCart()
    {
        $cart = Session::get('cart', []);
        return response()->json($cart);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('Product_Name', 'like', "%{$query}%")
            ->orWhere('SKU', 'like', "%{$query}%")
            ->get();
        return response()->json($products);
    }

    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');
        $customers = Customer::where('Customer_Name', 'like', "%{$query}%")
            ->orWhere('Customer_Email', 'like', "%{$query}%")
            ->orWhere('Customer_Phone', 'like', "%{$query}%")
            ->get();
        return response()->json($customers);
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = Session::get('cart', []);

        $currentQuantity = isset($cart[$product->Product_ID]) ? $cart[$product->Product_ID]['quantity'] : 0;

        if ($product->Quantity_on_Hand <= $currentQuantity) {
            return response()->json(['error' => 'Not enough stock for ' . $product->Product_Name], 400);
        }

        if (isset($cart[$product->Product_ID])) {
            $cart[$product->Product_ID]['quantity']++;
        } else {
            $cart[$product->Product_ID] = [
                'name' => $product->Product_Name,
                'sku' => $product->SKU,
                'price' => $product->Price,
                'quantity' => 1,
                'stock' => $product->Quantity_on_Hand
            ];
        }

        Session::put('cart', $cart);
        return response()->json(['message' => 'Product added to cart', 'cart' => $cart]);
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = Session::get('cart');
            if (isset($cart[$request->id])) {

                $product = Product::findOrFail($request->id);
                if ($product->Quantity_on_Hand < $request->quantity) {
                    return response()->json(['error' => 'Not enough stock for ' . $product->Product_Name], 400);
                }

                $cart[$request->id]["quantity"] = $request->quantity;
                if ($cart[$request->id]["quantity"] <= 0) {
                    unset($cart[$request->id]);
                }
                Session::put('cart', $cart);
            }
        }
        return response()->json(['message' => 'Cart updated', 'cart' => Session::get('cart', [])]);
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
        return response()->json(['message' => 'Product removed from cart', 'cart' => Session::get('cart', [])]);
    }

    public function clearCart()
    {
        Session::forget('cart');
        return response()->json(['message' => 'Cart cleared', 'cart' => []]);
    }

    public function completeSale(Request $request)
    {
        $cart = Session::get('cart');

        if (!$cart || count($cart) === 0) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        DB::beginTransaction();
        try {
            $customerType = $request->input('customer_type');
            $customerId = null;

            if ($customerType === 'internal') {
                $customerId = $request->input('customer_id');
                if (!$customerId) {
                    throw new \Exception('Please select an internal customer.');
                }
                // Verify customer exists
                $customer = Customer::find($customerId);
                if (!$customer) {
                    throw new \Exception('Selected customer not found.');
                }
            } else { // external customer
                $customerName = $request->input('customer_name');
                if (!$customerName) {
                    $customerName = 'Walk-in Customer'; // Default name for external customers
                }
                
                // Create new external customer
                $customer = Customer::create([
                    'Customer_Name' => $customerName,
                    'Customer_Phone' => null,
                    'Customer_Address' => null,
                    'Customer_Email' => null,
                    'Customer_Type' => 'external',
                    'Registration_Date' => now(),
                ]);
                $customerId = $customer->Customer_ID;
            }

            // Retrieve Staff_ID based on authenticated user's email
            $user = Auth::user();
            $staff = Staff::where('email', $user->email)->first();

            if (!$staff) {
                // Ensure a department exists for the staff member
                $department = Department::firstOrCreate(
                    ['Department_ID' => 1],
                    ['Department_Name' => 'Sales', 'Description' => 'Default Sales Department']
                );

                // If staff member not found, create one
                $staff = Staff::create([
                    'Staff_Name' => $user->name,
                    'Staff_Phone' => null,
                    'Staff_Address' => null,
                    'email' => $user->email,
                    'password' => $user->password,
                    'department_id' => $department->Department_ID,
                ]);
            }

            $staffId = $staff->Staff_ID;

            $order = Order::create([
                'Order_Date' => now(),
                'Staff_ID' => $staffId,
                'Customer_ID' => $customerId,
                'customer_type' => $customerType,
                'Promotion_ID' => $request->input('promotion_id') == 0 ? null : $request->input('promotion_id'),
                'Subtotal' => $request->input('subtotal'),
                'Discount_Amount' => $request->input('discount'),
                'Final_Amount' => $request->input('total'),
                'payment_method' => $request->input('payment_method', 'card'),
                'payment_status' => 'completed',
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
            ]);

            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if (!$product) {
                    throw new \Exception("Product with ID {$id} not found.");
                }

                if ($product->Quantity_on_Hand < $details['quantity']) {
                    throw new \Exception("Not enough stock for {$product->Product_Name}.");
                }

                OrderDetail::create([
                    'Order_ID' => $order->Order_ID,
                    'Product_ID' => $id,
                    'Quantity' => $details['quantity'],
                ]);

                $product->Quantity_on_Hand -= $details['quantity'];
                $product->save();
            }

            DB::commit();
            Session::forget('cart');

            return response()->json([
                'message' => 'Sale completed successfully!',
                'order_id' => $order->Order_ID,
                'customer_name' => $customer->Customer_Name,
                'payment_method' => $request->input('payment_method', 'card'),
                'transaction_id' => $order->transaction_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function checkout(Request $request)
    {
        $cart = Session::get('cart');
        if (!$cart) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Create order logic here
        $order = Order::create([
            'Customer_ID' => $request->customer_id,
            'Final_Amount' => collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            }),
            'status' => 'completed'
        ]);

        // Clear the cart after successful checkout
        Session::forget('cart');

        return response()->json([
            'message' => 'Order completed successfully',
            'order_id' => $order->Order_ID
        ]);
    }
}