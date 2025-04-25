<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes - accessible without login
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/featured', [DashboardController::class, 'featuredProducts'])->name('products.featured');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/about-us', [ContentController::class, 'aboutUs'])->name('about.us');

// Guest cart routes - maintain session cart without login
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');

// Manual cart merge route
Route::get('/cart/merge', [CartController::class, 'mergeGuestCartWithUserCart'])->name('cart.merge')->middleware('auth');

// Public dashboard routes (no auth required)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Role-specific dashboard routes (still need auth)
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin')->middleware('admin');
    Route::get('/dashboard/delivery', [DashboardController::class, 'deliveryDashboard'])->name('dashboard.delivery')->middleware('delivery');
    Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])->name('dashboard.user');
    
    // Checkout routes (protected by auth)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');
    
    // User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Customer routes
    Route::resource('orders', OrderController::class)->except(['store']);
    Route::resource('rates', RateController::class);
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    
    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class)->except(['index', 'show']);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('coupons', CouponController::class);
        Route::resource('charges', ChargeController::class);
        Route::resource('content', ContentController::class);
        
        // AboutUs management
        Route::get('/about-us/edit', [ContentController::class, 'editAboutUs'])->name('about.us.edit');
        Route::put('/about-us/update', [ContentController::class, 'updateAboutUs'])->name('about.us.update');
    });
    
    // Delivery personnel routes
    Route::middleware(['delivery'])->group(function () {
        Route::get('/delivery/orders', [OrderController::class, 'deliveryOrders'])->name('delivery.orders');
        Route::patch('/delivery/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('delivery.updateStatus');
    });
});

// Coupon routes for guest carts
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

Auth::routes();
