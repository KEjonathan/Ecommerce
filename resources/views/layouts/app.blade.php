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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }
        
        .card-title {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .section-title {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
        }
        
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
        
        .footer {
            background-color: var(--dark-color);
            color: #E5E7EB;
            padding: 3rem 0;
            margin-top: auto;
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
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #E5E7EB;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
        }
        
        .pagination .page-item .page-link {
            color: var(--primary-color);
            border-radius: 6px;
            margin: 0 3px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: #6B7280;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.25rem;
        }
        
        .alert-success {
            background-color: #ECFDF5;
            color: #065F46;
        }
        
        .alert-danger {
            background-color: #FEF2F2;
            color: #991B1B;
        }
        
        .alert-warning {
            background-color: #FFFBEB;
            color: #92400E;
        }
        
        .alert-info {
            background-color: #EFF6FF;
            color: #1E40AF;
        }
        
        @media (max-width: 992px) {
            .product-image {
                height: 160px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ url('/') }}">Lumina Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about-us*') ? 'active' : '' }}" href="{{ route('about.us') }}">About Us</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                                <i class="bi bi-bell"></i>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->unreadNotifications()->count() }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard.user') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
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

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

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
                    <a href="{{ route('about.us') }}" class="footer-link">About Us</a>
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
    
    @yield('scripts')
</body>
</html>
