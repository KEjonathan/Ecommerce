@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
        
        <!-- Statistics Overview -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Orders -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                            <i class="fas fa-shopping-cart text-primary-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['totalOrders'] }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold {{ $stats['orderGrowth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        @if($stats['orderGrowth'] >= 0)
                                            <i class="fas fa-arrow-up mr-0.5"></i>
                                        @else
                                            <i class="fas fa-arrow-down mr-0.5"></i>
                                        @endif
                                        <span>{{ abs($stats['orderGrowth']) }}%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('orders.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            View all<span class="sr-only"> orders</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">${{ number_format($stats['totalRevenue'], 2) }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold {{ $stats['revenueGrowth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        @if($stats['revenueGrowth'] >= 0)
                                            <i class="fas fa-arrow-up mr-0.5"></i>
                                        @else
                                            <i class="fas fa-arrow-down mr-0.5"></i>
                                        @endif
                                        <span>{{ abs($stats['revenueGrowth']) }}%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('orders.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            View details<span class="sr-only"> for revenue</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['totalCustomers'] }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold {{ $stats['customerGrowth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        @if($stats['customerGrowth'] >= 0)
                                            <i class="fas fa-arrow-up mr-0.5"></i>
                                        @else
                                            <i class="fas fa-arrow-down mr-0.5"></i>
                                        @endif
                                        <span>{{ abs($stats['customerGrowth']) }}%</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('users.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            View all<span class="sr-only"> customers</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-box text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $stats['totalProducts'] }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-gray-600">
                                        <span>{{ $stats['activeProducts'] }} active</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <a href="{{ route('products.index') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            View all<span class="sr-only"> products</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Orders -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('orders.show', $order) }}" class="text-primary-600 hover:text-primary-900">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($order->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($order->status === 'processing')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Processing
                                            </span>
                                        @elseif($order->status === 'shipped')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Shipped
                                            </span>
                                        @elseif($order->status === 'delivered')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Delivered
                                            </span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No recent orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        View all orders <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Low Stock Products</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($lowStockProducts as $product)
                                <tr class="bg-white">
                                    <td class="py-3 ps-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1999&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="{{ $product->name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $product->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $product->sku }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-red-600 font-medium">
                                            {{ $product->stock_quantity }} left
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($product->status)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No low stock products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        Manage inventory <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('products.create') }}" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                            <i class="fas fa-plus text-primary-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Add New Product</h3>
                            <p class="mt-1 text-sm text-gray-500">Create a new product listing in your inventory.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('categories.index') }}" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-tags text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Manage Categories</h3>
                            <p class="mt-1 text-sm text-gray-500">Organize your products with categories.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('coupons.index') }}" class="bg-white overflow-hidden shadow rounded-lg p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-tag text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Manage Coupons</h3>
                            <p class="mt-1 text-sm text-gray-500">Create and manage discount coupons.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 