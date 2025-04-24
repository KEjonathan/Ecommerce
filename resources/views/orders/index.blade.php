@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">My Orders</h1>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <!-- Order List -->
        <div class="col-md-12">
            @if(count($orders) > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Order History</h5>
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('orders.index') }}" method="GET" class="row g-2">
                                    <div class="col-auto">
                                        <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                            <option value="">All Statuses</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Delivery Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ $order->items->sum('quantity') }}</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>{{ date('M d, Y', strtotime($order->delivery_date)) }} ({{ ucfirst($order->delivery_time) }})</td>
                                            <td>
                                                @if($order->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($order->status == 'processing')
                                                    <span class="badge bg-primary">Processing</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge bg-info">Shipped</span>
                                                @elseif($order->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif($order->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                                
                                                @if($order->status == 'pending')
                                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($orders->hasPages())
                        <div class="card-footer bg-white">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5>No Orders Found</h5>
                        <p class="mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 