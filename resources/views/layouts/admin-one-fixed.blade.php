<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Force CSS Load -->
    <style>
        /* Ensure basic styling works */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        
        /* Force layout structure */
        #app {
            min-height: 100vh;
        }
        
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            height: 3.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 30;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }
        
        .aside {
            background: #1f2937;
            width: 15rem;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40;
            color: white;
        }
        
        .main-content {
            margin-left: 15rem;
            margin-top: 3.5rem;
            padding: 1.5rem;
        }
        
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .columns {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .column {
            flex: 1;
            min-width: 300px;
        }
        
        .button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .button:hover {
            background: #2563eb;
        }
        
        .notification {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        
        .notification.is-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .notification.is-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .title {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.125rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-list li a {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #d1d5db;
            text-decoration: none;
            gap: 0.75rem;
        }
        
        .menu-list li a:hover {
            background: #374151;
        }
        
        .menu-list li.active a {
            background: #374151;
        }
        
        .menu-label {
            color: #9ca3af;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            margin: 0;
        }
        
        .field {
            margin-bottom: 1rem;
        }
        
        .label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .control select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: white;
        }
        
        .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .navbar-end {
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 50;
            display: none;
        }
        
        .dropdown.active .dropdown-menu {
            display: block;
        }
        
        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
        }
        
        .dropdown-item:hover {
            background: #f3f4f6;
        }
        
        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
        }
        
        .footer {
            background: white;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        @media (max-width: 1024px) {
            .aside {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar {
                padding-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

<div id="app">

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item mobile-aside-button">
            <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
        <div class="navbar-item">
            <input placeholder="Search..." class="input" style="border: 1px solid #d1d5db; padding: 0.5rem; border-radius: 0.375rem;">
        </div>
    </div>
    <div class="navbar-end">
        <div class="navbar-item dropdown">
            <a class="navbar-link" onclick="toggleDropdown(this)">
                <div class="user-avatar">
                    <img src="https://avatars.dicebear.com/v2/initials/{{ Auth::user()->name ?? 'Admin User' }}.svg" alt="{{ Auth::user()->name ?? 'Admin User' }}" class="user-avatar">
                </div>
                <span>{{ Auth::user()->name ?? 'Admin User' }}</span>
                <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
            </a>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item">
                    <span class="icon"><i class="mdi mdi-account"></i></span>
                    My Profile
                </a>
                <a href="#" class="dropdown-item">Settings</a>
                <hr>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
                        <span class="icon"><i class="mdi mdi-logout"></i></span>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<aside class="aside">
    <div class="aside-tools" style="padding: 1rem; border-bottom: 1px solid #374151;">
        <div>
            <strong>Gemini Pro</strong> <span style="color: #3b82f6;">Trader</span>
        </div>
    </div>
    
    <div style="padding: 1rem 0;">
        <p class="menu-label">General</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="icon"><i class="mdi mdi-view-dashboard"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.webhook-dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="icon"><i class="mdi mdi-webhook"></i></span>
                    <span>Webhook Dashboard</span>
                </a>
            </li>
        </ul>

        <p class="menu-label">Trading</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('expert-signals.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-chart-line"></i></span>
                    <span>Expert Signals</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('bots.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-robot"></i></span>
                    <span>Trading Bots</span>
                </a>
            </li>
        </ul>

        <p class="menu-label">Management</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-account-group"></i></span>
                    <span>Users</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.financial.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-cash"></i></span>
                    <span>Financial</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('billing.*') ? 'active' : '' }}">
                <a href="{{ route('billing.index') }}">
                    <span class="icon"><i class="mdi mdi-credit-card"></i></span>
                    <span>Billing</span>
                </a>
            </li>
        </ul>

        <p class="menu-label">System</p>
        <ul class="menu-list">
            <li class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-bell"></i></span>
                    <span>Notifications</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-file-document"></i></span>
                    <span>Audit Logs</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="#">
                    <span class="icon"><i class="mdi mdi-cog"></i></span>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" style="margin-bottom: 1rem;">
        <ul style="display: flex; list-style: none; padding: 0; margin: 0; gap: 0.5rem; color: #6b7280;">
            <li>Admin</li>
            <li>/</li>
            <li style="color: #000; font-weight: 600;">@yield('breadcrumb', 'Dashboard')</li>
        </ul>
    </nav>

    <!-- Page Title -->
    <div style="margin-bottom: 1.5rem;">
        <h1 class="title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="notification is-success">
            <button class="delete" onclick="this.parentElement.remove()"></button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="notification is-danger">
            <button class="delete" onclick="this.parentElement.remove()"></button>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="notification is-danger">
            <button class="delete" onclick="this.parentElement.remove()"></button>
            <strong>Validation Error!</strong>
            <ul style="margin-top: 0.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

<!-- Footer -->
<footer class="footer">
    <div>
        © {{ date('Y') }}, Gemini Pro Trader
    </div>
    <div>
        <strong>Admin Panel</strong> powered by Laravel
    </div>
</footer>

</div>

<!-- Admin One JS -->
<script src="{{ asset('admin-one-js/main.js') }}"></script>

<script>
function toggleDropdown(element) {
    const dropdown = element.closest('.dropdown');
    dropdown.classList.toggle('active');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });
}
</script>

@stack('scripts')

</body>
</html>