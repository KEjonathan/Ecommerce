@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Images and Gallery -->
        <div class="col-md-6 mb-4">
            <div class="card border-0">
                <div class="text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                    @else
                        <div class="bg-light text-center py-5 rounded" style="height: 400px;">
                            <i class="fas fa-image fa-5x text-muted mt-5"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Product Information -->
        <div class="col-md-6">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
            
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <span class="h3 d-block mb-3">${{ number_format($product->price, 2) }}</span>
                
                <div class="d-flex mb-3">
                    @if($product->ratings->count() > 0)
                        <div class="me-3">
                            @php
                                $avgRating = $product->ratings->avg('rating');
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars >= 0.5;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp
                            
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                            
                            @if($halfStar)
                                <i class="fas fa-star-half-alt text-warning"></i>
                            @endif
                            
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star text-warning"></i>
                            @endfor
                            
                            <span class="text-muted ms-1">{{ number_format($avgRating, 1) }} ({{ $product->ratings->count() }} reviews)</span>
                        </div>
                    @else
                        <span class="text-muted">No ratings yet</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <p class="mb-1">Availability:</p>
                    <div>
                        @if($product->is_morning_available)
                            <span class="badge bg-info me-1">Morning</span>
                        @endif
                        @if($product->is_afternoon_available)
                            <span class="badge bg-warning me-1">Afternoon</span>
                        @endif
                        @if($product->is_evening_available)
                            <span class="badge bg-dark me-1">Evening</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="mb-1">Status:</p>
                    @if($product->stock_quantity > 0)
                        <span class="badge bg-success">In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>
            
            <p class="mb-4">{{ $product->description }}</p>
            
            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.store') }}" method="POST" class="d-flex mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="input-group me-3" style="width: 120px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="decrement">-</button>
                        <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" id="quantity">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="increment">+</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            @endif
        </div>
    </div>
    
    <!-- Product Details Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="true">Reviews ({{ $product->ratings->count() }})</button>
                </li>
                @if($product->supplier)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#supplier" type="button" role="tab" aria-controls="supplier" aria-selected="false">Supplier Information</button>
                </li>
                @endif
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                <div class="tab-pane fade show active" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    @if($product->ratings->count() > 0)
                        @foreach($product->ratings as $rating)
                            <div class="mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $rating->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $rating->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <div>
                                        @for($i = 0; $i < 5; $i++)
                                            @if($i < $rating->rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-0">{{ $rating->comment }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No reviews yet. Be the first to leave a review!</p>
                    @endif
                    
                    @auth
                        @if(!$product->ratings->contains('user_id', auth()->id()))
                            <div class="card mt-4">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0">Write a Review</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('rates.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Your Rating</label>
                                            <div class="rating-stars mb-2">
                                                <div class="d-flex">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <div class="me-2">
                                                            <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" required>
                                                            <label for="rating-{{ $i }}">{{ $i }} Stars</label>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Your Review</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mt-4">
                            Please <a href="{{ route('login') }}">login</a> to leave a review.
                        </div>
                    @endauth
                </div>
                
                @if($product->supplier)
                <div class="tab-pane fade" id="supplier" role="tabpanel" aria-labelledby="supplier-tab">
                    <h5>{{ $product->supplier->name }}</h5>
                    <p>{{ $product->supplier->description }}</p>
                    <p><strong>Contact:</strong> {{ $product->supplier->email }}</p>
                    <p><strong>Phone:</strong> {{ $product->supplier->phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <div class="card product-card h-100">
                            <a href="{{ route('products.show', $relatedProduct) }}">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 150px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/300x150?text=No+Image" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 150px; object-fit: cover;">
                                @endif
                            </a>
                            <div class="card-body">
                                <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                <p class="card-text mb-1">${{ number_format($relatedProduct->price, 2) }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-link p-0">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantity = document.getElementById('quantity');
        const increment = document.getElementById('increment');
        const decrement = document.getElementById('decrement');
        
        if (increment && decrement && quantity) {
            increment.addEventListener('click', function() {
                const max = parseInt(quantity.getAttribute('max'));
                const currentValue = parseInt(quantity.value);
                if (currentValue < max) {
                    quantity.value = currentValue + 1;
                }
            });
            
            decrement.addEventListener('click', function() {
                const currentValue = parseInt(quantity.value);
                if (currentValue > 1) {
                    quantity.value = currentValue - 1;
                }
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection 