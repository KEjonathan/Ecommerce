@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="section-title">Welcome back, {{ auth()->user()->name }}!</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Total Orders</h5>
                        <div class="icon-box bg-primary-subtle rounded-circle p-2">
                            <i class="bi bi-bag-check fs-4 text-primary"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                    <p class="text-muted mb-0">All time orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Reviews</h5>
                        <div class="icon-box bg-success-subtle rounded-circle p-2">
                            <i class="bi bi-star fs-4 text-success"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $totalReviews }}</h2>
                    <p class="text-muted mb-0">Product reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Cart Items</h5>
                        <div class="icon-box bg-warning-subtle rounded-circle p-2">
                            <i class="bi bi-cart3 fs-4 text-warning"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $cart && isset($cart->items) ? $cart->items->count() : 0 }}</h2>
                    <p class="text-muted mb-0">Items in your cart</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Notifications</h5>
                        <div class="icon-box bg-info-subtle rounded-circle p-2">
                            <i class="bi bi-bell fs-4 text-info"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $unreadNotifications->count() }}</h2>
                    <p class="text-muted mb-0">Unread notifications</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Your Favorite Products</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View All Products</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        @forelse($favoriteProducts as $product)
                        <div class="col-md-4 p-3 border-bottom border-end">
                            <div class="d-flex align-items-center">
                                <img src="https://images.unsplash.com/photo-1560343090-f0409e92791a?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="{{ $product->name }}" class="rounded" width="60" height="60" style="object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <div class="d-flex align-items-center mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->ratings->avg('rating') ?? 0))
                                                <i class="bi bi-star-fill text-warning fs-6"></i>
                                            @else
                                                <i class="bi bi-star text-warning fs-6"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1 text-muted">({{ $product->ratings->count() }})</span>
                                    </div>
                                    <span class="text-primary fw-bold">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 py-4 text-center">
                            <div class="mb-3">
                                <i class="bi bi-box2 fs-1 text-muted"></i>
                            </div>
                            <h5>No favorite products yet!</h5>
                            <p class="text-muted">Browse our catalog and rate products to see them here.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Orders</h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="list-group-item list-group-item-action py-3 px-4">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">${{ number_format($order->total, 2) }} - {{ count($order->items) }} items</p>
                            <small class="text-capitalize text-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ $order->status }}
                            </small>
                        </a>
                        @empty
                        <div class="list-group-item py-4 text-center">
                            <i class="bi bi-bag fs-4 text-muted mb-2"></i>
                            <p class="mb-0 text-muted">No orders yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Notifications</h5>
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($unreadNotifications as $notification)
                        <a href="{{ route('notifications.show', $notification) }}" class="list-group-item list-group-item-action py-3 px-4">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-truncate">{{ $notification->message }}</p>
                            <small class="text-capitalize text-{{ $notification->type === 'system' ? 'primary' : ($notification->type === 'order' ? 'success' : 'warning') }}">
                                {{ $notification->type }}
                            </small>
                        </a>
                        @empty
                        <div class="list-group-item py-4 text-center">
                            <i class="bi bi-bell-slash fs-4 text-muted mb-2"></i>
                            <p class="mb-0 text-muted">No new notifications</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 