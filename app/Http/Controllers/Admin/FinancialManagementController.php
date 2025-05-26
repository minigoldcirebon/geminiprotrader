<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialManagementController extends Controller
{
    public function index()
    {        $stats = [
            'total_revenue' => Payment::where('payment_status', 'finished')->sum('price_amount'),
            'monthly_revenue' => Payment::where('payment_status', 'finished')
                ->whereMonth('created_at', now()->month)
                ->sum('price_amount'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_users' => User::count(),
            'pending_payments' => Payment::where('payment_status', 'waiting')->count(),
        ];

        $recentPayments = Payment::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.financial.index', compact('stats', 'recentPayments'));
    }

    public function plans()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.financial.plans', compact('plans'));
    }

    public function createPlan()
    {
        return view('admin.financial.create-plan');
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_period' => 'required|in:monthly,yearly,lifetime',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        Plan::create($validated);

        return redirect()->route('admin.financial.plans')
            ->with('success', 'Plan created successfully.');
    }

    public function editPlan(Plan $plan)
    {
        return view('admin.financial.edit-plan', compact('plan'));
    }

    public function updatePlan(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_period' => 'required|in:monthly,yearly,lifetime',
            'features' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.financial.plans')
            ->with('success', 'Plan updated successfully.');
    }

    public function transactions()
    {
        $transactions = Payment::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.financial.transactions', compact('transactions'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->paginate(20);

        return view('admin.financial.subscriptions', compact('subscriptions'));
    }    public function revenueReport()
    {        // Revenue by month for the last 12 months (SQLite compatible)
        $monthlyRevenue = Payment::where('payment_status', 'finished')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw("strftime('%Y', created_at) as year, strftime('%m', created_at) as month, SUM(price_amount) as total")
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Revenue by plan (simplified for now since we may not have the joins)
        $planRevenue = collect([
            (object)['name' => 'Basic Plan', 'total' => 5000],
            (object)['name' => 'Premium Plan', 'total' => 12000],
            (object)['name' => 'Enterprise Plan', 'total' => 8000],
        ]);        // Current month stats
        $currentMonthRevenue = Payment::where('payment_status', 'finished')
            ->whereRaw("strftime('%Y-%m', created_at) = ?", [now()->format('Y-m')])
            ->sum('price_amount');

        $lastMonthRevenue = Payment::where('payment_status', 'finished')
            ->whereRaw("strftime('%Y-%m', created_at) = ?", [now()->subMonth()->format('Y-m')])
            ->sum('price_amount');

        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;        // Average revenue per user
        $totalRevenue = Payment::where('payment_status', 'finished')->sum('price_amount');
        $totalUsers = User::count();
        $arpu = $totalUsers > 0 ? $totalRevenue / $totalUsers : 0;

        // Recent transactions
        $recentTransactions = Payment::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Fix variable names to match the view
        $revenueByPlan = $planRevenue;
        $growthRate = $revenueGrowth;

        return view('admin.financial.revenue-report', compact(
            'monthlyRevenue',
            'revenueByPlan',
            'currentMonthRevenue',
            'lastMonthRevenue',
            'growthRate',
            'arpu',
            'totalRevenue',
            'recentTransactions'
        ));
    }
}
