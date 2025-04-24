<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Listen for successful login events
        Event::listen(Login::class, function ($event) {
            // Log that we caught the login event
            Log::info('Login event triggered - checking for session cart');
            
            // Get the authenticated user
            $user = $event->user;
            
            // Check if session has a cart
            if (session()->has('cart')) {
                $sessionCart = session('cart');
                Log::info('Session cart found', ['items_count' => count($sessionCart['items'] ?? [])]);
                
                // If session cart has items
                if (!empty($sessionCart['items'])) {
                    // Get or create user's database cart
                    $userCart = Cart::firstOrCreate(
                        ['user_id' => $user->id],
                        ['subtotal' => 0]
                    );
                    
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
                                }
                            } else {
                                // Add new item to user's cart
                                $userCart->items()->create([
                                    'product_id' => $product->id,
                                    'quantity' => $item['quantity'],
                                    'price' => $product->discount_price ?? $product->price,
                                    'subtotal' => ($product->discount_price ?? $product->price) * $item['quantity']
                                ]);
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
                    Log::info('Session cart transferred to database cart', [
                        'cart_id' => $userCart->id,
                        'items_count' => $userCart->items()->count()
                    ]);
                    
                    // Clear the session cart
                    session()->forget(['cart', 'cart_count']);
                }
            } else {
                Log::info('No session cart found');
            }
        });
    }
}
