<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Rate;
use App\Models\Category;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Only require authentication for role-specific dashboards
        $this->middleware('auth')->only(['adminDashboard', 'userDashboard', 'deliveryDashboard']);
    }

    /**
     * Show the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect to role-specific dashboard if authenticated
            if ($user->isAdmin()) {
                return redirect()->route('dashboard.admin');
            } elseif ($user->isDelivery()) {
                return redirect()->route('dashboard.delivery');
            }
            
            // Regular user - show user dashboard
            return $this->userDashboard();
        }
        
        // Guest user - show public dashboard
        return $this->guestDashboard();
    }

    /**
     * Show the dashboard for guest users.
     *
     * @return \Illuminate\View\View
     */
    public function guestDashboard()
    {
        // Get featured products
        $featuredProducts = Product::where('status', true)
            ->inRandomOrder()
            ->take(8)
            ->get();
        
        // Get product categories
        $categories = Category::where('status', true)
            ->take(6)
            ->get();
        
        return view('dashboard.guest', compact('featuredProducts', 'categories'));
    }

    /**
     * Show featured products for the iframe.
     *
     * @return \Illuminate\View\View
     */
    public function featuredProducts()
    {
        $featuredProducts = Product::where('status', true)
            ->inRandomOrder()
            ->take(6)
            ->get();
            
        return view('dashboard.featured-products', compact('featuredProducts'));
    }

    /**
     * Admin dashboard data and view.
     */
    protected function adminDashboard()
    {
        // Get statistics for admin dashboard
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Orders statistics
        $totalOrders = Order::count();
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->count();
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $orderGrowth = $lastMonthOrders > 0 
            ? round((($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : 100;

        // Revenue statistics
        $totalRevenue = Order::sum('total');
        $lastMonthRevenue = Order::where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->sum('total');
        $currentMonthRevenue = Order::where('created_at', '>=', $currentMonth)->sum('total');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 100;

        // Customer statistics
        $totalCustomers = User::where('type', 'customer')->count();
        $lastMonthCustomers = User::where('type', 'customer')
            ->where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->count();
        $currentMonthCustomers = User::where('type', 'customer')
            ->where('created_at', '>=', $currentMonth)
            ->count();
        $customerGrowth = $lastMonthCustomers > 0 
            ? round((($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100, 1)
            : 100;

        // Product statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', true)->count();

        $stats = [
            'totalOrders' => $totalOrders,
            'orderGrowth' => $orderGrowth,
            'totalRevenue' => $totalRevenue,
            'revenueGrowth' => $revenueGrowth,
            'totalCustomers' => $totalCustomers,
            'customerGrowth' => $customerGrowth,
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
        ];

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::with('category')
            ->where('stock_quantity', '<=', 10)
            ->where('status', true)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentOrders', 'lowStockProducts'));
    }

    /**
     * User dashboard data and view.
     */
    protected function userDashboard()
    {
        $user = auth()->user();

        // Recent orders
        $recentOrders = $user->orders()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // User statistics
        $totalOrders = $user->orders()->count();
        $totalReviews = $user->ratings()->count();

        // Active cart
        $cart = $user->carts()->with('items.product')->first();

        // Unread notifications
        $unreadNotifications = $user->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();

        // Favorite products (highest rated by the user)
        $favoriteProducts = Product::with(['ratings', 'category'])
            ->whereHas('ratings', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('rating', '>=', 4);
            })
            ->take(6)
            ->get();

        // If not enough favorites, supplement with best-selling products
        if ($favoriteProducts->count() < 6) {
            $bestSellingIds = DB::table('order_items')
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->pluck('product_id');

            $additionalProducts = Product::with(['ratings', 'category'])
                ->whereIn('id', $bestSellingIds)
                ->whereNotIn('id', $favoriteProducts->pluck('id'))
                ->take(6 - $favoriteProducts->count())
                ->get();

            $favoriteProducts = $favoriteProducts->merge($additionalProducts);
        }

        return view('dashboard.user', compact(
            'recentOrders',
            'totalOrders',
            'totalReviews',
            'cart',
            'unreadNotifications',
            'favoriteProducts'
        ));
    }

    /**
     * Delivery personnel dashboard data and view.
     */
    protected function deliveryDashboard()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Delivery statistics
        $pendingDeliveries = $user->deliveryOrders()
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        $completedToday = $user->deliveryOrders()
            ->where('status', 'delivered')
            ->whereDate('updated_at', $today)
            ->count();

        $totalCompleted = $user->deliveryOrders()
            ->where('status', 'delivered')
            ->count();

        $stats = [
            'pendingDeliveries' => $pendingDeliveries,
            'completedToday' => $completedToday,
            'totalCompleted' => $totalCompleted,
        ];

        // Assigned orders (latest processing or shipped ones)
        $assignedOrders = $user->deliveryOrders()
            ->with('user')
            ->whereIn('status', ['processing', 'shipped'])
            ->latest()
            ->take(10)
            ->get();

        // Recent notifications
        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.delivery', compact('stats', 'assignedOrders', 'recentNotifications'));
    }
} 