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
            --sidebar-width: 260px;
            --header-height: 64px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F3F4F6;
            color: #111827;
            margin: 0;
            padding: 0;
        }
        
        .app-container {
            display: flex;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, var(--primary-color) 0%, #6366F1 100%);
            color: white;
            transition: all 0.3s;
            z-index: 100;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-content {
            padding: 20px 0;
        }
        
        .nav-item {
            padding: 8px 20px;
            margin: 4px 12px;
            border-radius: 8px;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
        }
        
        .nav-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        .content-area {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .top-header {
            height: var(--header-height);
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .toggle-sidebar {
            border: none;
            background: transparent;
            color: var(--dark-color);
            font-size: 20px;
            cursor: pointer;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu-toggle {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px;
            border-radius: 24px;
            transition: all 0.2s;
        }
        
        .user-menu-toggle:hover {
            background-color: #F3F4F6;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 220px;
            z-index: 100;
            margin-top: 4px;
            overflow: hidden;
            display: none;
        }
        
        .user-menu-dropdown.show {
            display: block;
        }
        
        .user-info {
            padding: 16px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .user-email {
            color: #6B7280;
            font-size: 14px;
        }
        
        .dropdown-item {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background-color: #F9FAFB;
        }
        
        .dropdown-item i {
            font-size: 16px;
            color: #6B7280;
        }
        
        .notifications-bell {
            position: relative;
            margin-right: 20px;
        }
        
        .notifications-icon {
            font-size: 20px;
            color: #6B7280;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .main-content {
            padding: 24px;
        }
        
        .iframe-container {
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: calc(100vh - var(--header-height) - 48px);
        }
        
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .section-title {
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .content-area {
                margin-left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="brand-logo">Lumina Market</a>
            </div>
            <div class="sidebar-content">
                <ul class="nav flex-column">
                    <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="bi bi-grid-1x2-fill nav-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}" class="nav-link">
                            <i class="bi bi-box-seam nav-icon"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('categories*') ? 'active' : '' }}">
                        <a href="{{ route('categories.index') }}" class="nav-link">
                            <i class="bi bi-folder2-open nav-icon"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('orders*') ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}" class="nav-link">
                            <i class="bi bi-bag-check nav-icon"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin() || auth()->user()->isDelivery())
                    <li class="nav-item {{ Request::is('delivery*') ? 'active' : '' }}">
                        <a href="{{ route('delivery-orders.index') }}" class="nav-link">
                            <i class="bi bi-truck nav-icon"></i>
                            <span>Deliveries</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item {{ Request::is('cart*') ? 'active' : '' }}">
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="bi bi-cart3 nav-icon"></i>
                            <span>Cart</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="nav-link">
                            <i class="bi bi-people nav-icon"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('charges*') ? 'active' : '' }}">
                        <a href="{{ route('charges.index') }}" class="nav-link">
                            <i class="bi bi-cash-stack nav-icon"></i>
                            <span>Charges</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('coupons*') ? 'active' : '' }}">
                        <a href="{{ route('coupons.index') }}" class="nav-link">
                            <i class="bi bi-ticket-perforated nav-icon"></i>
                            <span>Coupons</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item {{ Request::is('notifications*') ? 'active' : '' }}">
                        <a href="{{ route('notifications.index') }}" class="nav-link">
                            <i class="bi bi-bell nav-icon"></i>
                            <span>Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile.edit') }}" class="nav-link">
                            <i class="bi bi-person-circle nav-icon"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area" id="content-area">
            <div class="top-header">
                <button class="toggle-sidebar" id="toggle-sidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="d-flex align-items-center">
                    <div class="notifications-bell">
                        <a href="{{ route('notifications.index') }}">
                            <i class="bi bi-bell notifications-icon"></i>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="notification-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <div class="user-menu">
                        <button class="user-menu-toggle" id="user-menu-toggle">
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="user-menu-dropdown" id="user-menu-dropdown">
                            <div class="user-info">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-email">{{ auth()->user()->email }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                <span>Profile</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="dropdown-item">
                                <i class="bi bi-bell"></i>
                                <span>Notifications</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item" 
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Toggle user dropdown
        document.getElementById('user-menu-toggle').addEventListener('click', function() {
            document.getElementById('user-menu-dropdown').classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-menu-dropdown');
            const toggle = document.getElementById('user-menu-toggle');
            
            if (!toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html> 