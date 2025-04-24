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
                    <h2 class="mb-0">{{ $totalOrders ?? 0 }}</h2>
                    <p class="text-success mb-0"><i class="bi bi-arrow-up"></i> 12% this month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Cart Items</h5>
                        <div class="icon-box bg-success-subtle rounded-circle p-2">
                            <i class="bi bi-cart3 fs-4 text-success"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $cartItems ?? 0 }}</h2>
                    <p class="text-success mb-0"><i class="bi bi-arrow-up"></i> 8% this week</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Notifications</h5>
                        <div class="icon-box bg-warning-subtle rounded-circle p-2">
                            <i class="bi bi-bell fs-4 text-warning"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $unreadNotifications ?? 0 }}</h2>
                    <p class="text-muted mb-0">Unread notifications</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0 text-muted">Saved Items</h5>
                        <div class="icon-box bg-info-subtle rounded-circle p-2">
                            <i class="bi bi-heart fs-4 text-info"></i>
                        </div>
                    </div>
                    <h2 class="mb-0">{{ $savedItems ?? 0 }}</h2>
                    <p class="text-muted mb-0">Products saved for later</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Featured Products</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="iframe-container">
                        <iframe src="{{ route('products.featured') }}" title="Featured Products"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Notifications</h5>
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentNotifications ?? [] as $notification)
                        <a href="{{ route('notifications.show', $notification) }}" class="list-group-item list-group-item-action py-3 px-4 {{ $notification->read_at ? '' : 'bg-light' }}">
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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <h5 class="mb-0">Your Activity</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-primary-subtle rounded-circle p-2 me-3">
                                    <i class="bi bi-bag-check text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Your order <span class="fw-bold">#ORD-123</span> has been shipped</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-success-subtle rounded-circle p-2 me-3">
                                    <i class="bi bi-cart-check text-success"></i>
                                </div>
                                <div>
                                    <p class="mb-0">Added <span class="fw-bold">Organic Apples</span> to your cart</p>
                                    <small class="text-muted">5 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-warning-subtle rounded-circle p-2 me-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                                <div>
                                    <p class="mb-0">You rated <span class="fw-bold">Fresh Bread</span> with 5 stars</p>
                                    <small class="text-muted">1 day ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 