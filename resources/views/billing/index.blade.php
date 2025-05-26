@extends('layouts.admin-one-fixed')

@section('title', 'Billing & Subscriptions')
@section('page-title', 'Billing & Subscriptions')
@section('breadcrumb', 'Billing')

@section('content')

    <div class="columns">
        <!-- Current Subscription -->
        <div class="column" style="flex: 2;">
            <div class="card mb-6">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon"><i class="mdi mdi-credit-card"></i></span>
                        Current Subscription
                    </p>
                </header>
                <div class="card-content">
                            
                    @if($activeSubscription)
                        <div class="notification is-success">
                            <div class="level">
                                <div class="level-left">
                                    <div class="level-item">
                                        <div>
                                            <h4 class="title is-5">{{ $activeSubscription->subscriptionPlan->name }}</h4>
                                            <p class="subtitle is-6">Active Subscription</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="level-right">
                                    <div class="level-item has-text-right">
                                        <div>
                                            <div class="title is-3">${{ number_format($activeSubscription->subscriptionPlan->price, 2) }}</div>
                                            <div class="subtitle is-6">per {{ $activeSubscription->subscriptionPlan->billing_cycle }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label is-small">Started:</label>
                                        <div class="content">{{ $activeSubscription->starts_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label is-small">Next Billing:</label>
                                        <div class="content">{{ $activeSubscription->ends_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label is-small">Status:</label>
                                        <span class="tag is-success">
                                            {{ ucfirst($activeSubscription->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label is-small">Auto-renew:</label>
                                        <div class="content">{{ $activeSubscription->auto_renew ? 'Yes' : 'No' }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="content">
                                <h5 class="title is-6">Plan Features:</h5>
                                <div class="columns is-multiline">
                                    @foreach($activeSubscription->subscriptionPlan->features as $feature)
                                        <div class="column is-6">
                                            <div class="field">
                                                <span class="icon-text">
                                                    <span class="icon has-text-success">
                                                        <i class="mdi mdi-check"></i>
                                                    </span>
                                                    <span>{{ $feature }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="field">
                                <form action="{{ route('billing.cancel-subscription') }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="button is-danger"
                                            onclick="return confirm('Are you sure you want to cancel your subscription?')">
                                        <span class="icon"><i class="mdi mdi-cancel"></i></span>
                                        <span>Cancel Subscription</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="notification is-warning has-text-centered">
                            <div class="content">
                                <span class="icon is-large has-text-warning">
                                    <i class="mdi mdi-currency-usd mdi-48px"></i>
                                </span>
                                <h4 class="title is-5">No Active Subscription</h4>
                                <p>Choose a subscription plan to access premium features and trading bots.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon"><i class="mdi mdi-history"></i></span>
                        Recent Payments
                    </p>
                </header>
                <div class="card-content">
                            
                    @if($recentPayments->count() > 0)
                        <div class="table-container">
                            <table class="table is-fullwidth is-striped is-hoverable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
                                            <td><span class="tag">{{ strtoupper($payment->currency) }}</span></td>
                                            <td>
                                                <span class="tag 
                                                    @if($payment->status === 'completed') is-success
                                                    @elseif($payment->status === 'pending') is-warning
                                                    @elseif($payment->status === 'failed') is-danger
                                                    @else is-light
                                                    @endif">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment->status === 'completed')
                                                    <a href="{{ route('billing.download-invoice', $payment) }}" 
                                                       class="button is-small is-primary is-outlined">
                                                        <span class="icon"><i class="mdi mdi-download"></i></span>
                                                        <span>Invoice</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="content">
                            <a href="{{ route('billing.history') }}" class="button is-primary is-outlined">
                                <span class="icon"><i class="mdi mdi-history"></i></span>
                                <span>View All Payment History</span>
                            </a>
                        </div>
                    @else
                        <div class="notification is-info has-text-centered">
                            <div class="content">
                                <span class="icon is-large has-text-info">
                                    <i class="mdi mdi-file-document-outline mdi-48px"></i>
                                </span>
                                <p>No payment history found.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subscription Plans -->
        <div class="column" style="flex: 1;">
            <div class="card">
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600;">
                    <span class="icon"><i class="mdi mdi-package-variant"></i></span>
                    Available Plans
                </div>
                <div style="padding: 1.5rem;">
                    @foreach($subscriptionPlans as $plan)
                        <div class="card" style="margin-bottom: 1rem; @if($activeSubscription && $activeSubscription->subscription_plan_id === $plan->id) background-color: #f0fdf4; @endif">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h4 style="font-size: 1.125rem; font-weight: 600; margin: 0;">{{ $plan->name }}</h4>
                                @if($activeSubscription && $activeSubscription->subscription_plan_id === $plan->id)
                                    <span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">Current</span>
                                @endif
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <div style="font-size: 1.875rem; font-weight: 600;">${{ number_format($plan->price, 2) }}</div>
                                <div style="color: #6b7280;">per {{ $plan->billing_cycle }}</div>
                            </div>
                            
                            <p style="margin-bottom: 1rem;">{{ $plan->description }}</p>
                            
                            @if($plan->features)
                                <div style="margin-bottom: 1rem;">
                                    @foreach(array_slice($plan->features, 0, 3) as $feature)
                                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                            <span style="color: #10b981;">
                                                <i class="mdi mdi-check"></i>
                                            </span>
                                            <span>{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                    @if(count($plan->features) > 3)
                                        <p style="color: #6b7280; font-size: 0.875rem;">
                                            +{{ count($plan->features) - 3 }} more features
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if(!$activeSubscription || $activeSubscription->subscription_plan_id !== $plan->id)
                                <form action="{{ route('billing.subscribe') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <div class="field">
                                        <label class="label">Payment Currency</label>
                                        <div class="control">
                                            <select name="pay_currency" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                                                <option value="btc">Bitcoin (BTC)</option>
                                                <option value="eth">Ethereum (ETH)</option>
                                                <option value="usdt">Tether (USDT)</option>
                                                <option value="ltc">Litecoin (LTC)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="button" style="width: 100%;">
                                        <span class="icon"><i class="mdi mdi-credit-card"></i></span>
                                        <span>Subscribe Now</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection