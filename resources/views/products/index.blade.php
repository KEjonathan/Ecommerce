@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title mb-0">All Products</h1>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryFilter" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ isset($selectedCategory) && $selectedCategory ? $selectedCategory->name : 'All Categories' }}
                </button>
                <ul class="dropdown-menu shadow-sm" aria-labelledby="categoryFilter">
                    <li><a class="dropdown-item {{ !isset($selectedCategory) || !$selectedCategory ? 'active' : '' }}" href="{{ route('products.index') }}">All Categories</a></li>
                    @foreach($categories as $category)
                        <li><a class="dropdown-item {{ isset($selectedCategory) && $selectedCategory && $selectedCategory->id == $category->id ? 'active' : '' }}" href="{{ route('products.index', ['category_id' => $category->id]) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortOptions" data-bs-toggle="dropdown" aria-expanded="false">
                    Sort By
                </button>
                <ul class="dropdown-menu shadow-sm" aria-labelledby="sortOptions">
                    <li><a class="dropdown-item {{ request('sort') == 'latest' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}">Latest</a></li>
                    <li><a class="dropdown-item {{ request('sort') == 'price_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price: Low to High</a></li>
                    <li><a class="dropdown-item {{ request('sort') == 'price_desc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price: High to Low</a></li>
                    <li><a class="dropdown-item {{ request('sort') == 'popular' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Popular</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    @if(isset($selectedCategory) && $selectedCategory)
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">All Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedCategory->name }}</li>
            </ol>
        </nav>
    </div>
    @endif
    
    <div class="row g-4">
        @forelse($products as $product)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card product-card h-100">
                @if($product->discount_price)
                <div class="badge badge-discount">
                    {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                </div>
                @endif
                
                <a href="{{ route('products.show', $product) }}">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x300?text=No+Image" class="card-img-top product-image" alt="{{ $product->name }}">
                    @endif
                </a>
                
                <div class="card-body p-3">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                        <h5 class="product-title">{{ $product->name }}</h5>
                    </a>
                    <p class="product-category mb-2">
                        <a href="{{ route('products.index', ['category_id' => $product->category->id]) }}" class="text-decoration-none text-muted">
                            {{ $product->category->name }}
                        </a>
                    </p>
                    
                    <div class="d-flex align-items-center mb-2">
                        <div class="d-flex me-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->ratings->avg('rating') ?? 0))
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-muted small">({{ $product->ratings->count() }})</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        @if($product->discount_price)
                        <span class="text-decoration-line-through text-muted me-2">${{ number_format($product->price, 2) }}</span>
                        <span class="fw-bold text-primary fs-5">${{ number_format($product->discount_price, 2) }}</span>
                        @else
                        <span class="fw-bold text-primary fs-5">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="mb-4">
                <i class="bi bi-search fs-1 text-muted"></i>
            </div>
            <h3>No Products Found</h3>
            <p class="text-muted">We couldn't find any products matching your criteria.</p>
            @if(isset($selectedCategory) || request('search'))
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary mt-2">View All Products</a>
            @endif
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-5">
        {{ $products->withQueryString()->links() }}
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
</style>
@endsection 