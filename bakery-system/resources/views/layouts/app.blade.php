<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bakery System') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-canvas" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            
            <!-- Sidebar -->
            <aside 
                class="fixed inset-y-0 left-0 z-50 w-70 bg-surface border-r border-border transform transition-transform duration-base lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                x-transition:enter="transition-smooth duration-base"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition-smooth duration-base"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <!-- Sidebar Header / Brand -->
                <div class="h-topbar flex items-center px-6 border-b border-border">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-display font-bold text-lg text-text-main">Bakery</div>
                            <div class="text-xs text-text-muted font-semibold">Management</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                    @if(auth()->user()->isOwner())
                        <!-- Owner Navigation -->
                        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Products Section -->
                        <div class="nav-section">
                            <div class="nav-section-title">Produk</div>
                            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <span>Semua Produk</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Kategori</span>
                            </a>
                            <a href="{{ route('admin.promotions.index') }}" class="nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2m9-6a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Promo & Voucher</span>
                            </a>
                        </div>

                        <!-- Stock Section -->
                        <div class="nav-section">
                            <div class="nav-section-title">Stok</div>
                            <a href="{{ route('stock-movements.index') }}" class="nav-item {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Pergerakan Stok</span>
                            </a>
                        </div>

                        <!-- Orders Section -->
                        <div class="nav-section">
                            <div class="nav-section-title">Pesanan</div>
                            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Kelola Pesanan</span>
                            </a>
                        </div>

                        <!-- Employee Section -->
                        <a href="{{ route('employees.index') }}" class="nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Karyawan</span>
                        </a>

                        <!-- Attendance Section -->
                        <a href="{{ route('attendances.index') }}" class="nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Absensi</span>
                        </a>
                    @else
                        <!-- Employee Navigation -->
                        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('stock-movements.index') }}" class="nav-item {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Stok</span>
                        </a>

                        <a href="{{ route('attendance.my') }}" class="nav-item {{ request()->routeIs('attendance.my') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Absensi Saya</span>
                        </a>
                    @endif
                </nav>

                <!-- User Profile Section at Bottom -->
                <div class="p-4 border-t border-border">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-subtle">
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="body-sm font-semibold text-text-main truncate">{{ auth()->user()->name }}</div>
                            <div class="caption text-text-muted">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:ml-70">
                
                <!-- Topbar -->
                <header class="h-topbar bg-surface border-b border-border sticky top-0 z-40">
                    <div class="h-full px-6 flex items-center justify-between">
                        <!-- Mobile Menu Button -->
                        <button 
                            @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-lg hover:bg-surface-subtle transition-colors"
                        >
                            <svg class="w-6 h-6 text-text-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Page Title / Breadcrumb -->
                        @isset($header)
                            <div class="hidden lg:block">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Right Actions -->
                        <div class="flex items-center gap-3 ml-auto">
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button 
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-surface-subtle transition-colors"
                                >
                                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div 
                                    x-show="open"
                                    x-transition
                                    class="absolute right-0 mt-2 w-56 bg-surface border border-border rounded-xl shadow-sm overflow-hidden"
                                >
                                    <div class="p-3 border-b border-border">
                                        <div class="body-sm font-semibold text-text-main">{{ auth()->user()->name }}</div>
                                        <div class="caption text-text-muted">{{ auth()->user()->email }}</div>
                                    </div>
                                    <div class="p-2">
                                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>Profil</span>
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item w-full text-left text-danger">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                <span>Keluar</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Backdrop -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false"
            x-transition:enter="transition-smooth duration-base"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-smooth duration-base"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-overlay z-40 lg:hidden"
            style="display: none;"
        ></div>

        <style>
            /* Navigation Styles */
            .nav-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: var(--radius-lg);
                font-size: var(--type-body-sm);
                font-weight: 600;
                color: var(--color-text-muted);
                transition: all var(--duration-fast) var(--ease-snappy);
            }
            
            .nav-item:hover {
                background-color: var(--color-primary-soft);
                color: var(--color-primary-active);
            }
            
            .nav-item.active {
                background-color: var(--color-primary);
                color: var(--color-text-inverse);
            }
            
            .nav-section {
                margin-top: 1.5rem;
            }
            
            .nav-section-title {
                padding: 0.5rem 1rem;
                font-size: var(--type-caption);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--color-text-muted);
            }
            
            .dropdown-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                border-radius: var(--radius-md);
                font-size: var(--type-body-sm);
                font-weight: 500;
                color: var(--color-text-main);
                transition: background-color var(--duration-fast) var(--ease-snappy);
            }
            
            .dropdown-item:hover {
                background-color: var(--color-surface-subtle);
            }
        </style>
    </body>
</html>
