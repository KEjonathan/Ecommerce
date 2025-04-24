@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Shopping Cart</h1>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
            @if(count($cartItems) > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($cartItems as $cartItem)
                                <li class="list-group-item py-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($cartItem->product->image)
                                                <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?q=80&w=2025&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded" alt="{{ $cartItem->product->name }}">
                                            @else
                                                <div class="bg-light text-center p-3 rounded">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">{{ $cartItem->product->name }}</h6>
                                            <p class="small text-muted mb-0">{{ $cartItem->product->category->name }}</p>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span>${{ number_format($cartItem->product->price, 2) }}</span>
                                        </div>
                                        <div class="col-md-2">
                                            <form action="{{ route('cart.update', $cartItem) }}" method="POST" class="quantity-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm decrement">-</button>
                                                    <input type="number" name="quantity" class="form-control text-center quantity-input" value="{{ $cartItem->quantity }}" min="1" max="{{ $cartItem->product->stock_quantity }}">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm increment">+</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <span>${{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}</span>
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <form action="{{ route('cart.destroy', $cartItem) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <div class="text-end mb-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue Shopping</a>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5>Your cart is empty</h5>
                        <p class="mb-4">Looks like you haven't added any products to your cart yet.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Order Summary -->
        <div class="col-md-4">
            @if(count($cartItems) > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if(isset($discount) && $discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        
                        @foreach($charges as $charge)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $charge->name }}:</span>
                                <span>${{ number_format($charge->amount, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong>${{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <!-- Coupon Form -->
                        <form action="{{ route('cart.index') }}" method="GET" class="mb-4">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" placeholder="Coupon code" name="coupon" value="{{ request('coupon') }}">
                                <button class="btn btn-outline-secondary" type="submit">Apply</button>
                            </div>
                            @if(session('coupon_error'))
                                <div class="text-danger small">{{ session('coupon_error') }}</div>
                            @endif
                            @if(session('coupon_success'))
                                <div class="text-success small">{{ session('coupon_success') }}</div>
                            @endif
                        </form>
                        
                        <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity increment/decrement
        const incrementButtons = document.querySelectorAll('.increment');
        const decrementButtons = document.querySelectorAll('.decrement');
        
        incrementButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('max'));
                const currentValue = parseInt(input.value);
                if (currentValue < max) {
                    input.value = currentValue + 1;
                    this.closest('form').submit();
                }
            });
        });
        
        decrementButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection 