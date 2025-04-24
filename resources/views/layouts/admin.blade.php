<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Commerce') }} Admin - @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#4da6ff',
                            DEFAULT: '#0066cc',
                            dark: '#004d99',
                        },
                        secondary: {
                            light: '#f7fafc',
                            DEFAULT: '#edf2f7',
                            dark: '#cbd5e0',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            },
            plugins: [
                function({ addUtilities, addComponents, e, config }) {
                    const newUtilities = {
                        '.line-clamp-1': {
                            overflow: 'hidden',
                            display: '-webkit-box',
                            '-webkit-box-orient': 'vertical',
                            '-webkit-line-clamp': '1',
                        },
                        '.line-clamp-2': {
                            overflow: 'hidden',
                            display: '-webkit-box',
                            '-webkit-box-orient': 'vertical',
                            '-webkit-line-clamp': '2',
                        },
                        '.line-clamp-3': {
                            overflow: 'hidden',
                            display: '-webkit-box',
                            '-webkit-box-orient': 'vertical',
                            '-webkit-line-clamp': '3',
                        }
                    }
                    addUtilities(newUtilities)
                }
            ]
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            border-radius: 50%;
            background-color: #f56565;
            color: white;
            font-size: 0.75rem;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .sidebar-active {
            border-left: 4px solid #0066cc;
            background-color: rgba(0, 102, 204, 0.1);
        }

        /* Alpine.js Transitions */
        [x-cloak] { display: none !important; }
        
        .transition {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .transition-transform {
            transition-property: transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .duration-100 { transition-duration: 100ms; }
        .duration-200 { transition-duration: 200ms; }
        
        .ease-in { transition-timing-function: cubic-bezier(0.4, 0, 1, 1); }
        .ease-out { transition-timing-function: cubic-bezier(0, 0, 0.2, 1); }
        
        .opacity-0 { opacity: 0; }
        .opacity-100 { opacity: 1; }
        
        .scale-95 { transform: scale(0.95); }
        .scale-100 { transform: scale(1); }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cccccc;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #0066cc;
        }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="flex min-h-screen">
        <!-- Sidebar (Desktop) -->
        <div class="hidden md:flex md:flex-col w-64 bg-white shadow-md">
            <!-- Logo -->
            <div class="py-4 px-6 border-b">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <span class="text-xl font-bold text-primary">{{ config('app.name', 'E-Commerce') }}</span>
                </a>
                <p class="text-gray-500 text-sm mt-1">Admin Dashboard</p>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-grow py-4 px-2">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-gray-500"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('products.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-box w-5 text-gray-500"></i>
                            <span class="ml-3">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('categories.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-tags w-5 text-gray-500"></i>
                            <span class="ml-3">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('orders.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-shopping-bag w-5 text-gray-500"></i>
                            <span class="ml-3">Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('users.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-users w-5 text-gray-500"></i>
                            <span class="ml-3">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('coupons.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('coupons.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-percent w-5 text-gray-500"></i>
                            <span class="ml-3">Coupons</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('charges.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('charges.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-money-bill w-5 text-gray-500"></i>
                            <span class="ml-3">Charges</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('content.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('content.*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-file-alt w-5 text-gray-500"></i>
                            <span class="ml-3">Content</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about.us.edit') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('about.us.edit') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-info-circle w-5 text-gray-500"></i>
                            <span class="ml-3">About Us</span>
                        </a>
                    </li>
                </ul>
                
                <!-- Divider -->
                <hr class="my-4 border-gray-200">
                
                <!-- Store Front Link -->
                <ul>
                    <li>
                        <a href="{{ url('/') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-store w-5 text-gray-500"></i>
                            <span class="ml-3">View Store</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- User Profile -->
            <div class="border-t py-4 px-6">
                <div class="flex items-center">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full">
                    @else
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center text-gray-600 hover:text-red-500">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <!-- Mobile Menu Button -->
                    <button type="button" class="md:hidden text-gray-600 focus:outline-none" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h1>
                    
                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        @php
                            $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                            $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
                        @endphp
                        <x-notification-dropdown :notifications="$recentNotifications" :unreadCount="$unreadCount" />
                        
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-primary focus:outline-none">
                                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                                @if(auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white">
                                    Profile
                                </a>
                                <a href="{{ url('/') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white">
                                    View Store
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-primary hover:text-white">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Mobile Sidebar (hidden on desktop) -->
            <div class="md:hidden fixed inset-0 bg-gray-600 bg-opacity-75 z-40 hidden" id="mobile-sidebar-overlay"></div>
            
            <div class="md:hidden fixed inset-y-0 left-0 w-64 bg-white shadow-md z-50 transform -translate-x-full transition-transform duration-300 ease-in-out" id="mobile-sidebar">
                <!-- Logo -->
                <div class="py-4 px-6 border-b">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <span class="text-xl font-bold text-primary">{{ config('app.name', 'E-Commerce') }}</span>
                    </a>
                    <p class="text-gray-500 text-sm mt-1">Admin Dashboard</p>
                </div>
                
                <!-- Navigation Links -->
                <nav class="flex-grow py-4 px-2">
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-tachometer-alt w-5 text-gray-500"></i>
                                <span class="ml-3">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('products.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-box w-5 text-gray-500"></i>
                                <span class="ml-3">Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('categories.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-tags w-5 text-gray-500"></i>
                                <span class="ml-3">Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('orders.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-shopping-bag w-5 text-gray-500"></i>
                                <span class="ml-3">Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('users.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-users w-5 text-gray-500"></i>
                                <span class="ml-3">Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupons.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('coupons.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-percent w-5 text-gray-500"></i>
                                <span class="ml-3">Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('charges.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('charges.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-money-bill w-5 text-gray-500"></i>
                                <span class="ml-3">Charges</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('content.index') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('content.*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-file-alt w-5 text-gray-500"></i>
                                <span class="ml-3">Content</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.us.edit') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md {{ request()->routeIs('about.us.edit') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-info-circle w-5 text-gray-500"></i>
                                <span class="ml-3">About Us</span>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Divider -->
                    <hr class="my-4 border-gray-200">
                    
                    <!-- Store Front Link -->
                    <ul>
                        <li>
                            <a href="{{ url('/') }}" class="flex items-center py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fas fa-store w-5 text-gray-500"></i>
                                <span class="ml-3">View Store</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <!-- Breadcrumbs -->
                @hasSection('breadcrumbs')
                    <div class="mb-6">
                        @yield('breadcrumbs')
                    </div>
                @endif
                
                <!-- Alerts -->
                @if(session('success') || session('error') || session('info') || $errors->any())
                    <div class="mb-6">
                        @if(session('success'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
                                <p>{{ session('success') }}</p>
                                <button class="absolute top-0 right-0 mt-4 mr-4 text-green-700" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                                <p>{{ session('error') }}</p>
                                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        
                        @if(session('info'))
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 relative" role="alert">
                                <p>{{ session('info') }}</p>
                                <button class="absolute top-0 right-0 mt-4 mr-4 text-blue-700" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                                <ul class="list-disc pl-4">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700" onclick="this.parentElement.style.display='none'">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- AlpineJS - for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            
            if (sidebarToggle && mobileSidebar && overlay) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                
                overlay.addEventListener('click', function() {
                    mobileSidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
            
            // Close alert messages after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.display = 'none';
                });
            }, 5000);

            // Auto-refresh notification count every 60 seconds
            let notificationUpdateInterval;
            
            function startNotificationUpdates() {
                // Clear any existing intervals
                if (notificationUpdateInterval) {
                    clearInterval(notificationUpdateInterval);
                }
                
                // Initial fetch
                updateNotifications();
                
                // Set interval for updates
                notificationUpdateInterval = setInterval(updateNotifications, 60000); // Every minute
            }
            
            function updateNotifications() {
                fetch('{{ route("notifications.unread-count") }}')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Update all notification badges
                        const notificationBadges = document.querySelectorAll('.notification-badge');
                        notificationBadges.forEach(badge => {
                            if (data.count > 0) {
                                badge.textContent = data.count > 9 ? '9+' : data.count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        });
                        
                        // If there are new notifications, optionally reload the dropdown content
                        // This implementation would depend on how you want to handle refreshing the dropdown
                    })
                    .catch(error => {
                        console.error('Error updating notifications:', error);
                        // If we've had an error, try again later but with exponential backoff
                        clearInterval(notificationUpdateInterval);
                        setTimeout(startNotificationUpdates, 120000); // Try again in 2 minutes
                    });
            }
            
            // Start the notification updates
            startNotificationUpdates();
            
            // Handle visibility change (when user returns to the tab)
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    updateNotifications(); // Immediate update when tab becomes visible
                    startNotificationUpdates(); // Restart the interval
                } else {
                    clearInterval(notificationUpdateInterval); // Clear interval when tab is hidden
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html> 