<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumina Market') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
            --dark-color: #1F2937;
            --light-color: #F9FAFB;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F3F4F6;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            padding: 6rem 0;
            color: white;
        }
        
        .hero-title {
            font-weight: 800;
            font-size: 3rem;
            line-height: 1.2;
        }
        
        .hero-text {
            font-size: 1.125rem;
            opacity: 0.9;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .category-card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: none;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .category-img {
            height: 160px;
            object-fit: cover;
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
            height: 200px;
            object-fit: cover;
        }
        
        .badge-discount {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
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
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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
            color: var(--primary-color);
        }
        
        .footer {
            background-color: var(--dark-color);
            color: #E5E7EB;
            padding: 3rem 0;
        }
        
        .footer-heading {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .footer-link {
            color: #D1D5DB;
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: color 0.2s;
        }
        
        .footer-link:hover {
            color: white;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            margin-right: 0.75rem;
            transition: background-color 0.2s;
        }
        
        .social-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .login-message {
            background-color: #EFF6FF;
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('dashboard') }}">Lumina Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard.user') }}">My Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                            Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    <li class="nav-item ms-2">
                        <a class="btn btn-outline-primary position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ session('cart_count', 0) }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title mb-4">Fresh Quality Products Delivered to Your Door</h1>
                    <p class="hero-text mb-4">Shop from our wide selection of fresh groceries and have them delivered right to your doorstep. Quality products, competitive prices, and fast delivery.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
                        <a href="#categories" class="btn btn-outline-light btn-lg">Browse Categories</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80" alt="Fresh groceries" class="img-fluid rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Login to Checkout Message -->
    @guest
    <div class="container mt-4">
        <div class="login-message p-3 rounded">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle fs-4 text-primary me-3"></i>
                <div>
                    <h5 class="mb-1">Browse as a Guest</h5>
                    <p class="mb-0">You can browse products and add them to your cart. <strong>Login or register</strong> when you're ready to checkout.</p>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Register</a>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Categories Section -->
    <section class="py-5" id="categories">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Shop by Category</h2>
                <p class="text-muted">Browse our wide selection of categories and find exactly what you need</p>
            </div>
            
            <div class="row g-4">
                @foreach($categories as $category)
                <div class="col-md-4 col-lg-2">
                    <div class="card category-card">
                        <img src="https://images.unsplash.com/photo-1555529771-122e5d9f2341?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top category-img" alt="{{ $category->name }}">
                        <div class="card-body p-3">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ $category->products_count }} Products</p>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-outline-primary">View Products</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-2">Featured Products</h2>
                    <p class="text-muted">Discover our handpicked selection of top quality products</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-primary disabled">View All Products</a>
            </div>
            
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100">
                        @if($product->discount_price)
                        <div class="badge badge-discount">
                            {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                        </div>
                        @endif
                        
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?q=80&w=2064&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top product-image" alt="{{ $product->name }}">
                        
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
                            
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-add-cart flex-grow-1">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-wishlist">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Why Shop With Us</h2>
                <p class="text-muted">We're committed to providing the best shopping experience</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-truck text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text text-muted">Get your orders delivered to your doorstep quickly and efficiently.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-check text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="card-title">Quality Assurance</h5>
                        <p class="card-text text-muted">We ensure all products meet the highest quality standards.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-credit-card text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="card-title">Secure Payments</h5>
                        <p class="card-text text-muted">Your payment information is always safe and secured.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-headset text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="card-title">24/7 Support</h5>
                        <p class="card-text text-muted">Our customer support team is always ready to help you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="footer-heading">Lumina Market</h5>
                    <p>Your one-stop shop for fresh, quality products delivered right to your doorstep.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="footer-heading">Shop</h5>
                    <a href="{{ route('products.index') }}" class="footer-link">All Products</a>
                    <a href="{{ route('categories.index') }}" class="footer-link">Categories</a>
                    <a href="#" class="footer-link">Deals & Offers</a>
                    <a href="#" class="footer-link">New Arrivals</a>
                </div>
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="footer-heading">About</h5>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Contact Us</a>
                    <a href="#" class="footer-link">Careers</a>
                    <a href="#" class="footer-link">Blog</a>
                </div>
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="footer-heading">Help</h5>
                    <a href="#" class="footer-link">FAQs</a>
                    <a href="#" class="footer-link">Shipping</a>
                    <a href="#" class="footer-link">Returns</a>
                    <a href="#" class="footer-link">Payment Methods</a>
                </div>
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="footer-heading">Legal</h5>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Cookie Policy</a>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0">&copy; {{ date('Y') }} Lumina Market. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <img src="https://via.placeholder.com/240x30" alt="Payment methods" height="30">
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 