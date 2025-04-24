@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-success mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                    <h2 class="mb-4">Thank You for Your Order!</h2>
                    <p class="lead mb-4">Your order #{{ $order->id }} has been placed successfully.</p>
                    <div class="mb-4">
                        <p class="mb-1">We'll deliver your items on:</p>
                        <h5>{{ date('F d, Y', strtotime($order->delivery_date)) }} ({{ ucfirst($order->delivery_time) }})</h5>
                    </div>
                    <p>A confirmation email has been sent to {{ $order->email }}.</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Shipping Information</h6>
                            <p class="mb-1">{{ $order->name }}</p>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->phone }}</p>
                            <p class="mb-0">{{ $order->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Order Summary</h6>
                            <p class="mb-1"><strong>Order Number:</strong> #{{ $order->id }}</p>
                            <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="mb-1"><strong>Payment Method:</strong> {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}</p>
                            <p class="mb-0"><strong>Order Status:</strong> <span class="badge bg-info">{{ ucfirst($order->status) }}</span></p>
                        </div>
                    </div>
                    
                    <h6>Items Ordered</h6>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
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
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end text-success">Discount:</td>
                                        <td class="text-end text-success">-${{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                @endif
                                @foreach(json_decode($order->charges, true) ?? [] as $charge)
                                    <tr>
                                        <td colspan="3" class="text-end">{{ $charge['name'] }}:</td>
                                        <td class="text-end">${{ number_format($charge['amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($order->notes)
                        <div class="mb-0">
                            <h6>Order Notes</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('orders.index') }}" class="btn btn-primary me-2">View All Orders</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection 