<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Gemini Pro Trader')) - Admin Panel</title>

    <!-- Admin One CSS -->
    <link rel="stylesheet" href="{{ asset('admin-one-css/main.css') }}">
    
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}"/>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}"/>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Additional CSS for better compatibility -->
    <style>
        /* Ensure proper layout */
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        /* Fix any layout issues */
        .section.main-section {
            padding: 1.5rem;
        }
        
        /* Ensure cards display properly */
        .card {
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        /* Fix notification styling */
        .notification {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        
        .notification.is-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .notification.is-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
    
    @stack('styles')
</head>
<body>

<div id="app">

<!-- Navbar -->
<nav id="navbar-main" class="navbar is-fixed-top">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
            <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
        <div class="navbar-item">
            <div class="control">
                <input placeholder="Search..." class="input">
            </div>
        </div>
    </div>
    <div class="navbar-brand is-right">
        <a class="navbar-item --jb-navbar-menu-toggle" data-target="navbar-menu">
            <span class="icon"><i class="mdi mdi-dots-vertical mdi-24px"></i></span>
        </a>
    </div>
    <div class="navbar-menu" id="navbar-menu">
        <div class="navbar-end">
            <div class="navbar-item dropdown has-divider has-user-avatar">
                <a class="navbar-link">
                    <div class="user-avatar">
                        <img src="https://avatars.dicebear.com/v2/initials/{{ auth()->user()->name ?? 'Admin' }}.svg" 
                             alt="{{ auth()->user()->name ?? 'Admin' }}" class="rounded-full">
                    </div>
                    <div class="is-user-name"><span>{{ auth()->user()->name ?? 'Admin' }}</span></div>
                    <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('profile.edit') }}" class="navbar-item">
                        <span class="icon"><i class="mdi mdi-account"></i></span>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="navbar-item">
                        <span class="icon"><i class="mdi mdi-settings"></i></span>
                        <span>Settings</span>
                    </a>
                    <hr class="navbar-divider">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="navbar-item w-full text-left">
                            <span class="icon"><i class="mdi mdi-logout"></i></span>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
        <div>
            <strong>Gemini Pro</strong> <b class="font-black">Trader</b>
        </div>
    </div>
    <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                    <span class="menu-item-label">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.webhook-dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="icon"><i class="mdi mdi-webhook"></i></span>
                    <span class="menu-item-label">Webhook Dashboard</span>
                </a>
            </li>
        </ul>
        
        <p class="menu-label">Trading</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('expert-signals.*') ? 'active' : '' }}">
                <a href="{{ route('expert-signals.public') }}">
                    <span class="icon"><i class="mdi mdi-chart-line"></i></span>
                    <span class="menu-item-label">Expert Signals</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('bots.*') ? 'active' : '' }}">
                <a href="{{ route('bots.index') }}">
                    <span class="icon"><i class="mdi mdi-robot"></i></span>
                    <span class="menu-item-label">Trading Bots</span>
                </a>
            </li>
        </ul>
        
        <p class="menu-label">Management</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                    <span class="menu-item-label">Users</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.financial.*') ? 'active' : '' }}">
                <a href="{{ route('admin.financial.index') }}">
                    <span class="icon"><i class="mdi mdi-currency-usd"></i></span>
                    <span class="menu-item-label">Financial</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <a href="{{ route('billing.index') }}">
                    <span class="icon"><i class="mdi mdi-credit-card"></i></span>
                    <span class="menu-item-label">Billing</span>
                </a>
            </li>
        </ul>
        
        <p class="menu-label">System</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a href="{{ route('admin.notifications.index') }}">
                    <span class="icon"><i class="mdi mdi-bell"></i></span>
                    <span class="menu-item-label">Notifications</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                <a href="{{ route('admin.audit.index') }}">
                    <span class="icon"><i class="mdi mdi-history"></i></span>
                    <span class="menu-item-label">Audit Logs</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}">
                    <span class="icon"><i class="mdi mdi-cog"></i></span>
                    <span class="menu-item-label">Settings</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Title Bar -->
<section class="is-title-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <ul>
            <li>Admin</li>
            <li>@yield('page-title', 'Dashboard')</li>
        </ul>
        @yield('title-bar-actions')
    </div>
</section>

<!-- Hero Bar -->
<section class="is-hero-bar">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
        <h1 class="title">
            @yield('page-heading', 'Dashboard')
        </h1>
        @yield('hero-actions')
    </div>
</section>

<!-- Main Content -->
<section class="section main-section">
    @if(session('success'))
        <div class="notification green">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                <div>
                    <span class="icon"><i class="mdi mdi-buffer"></i></span>
                    <b>Success!</b> {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="notification red">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                <div>
                    <span class="icon"><i class="mdi mdi-alert"></i></span>
                    <b>Error!</b> {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="notification red">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
                <div>
                    <span class="icon"><i class="mdi mdi-alert"></i></span>
                    <b>Validation Error!</b>
                    <ul class="mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @yield('content')
</section>

<!-- Footer -->
<footer class="footer">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0">
        <div class="flex items-center justify-start space-x-3">
            <div>
                © {{ date('Y') }}, Gemini Pro Trader
            </div>
        </div>
        <div class="flex items-center justify-start space-x-3">
            <div>
                <b>Admin Panel</b> powered by Laravel
            </div>
        </div>
    </div>
</footer>

</div>

<!-- Admin One JS -->
<script src="{{ asset('admin-one-js/main.js') }}"></script>

@stack('scripts')

</body>
</html>