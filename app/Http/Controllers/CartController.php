<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Charge;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');
        
        // Calculate cart total
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->subtotal;
        }
        
        // Update cart subtotal
        if ($cart->subtotal != $subtotal) {
            $cart->subtotal = $subtotal;
            $cart->save();
        }
        
        $cartItems = $cart->items;
        
        // Get charges (shipping, tax, etc.)
        $charges = Charge::where('status', true)->get();
        
        // Calculate discount if coupon is applied
        $discount = 0;
        if ($cart->coupon_id) {
            $coupon = Coupon::find($cart->coupon_id);
            if ($coupon && $coupon->status) {
                if ($coupon->type === 'percentage') {
                    $discount = ($subtotal * $coupon->value) / 100;
                } else {
                    $discount = $coupon->value;
                }
            }
        }
        
        // Calculate total
        $total = $subtotal - $discount;
        foreach ($charges as $charge) {
            $total += $charge->amount;
        }
        
        return view('cart.index', compact('cart', 'cartItems', 'subtotal', 'charges', 'discount', 'total'));
    }

    /**
     * Add a product to cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($validated['product_id']);
        
        // Check if product is available
        if (!$product->status) {
            return back()->with('error', 'This product is not available.');
        }
        
        // Check if there is enough stock
        if ($product->stock_quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available.');
        }
        
        // If authenticated, use database cart
        if (Auth::check()) {
            $cart = $this->getOrCreateCart();
            
            // Check if product is already in cart
            $cartItem = $cart->items()->where('product_id', $product->id)->first();
            
            if ($cartItem) {
                // Update quantity if product already in cart
                $cartItem->quantity += $validated['quantity'];
                $cartItem->price = $product->discount_price ?? $product->price;
                $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
                $cartItem->save();
            } else {
                // Add new item to cart
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->discount_price ?? $product->price,
                    'subtotal' => ($product->discount_price ?? $product->price) * $validated['quantity'],
                ]);
            }
            
            // Update cart subtotal
            $cart->subtotal = $cart->items()->sum('subtotal');
            $cart->save();
        } 
        // For guests, use session cart
        else {
            $sessionCart = session('cart', [
                'items' => [],
                'subtotal' => 0,
                'coupon_id' => null
            ]);
            
            $price = $product->discount_price ?? $product->price;
            $productId = $product->id;
            $quantity = $validated['quantity'];
            $subtotal = $price * $quantity;
            
            // Check if product already exists in cart
            $itemExists = false;
            foreach ($sessionCart['items'] as &$item) {
                if ($item['product_id'] == $productId) {
                    $item['quantity'] += $quantity;
                    $item['subtotal'] = $item['price'] * $item['quantity'];
                    $itemExists = true;
                    break;
                }
            }
            
            // If product doesn't exist in cart, add it
            if (!$itemExists) {
                $sessionCart['items'][] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }
            
            // Recalculate cart subtotal
            $sessionCart['subtotal'] = 0;
            foreach ($sessionCart['items'] as $item) {
                $sessionCart['subtotal'] += $item['subtotal'];
            }
            
            session(['cart' => $sessionCart]);
            session(['cart_count' => count($sessionCart['items'])]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update cart items quantity.
     */
    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        // For authenticated users
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($itemId);
            $product = $cartItem->product;
            
            // Check if there is enough stock
            if ($product->stock_quantity < $validated['quantity']) {
                return back()->with('error', 'Not enough stock available.');
            }
            
            // Update quantity
            $cartItem->quantity = $validated['quantity'];
            $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
            $cartItem->save();
            
            // Update cart subtotal
            $userCart = $cartItem->cart;
            $userCart->subtotal = $userCart->items()->sum('subtotal');
            $userCart->save();
        } 
        // For guests
        else {
            $sessionCart = session('cart');
            if (!$sessionCart) {
                return redirect()->route('cart.index');
            }
            
            // Find the item in the session cart
            foreach ($sessionCart['items'] as &$item) {
                if ($item['product_id'] == $itemId) {
                    $product = Product::find($itemId);
                    
                    // Check if there is enough stock
                    if ($product->stock_quantity < $validated['quantity']) {
                        return back()->with('error', 'Not enough stock available.');
                    }
                    
                    $item['quantity'] = $validated['quantity'];
                    $item['subtotal'] = $item['price'] * $item['quantity'];
                    break;
                }
            }
            
            // Recalculate cart subtotal
            $sessionCart['subtotal'] = 0;
            foreach ($sessionCart['items'] as $item) {
                $sessionCart['subtotal'] += $item['subtotal'];
            }
            
            session(['cart' => $sessionCart]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from cart.
     */
    public function destroy($itemId)
    {
        // For authenticated users
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($itemId);
            $userCart = $cartItem->cart;
            
            // Delete the cart item
            $cartItem->delete();
            
            // Update cart subtotal
            $userCart->subtotal = $userCart->items()->sum('subtotal');
            $userCart->save();
        } 
        // For guests
        else {
            $sessionCart = session('cart');
            if (!$sessionCart) {
                return redirect()->route('cart.index');
            }
            
            // Find and remove the item from session cart
            foreach ($sessionCart['items'] as $key => $item) {
                if ($item['product_id'] == $itemId) {
                    unset($sessionCart['items'][$key]);
                    break;
                }
            }
            
            // Reindex the array
            $sessionCart['items'] = array_values($sessionCart['items']);
            
            // Recalculate cart subtotal
            $sessionCart['subtotal'] = 0;
            foreach ($sessionCart['items'] as $item) {
                $sessionCart['subtotal'] += $item['subtotal'];
            }
            
            session(['cart' => $sessionCart]);
            session(['cart_count' => count($sessionCart['items'])]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
    
    /**
     * Apply coupon to cart.
     */
    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);
        
        $cart = $this->getOrCreateCart();
        $coupon = Coupon::where('code', $validated['coupon_code'])->first();
        
        // Check if coupon is valid
        if (!$coupon->isValidFor($cart->subtotal)) {
            return back()->with('error', 'This coupon cannot be applied to your cart.');
        }
        
        // Apply coupon to cart
        $cart->coupon_id = $coupon->id;
        $cart->save();
        
        return redirect()->route('cart.index')->with('success', 'Coupon applied successfully.');
    }
    
    /**
     * Remove coupon from cart.
     */
    public function removeCoupon()
    {
        $cart = $this->getOrCreateCart();
        $cart->coupon_id = null;
        $cart->save();
        
        return redirect()->route('cart.index')->with('success', 'Coupon removed successfully.');
    }
    
    /**
     * Show the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        // Check if user is authenticated before checkout
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please log in to complete your purchase')
                ->with('redirect_to', route('cart.checkout'));
        }
        
        $user = Auth::user();
        $cart = $this->getCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add products before checking out.');
        }
        
        // Get charges for checkout
        $charges = Charge::where('status', true)->get();
        
        return view('cart.checkout', compact('cart', 'charges'));
    }
    
    /**
     * Get the current user's cart or create a new one.
     * 
     * Supports both authenticated users and guests.
     */
    private function getOrCreateCart()
    {
        // For authenticated users, use database carts
        if (Auth::check()) {
            $user = Auth::user();
            $cart = $user->carts()->first();
            
            if (!$cart) {
                $cart = new Cart([
                    'user_id' => $user->id,
                    'subtotal' => 0,
                ]);
                $cart->save();
            }
            
            return $cart;
        }
        
        // For guests, use session-based carts
        $sessionCart = session('cart');
        
        if (!$sessionCart) {
            $sessionCart = [
                'items' => [],
                'subtotal' => 0,
                'coupon_id' => null
            ];
            session(['cart' => $sessionCart]);
        }
        
        // Convert session cart to a Cart model instance for consistency
        $cart = new Cart();
        $cart->subtotal = $sessionCart['subtotal'];
        $cart->coupon_id = $sessionCart['coupon_id'];
        
        // Create a collection of CartItem objects
        $items = collect();
        foreach ($sessionCart['items'] as $item) {
            $cartItem = new CartItem($item);
            $cartItem->product = Product::find($item['product_id']);
            $items->push($cartItem);
        }
        
        // Attach the items collection to the cart
        $cart->setRelation('items', $items);
        
        return $cart;
    }

    /**
     * Get the cart for the current user or session.
     */
    private function getCart()
    {
        return $this->getOrCreateCart();
    }

    /**
     * Transfer a guest cart to a user's cart.
     * This can be called manually if needed.
     */
    public function mergeGuestCartWithUserCart()
    {
        // Only applicable if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if session has a cart
        if (!session()->has('cart')) {
            return redirect()->route('cart.index')
                ->with('info', 'No guest cart found to merge.');
        }
        
        $sessionCart = session('cart');
        
        // If session cart has no items, do nothing
        if (empty($sessionCart['items'])) {
            session()->forget(['cart', 'cart_count']);
            return redirect()->route('cart.index')
                ->with('info', 'Your guest cart was empty.');
        }
        
        $user = Auth::user();
        
        // Get or create user's database cart
        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['subtotal' => 0]
        );
        
        $mergedCount = 0;
        
        // Transfer items from session cart to database cart
        foreach ($sessionCart['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            if ($product && $product->status && $product->stock_quantity >= $item['quantity']) {
                // Check if this product is already in the user's cart
                $existingItem = $userCart->items()->where('product_id', $product->id)->first();
                
                if ($existingItem) {
                    // Update existing item quantity
                    $newQuantity = $existingItem->quantity + $item['quantity'];
                    
                    // Make sure new quantity doesn't exceed stock
                    if ($newQuantity <= $product->stock_quantity) {
                        $existingItem->quantity = $newQuantity;
                        $existingItem->subtotal = $existingItem->price * $newQuantity;
                        $existingItem->save();
                        $mergedCount++;
                    }
                } else {
                    // Add new item to user's cart
                    $userCart->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->discount_price ?? $product->price,
                        'subtotal' => ($product->discount_price ?? $product->price) * $item['quantity']
                    ]);
                    $mergedCount++;
                }
            }
        }
        
        // Update cart subtotal
        $userCart->subtotal = $userCart->items()->sum('subtotal');
        
        // Transfer any coupon from session to database cart
        if (!empty($sessionCart['coupon_id'])) {
            $userCart->coupon_id = $sessionCart['coupon_id'];
        }
        
        $userCart->save();
        
        // Clear the session cart
        session()->forget(['cart', 'cart_count']);
        
        return redirect()->route('cart.index')
            ->with('success', "Your guest cart items ($mergedCount items) have been added to your account.");
    }
}
