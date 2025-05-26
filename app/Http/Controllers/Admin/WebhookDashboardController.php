<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertSignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookDashboardController extends Controller
{
    /**
     * Display webhook dashboard.
     */
    public function index()
    {
        // Overall statistics
        $totalWebhookSignals = ExpertSignal::fromWebhook()->count();
        $totalProfit = ExpertSignal::fromWebhook()->where('signal_result', 'profit')->count();
        $totalLoss = ExpertSignal::fromWebhook()->where('signal_result', 'loss')->count();
        $totalPending = ExpertSignal::fromWebhook()->whereNull('signal_result')->count();
        
        $winRate = ($totalProfit + $totalLoss) > 0 ? 
            round(($totalProfit / ($totalProfit + $totalLoss)) * 100, 2) : 0;
        
        $avgProfitLoss = ExpertSignal::fromWebhook()
            ->whereNotNull('profit_loss_percentage')
            ->avg('profit_loss_percentage') ?? 0;

        // Signals by source
        $signalsBySource = ExpertSignal::fromWebhook()
            ->select('webhook_source', DB::raw('COUNT(*) as total'))
            ->groupBy('webhook_source')
            ->get();

        // Performance by source
        $performanceBySource = ExpertSignal::fromWebhook()
            ->select(
                'webhook_source',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN signal_result = "profit" THEN 1 ELSE 0 END) as profits'),
                DB::raw('SUM(CASE WHEN signal_result = "loss" THEN 1 ELSE 0 END) as losses'),
                DB::raw('AVG(profit_loss_percentage) as avg_profit_loss')
            )
            ->whereNotNull('webhook_source')
            ->groupBy('webhook_source')
            ->get();

        // Recent signals
        $recentSignals = ExpertSignal::fromWebhook()
            ->with('creator')
            ->latest()
            ->take(20)
            ->get();

        // Daily signal count for last 30 days
        $dailySignals = ExpertSignal::fromWebhook()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing pairs
        $topPairs = ExpertSignal::fromWebhook()
            ->select(
                'pair',
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(profit_loss_percentage) as avg_profit_loss')
            )
            ->whereNotNull('profit_loss_percentage')
            ->groupBy('pair')
            ->orderByDesc('avg_profit_loss')
            ->take(10)
            ->get();

        return view('admin.webhook-dashboard', compact(
            'totalWebhookSignals',
            'totalProfit',
            'totalLoss',
            'totalPending',
            'winRate',
            'avgProfitLoss',
            'signalsBySource',
            'performanceBySource',
            'recentSignals',
            'dailySignals',
            'topPairs'
        ));
    }

    /**
     * Get webhook statistics as JSON.
     */
    public function stats()
    {
        $stats = [
            'total_signals' => ExpertSignal::fromWebhook()->count(),
            'profitable_signals' => ExpertSignal::fromWebhook()->where('signal_result', 'profit')->count(),
            'loss_signals' => ExpertSignal::fromWebhook()->where('signal_result', 'loss')->count(),
            'pending_signals' => ExpertSignal::fromWebhook()->whereNull('signal_result')->count(),
            'avg_profit_loss' => ExpertSignal::fromWebhook()->whereNotNull('profit_loss_percentage')->avg('profit_loss_percentage'),
            'sources' => ExpertSignal::fromWebhook()
                ->selectRaw('webhook_source, count(*) as count')
                ->groupBy('webhook_source')
                ->pluck('count', 'webhook_source'),
        ];

        return response()->json($stats);
    }
}
