<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Charge;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the current user.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            // Admin sees all orders
            $orders = Order::with('user', 'deliveryPerson')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif ($user->isDelivery()) {
            // Delivery sees assigned orders
            $orders = $user->deliveryOrders()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Customer sees their own orders
            $orders = $user->orders()
                ->with('deliveryPerson')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,credit_card,paypal',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $user = auth()->user();
        $cart = $user->carts()->with('items.product', 'coupon')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }
        
        // Create order
        $order = new Order([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'subtotal' => $cart->subtotal,
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
            'coupon_id' => $cart->coupon_id,
        ]);
        
        // Calculate discount if coupon applied
        if ($cart->coupon) {
            if ($cart->coupon->type === 'percentage') {
                $order->discount = $cart->subtotal * ($cart->coupon->value / 100);
            } else {
                $order->discount = $cart->coupon->value;
            }
            
            // Increment coupon usage count
            $cart->coupon->increment('usage_count');
        } else {
            $order->discount = 0;
        }
        
        // Calculate taxes and charges
        $charges = Charge::where('status', true)->get();
        $tax = 0;
        
        foreach ($charges as $charge) {
            $tax += $charge->calculateAmount($cart->subtotal);
        }
        
        $order->tax = $tax;
        $order->total = $cart->subtotal - $order->discount + $tax;
        
        $order->save();
        
        // Create order items
        foreach ($cart->items as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'subtotal' => $cartItem->subtotal,
            ]);
            
            // Reduce product stock
            $product = $cartItem->product;
            $product->stock_quantity -= $cartItem->quantity;
            $product->save();
        }
        
        // Clear cart after order is placed
        $cart->items()->delete();
        $cart->delete();
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && !$user->isDelivery() && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->load('items.product', 'user', 'deliveryPerson', 'coupon');
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show orders assigned to delivery person.
     */
    public function deliveryOrders()
    {
        $user = auth()->user();
        
        if (!$user->isDelivery()) {
            abort(403, 'Unauthorized action.');
        }
        
        $orders = $user->deliveryOrders()
            ->whereIn('status', ['processing', 'shipped'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('orders.delivery', compact('orders'));
    }

    /**
     * Update order status by delivery person.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();
        
        // Authorization check
        if (!$user->isAdmin() && !$user->isDelivery()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Admin can do more changes
        if ($user->isAdmin()) {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
                'payment_status' => 'required|in:pending,paid,failed',
                'delivery_person_id' => 'nullable|exists:users,id',
            ]);
            
            // Verify delivery person is actually a delivery person
            if (!empty($validated['delivery_person_id'])) {
                $deliveryPerson = User::findOrFail($validated['delivery_person_id']);
                if (!$deliveryPerson->isDelivery()) {
                    return back()->with('error', 'Selected user is not a delivery person.');
                }
            }
            
            $order->update($validated);
        } else {
            // Delivery person can only update status to shipped or delivered
            $validated = $request->validate([
                'status' => 'required|in:shipped,delivered',
            ]);
            
            $order->status = $validated['status'];
            $order->save();
        }
        
        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
