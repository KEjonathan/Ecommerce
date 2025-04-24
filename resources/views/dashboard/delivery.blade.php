@extends('layouts.app')

@section('title', 'Delivery Dashboard')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary">
                <i class="fas fa-home mr-1"></i> Home
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                <span class="text-gray-500">Delivery Dashboard</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container mx-auto py-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Hello, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 mt-1">Welcome to your delivery dashboard.</p>
            </div>
            <div class="hidden sm:flex items-center text-sm text-gray-500">
                <i class="far fa-calendar-alt mr-2"></i>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Order Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $stats['pendingDeliveries'] }}</h2>
                    <p class="text-sm text-gray-500">Pending Deliveries</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $stats['completedToday'] }}</h2>
                    <p class="text-sm text-gray-500">Completed Today</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $stats['totalCompleted'] }}</h2>
                    <p class="text-sm text-gray-500">Total Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Orders Section -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Assigned Orders</h2>
            <a href="{{ route('delivery.orders') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                View All
            </a>
        </div>
        
        @if(count($assignedOrders) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignedOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $order->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $order->user->phone }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="max-w-xs truncate">
                                        {{ $order->shipping_address }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->status === 'processing')
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
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-primary-600 hover:text-primary-900">
                                            <i class="fas fa-eye"></i>
                                            <span class="sr-only">View</span>
                                        </a>
                                        
                                        @if($order->status === 'processing')
                                            <form action="{{ route('delivery.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="shipped">
                                                <button type="submit" class="text-purple-600 hover:text-purple-900" onclick="return confirm('Mark this order as shipped?')">
                                                    <i class="fas fa-truck"></i>
                                                    <span class="sr-only">Mark as Shipped</span>
                                                </button>
                                            </form>
                                        @elseif($order->status === 'shipped')
                                            <form action="{{ route('delivery.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Mark this order as delivered?')">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span class="sr-only">Mark as Delivered</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-box-open text-3xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No orders assigned</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any delivery orders assigned at the moment.</p>
            </div>
        @endif
    </div>
    
    <!-- Delivery Map Section -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Delivery Map</h2>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg h-80 flex items-center justify-center">
                <div class="text-center">
                    <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                        <i class="fas fa-map-marked-alt text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900">Delivery map</h3>
                    <p class="mt-1 text-sm text-gray-500">View your delivery route and locations.</p>
                    <button class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        <i class="fas fa-location-arrow mr-2"></i>
                        Open Map
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity / Notifications -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            <a href="{{ route('notifications.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                View All
            </a>
        </div>
        
        @if(count($recentNotifications) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($recentNotifications as $notification)
                    <div class="p-4 sm:px-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'order')
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-green-600"></i>
                                    </div>
                                @elseif($notification->type === 'system')
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-cog text-blue-600"></i>
                                    </div>
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-bell text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $notification->title }}
                                </div>
                                <div class="text-sm text-gray-500 line-clamp-2">
                                    {{ $notification->message }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-bell-slash text-3xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
                <p class="mt-1 text-sm text-gray-500">You'll be notified when there's new activity.</p>
            </div>
        @endif
    </div>
</div>
@endsection 