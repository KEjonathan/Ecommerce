@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Category Header and Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <div class="position-relative category-banner mb-4">
                @if($category->image)
                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid rounded" alt="{{ $category->name }}" style="height: 200px; width: 100%; object-fit: cover;">
                @else
                    <div class="bg-light text-center py-5 rounded" style="height: 200px;">
                        <i class="fas fa-folder-open fa-4x text-muted mt-4"></i>
                    </div>
                @endif
                <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
                    <h1 class="h2 text-white mb-0">{{ $category->name }}</h1>
                </div>
            </div>
            
            @if($category->description)
                <p class="lead mb-4">{{ $category->description }}</p>
            @endif
        </div>
    </div>
    
    <!-- Products in this Category -->
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.show', $category) }}" method="GET" id="filter-form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <h6 class="text-uppercase font-weight-bold small">Availability</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="availability" id="availability-all" value="" 
                                    {{ !request('availability') ? 'checked' : '' }} onChange="this.form.submit()">
                                <label class="form-check-label" for="availability-all">
                                    Any Time
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="availability" id="availability-morning" 
                                    value="morning" {{ request('availability') == 'morning' ? 'checked' : '' }} onChange="this.form.submit()">
                                <label class="form-check-label" for="availability-morning">
                                    Morning
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="availability" id="availability-afternoon" 
                                    value="afternoon" {{ request('availability') == 'afternoon' ? 'checked' : '' }} onChange="this.form.submit()">
                                <label class="form-check-label" for="availability-afternoon">
                                    Afternoon
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="availability" id="availability-evening" 
                                    value="evening" {{ request('availability') == 'evening' ? 'checked' : '' }} onChange="this.form.submit()">
                                <label class="form-check-label" for="availability-evening">
                                    Evening
                                </label>
                            </div>
                        </div>
                        
                        @if(request('availability'))
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-md-9">
            <!-- Search Bar -->
            <div class="mb-4">
                <form action="{{ route('categories.show', $category) }}" method="GET">
                    @if(request('availability'))
                        <input type="hidden" name="availability" value="{{ request('availability') }}">
                    @endif
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search in {{ $category->name }}..." name="search" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 product-card">
                            <div class="position-relative">
                                @if($product->image)
                                    <img src="https://images.unsplash.com/photo-1581235720704-06d3acfcb36f?q=80&w=2080&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center py-5" style="height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <!-- Availability badges -->
                                <div class="position-absolute bottom-0 start-0 p-2">
                                    @if($product->is_morning_available)
                                        <span class="badge bg-info me-1">Morning</span>
                                    @endif
                                    @if($product->is_afternoon_available)
                                        <span class="badge bg-warning me-1">Afternoon</span>
                                    @endif
                                    @if($product->is_evening_available)
                                        <span class="badge bg-dark">Evening</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-truncate">{{ $product->description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                    
                                    @if($product->stock_quantity > 0)
                                        <form action="{{ route('cart.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-link p-0">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found in this category. Try adjusting your filters or search terms.
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
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
    .category-banner {
        overflow: hidden;
        border-radius: 0.375rem;
    }
</style>
@endsection 