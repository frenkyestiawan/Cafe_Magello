<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Cafe Magello</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active {
            background-color: #374151;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-xl font-bold">Cafe Magello</h1>
                <p class="text-sm text-gray-400">Admin Panel</p>
            </div>
            
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}" 
                           class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart w-6"></i>
                            <span>Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.menus.index') }}" 
                           class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                            <i class="fas fa-utensils w-6"></i>
                            <span>Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tables.index') }}" 
                           class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                            <i class="fas fa-chair w-6"></i>
                            <span>Meja & QR Code</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}" 
                           class="sidebar-link flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-6"></i>
                            <span>Laporan Penjualan</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="p-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-2 rounded hover:bg-gray-700 text-left">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>