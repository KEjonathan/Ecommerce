<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Featured Products - Lumina Market</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: transparent;
            margin: 0;
            padding: 20px;
        }
        
        .product-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .product-image {
            height: 180px;
            object-fit: cover;
            background-color: #f9fafb;
        }
        
        .badge-discount {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #4F46E5;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }
        
        .product-title {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .product-category {
            color: #6B7280;
            font-size: 0.825rem;
            margin-bottom: 0.5rem;
        }
        
        .product-price {
            font-weight: 700;
            color: #111827;
            font-size: 1.1rem;
        }
        
        .original-price {
            text-decoration: line-through;
            color: #9CA3AF;
            font-weight: normal;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }
        
        .btn-add-cart {
            background-color: #4F46E5;
            border-color: #4F46E5;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        
        .btn-add-cart:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
        
        .btn-wishlist {
            border-radius: 8px;
            border: 1px solid #E5E7EB;
            background-color: white;
            color: #6B7280;
            padding: 0.5rem;
        }
        
        .btn-wishlist:hover {
            background-color: #F3F4F6;
            color: #4F46E5;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-4">
            @forelse ($featuredProducts as $product)
            <div class="col-md-4">
                <div class="card product-card">
                    @if($product->discount_price)
                    <div class="badge badge-discount">
                        {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                    </div>
                    @endif
                    
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=2080&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top product-image" alt="{{ $product->name }}">
                    
                    <div class="card-body p-3">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <p class="product-category">{{ $product->category->name }}</p>
                        
                        <div class="d-flex align-items-center mb-3">
                            @if($product->discount_price)
                            <span class="original-price">${{ number_format($product->price, 2) }}</span>
                            <span class="product-price">${{ number_format($product->discount_price, 2) }}</span>
                            @else
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-add-cart flex-grow-1">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                            <button class="btn btn-wishlist">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box2 fs-1 text-muted mb-3"></i>
                <h4>No featured products available</h4>
                <p class="text-muted">Check back later for exciting offers!</p>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 