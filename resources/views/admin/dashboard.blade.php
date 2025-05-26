@extends('layouts.admin-one-fixed')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<!-- Key Metrics -->
<div class="columns" style="margin-bottom: 1.5rem;">
    <!-- Total Users -->
    <div class="column">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Users</h3>
                    <h1 style="font-size: 2rem; font-weight: 600; margin: 0;">{{ $totalUsers ?? 0 }}</h1>
                </div>
                <span style="color: #10b981; font-size: 3rem;"><i class="mdi mdi-account-multiple"></i></span>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions -->
    <div class="column">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Active Subscriptions</h3>
                    <h1 style="font-size: 2rem; font-weight: 600; margin: 0;">{{ $activeSubscriptions ?? 0 }}</h1>
                </div>
                <span style="color: #3b82f6; font-size: 3rem;"><i class="mdi mdi-check-circle"></i></span>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="column">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Revenue</h3>
                    <h1 style="font-size: 2rem; font-weight: 600; margin: 0;">${{ number_format($totalRevenue ?? 0, 2) }}</h1>
                </div>
                <span style="color: #f59e0b; font-size: 3rem;"><i class="mdi mdi-currency-usd"></i></span>
            </div>
        </div>
    </div>

    <!-- Pending Signals -->
    <div class="column">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Pending Signals</h3>
                    <h1 style="font-size: 2rem; font-weight: 600; margin: 0;">{{ $pendingSignals ?? 0 }}</h1>
                </div>
                <span style="color: #ef4444; font-size: 3rem;"><i class="mdi mdi-alert"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Activity -->
<div class="columns" style="margin-bottom: 1.5rem;">
    <!-- Recent Registrations Chart -->
    <div class="column">
        <div class="card">
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">
                <span class="icon"><i class="mdi mdi-account-plus"></i></span>
                Recent Registrations
            </div>
            <div style="text-align: center; padding: 2rem;">
                <h1 style="font-size: 2.5rem; font-weight: 600; color: #3b82f6; margin-bottom: 0.5rem;">{{ $recentRegistrations ?? 0 }}</h1>
                <h3 style="color: #6b7280; margin: 0;">New users in last 30 days</h3>
            </div>
        </div>
    </div>

    <!-- Revenue Trend -->
    <div class="column">
        <div class="card">
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">
                <span class="icon"><i class="mdi mdi-trending-up"></i></span>
                Revenue Trend
            </div>
            <div style="text-align: center; padding: 2rem;">
                @if(isset($monthlyRevenue) && is_array($monthlyRevenue) && count($monthlyRevenue) > 0)
                    <h1 style="font-size: 2.5rem; font-weight: 600; color: #10b981; margin-bottom: 0.5rem;">${{ number_format(end($monthlyRevenue)['revenue'] ?? 0, 2) }}</h1>
                    <h3 style="color: #6b7280; margin: 0;">{{ end($monthlyRevenue)['month'] ?? 'This month' }}</h3>
                @else
                    <h1 style="font-size: 2.5rem; font-weight: 600; color: #10b981; margin-bottom: 0.5rem;">$0.00</h1>
                    <h3 style="color: #6b7280; margin: 0;">This month</h3>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">
        <span class="icon"><i class="mdi mdi-history"></i></span>
        Recent Activity
    </div>
    <div style="padding: 1.5rem;">
        @if(isset($recentActivity) && count($recentActivity) > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($recentActivity as $activity)
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div>
                            <span style="color: #10b981;">
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                        </div>
                        <div style="flex: 1;">
                            <p style="font-size: 0.875rem; margin-bottom: 0.25rem;">{{ $activity['description'] ?? 'Activity' }}</p>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">{{ $activity['created_at'] ?? now() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem;">
                <span style="color: #d1d5db; font-size: 3rem;">
                    <i class="mdi mdi-clipboard-text-outline"></i>
                </span>
                <h3 style="color: #6b7280; margin: 1rem 0 0.5rem 0;">No recent activity</h3>
                <p style="color: #6b7280; margin: 0;">Activity will appear here as users interact with the platform.</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">
        <span class="icon"><i class="mdi mdi-lightning-bolt"></i></span>
        Quick Actions
    </div>
    <div style="padding: 1.5rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <a href="#" class="button" style="background: #3b82f6;">
                <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
                <span>Manage Users</span>
            </a>
            
            <a href="#" class="button" style="background: #10b981;">
                <span class="icon"><i class="mdi mdi-currency-usd"></i></span>
                <span>Financial Reports</span>
            </a>
            
            <a href="#" class="button" style="background: #f59e0b;">
                <span class="icon"><i class="mdi mdi-bell"></i></span>
                <span>Send Notifications</span>
            </a>
            
            <a href="#" class="button" style="background: #6b7280;">
                <span class="icon"><i class="mdi mdi-cog"></i></span>
                <span>System Settings</span>
            </a>
        </div>
    </div>
</div>
@endsection