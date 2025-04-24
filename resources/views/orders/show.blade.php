@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0">Order #{{ $order->id }}</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
            
            @if($order->status == 'pending')
                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                </form>
            @endif
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3" style="width: 50px; height: 50px;">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="img-fluid rounded">
                                                    @else
                                                        <div class="bg-light rounded text-center py-2">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if($item->product)
                                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline">
                        <li class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0 mt-1">Your order has been placed successfully.</p>
                            </div>
                        </li>
                        
                        @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                            <li class="timeline-item">
                                <div class="timeline-marker {{ $order->status == 'processing' ? 'bg-primary' : 'bg-success' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Processing</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 mt-1">Your order is being processed.</p>
                                </div>
                            </li>
                        @endif
                        
                        @if($order->status == 'shipped' || $order->status == 'delivered')
                            <li class="timeline-item">
                                <div class="timeline-marker {{ $order->status == 'shipped' ? 'bg-primary' : 'bg-success' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Shipped</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 mt-1">Your order has been shipped.</p>
                                </div>
                            </li>
                        @endif
                        
                        @if($order->status == 'delivered')
                            <li class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Delivered</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 mt-1">Your order has been delivered successfully.</p>
                                </div>
                            </li>
                        @endif
                        
                        @if($order->status == 'cancelled')
                            <li class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Cancelled</h6>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y h:i A') }}</small>
                                    <p class="mb-0 mt-1">Your order has been cancelled.</p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            @if($order->notes)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Order #:</span>
                            <span>{{ $order->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Date:</span>
                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Total Items:</span>
                            <span>{{ $order->items->sum('quantity') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Status:</span>
                            <span>
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
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Method:</span>
                            <span>{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}</span>
                        </li>
                    </ul>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span>-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    
                    @foreach(json_decode($order->charges, true) ?? [] as $charge)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $charge['name'] }}:</span>
                            <span>${{ number_format($charge['amount'], 2) }}</span>
                        </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong>${{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Information</h5>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->name }}</strong><br>
                        {{ $order->address }}<br>
                        <i class="fas fa-phone-alt me-1 text-muted"></i> {{ $order->phone }}<br>
                        <i class="fas fa-envelope me-1 text-muted"></i> {{ $order->email }}
                    </address>
                </div>
            </div>
            
            <!-- Delivery Information -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Delivery Date:</strong><br>
                        {{ date('F d, Y', strtotime($order->delivery_date)) }}
                    </div>
                    <div>
                        <strong>Delivery Time:</strong><br>
                        @if($order->delivery_time == 'morning')
                            Morning (8:00 AM - 12:00 PM)
                        @elseif($order->delivery_time == 'afternoon')
                            Afternoon (12:00 PM - 4:00 PM)
                        @elseif($order->delivery_time == 'evening')
                            Evening (4:00 PM - 8:00 PM)
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        list-style-type: none;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: 0;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 0.25rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
        border-left: 1px solid #dee2e6;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
</style>
@endsection 