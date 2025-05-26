<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpertSignal;
use App\Models\UserNotification;

class ExpertSignalController extends Controller
{    public function index(Request $request)    {
        // Get the latest signal with full chart data and technical indicators
        $latestSignal = ExpertSignal::with([
            'creator', 
            'approver', 
            'technicalIndicators',
            'chartDataPoints' => function($query) {
                $query->orderBy('timestamp', 'desc')->limit(50);
            }
        ])
            ->where('status', 'published')
            ->latest('published_at')
            ->first();

        // Get other signals without chart data (lighter loading)
        $query = ExpertSignal::with(['creator', 'approver', 'technicalIndicators'])
            ->where('status', 'published')
            ->latest('published_at');

        // Exclude the latest signal if it exists
        if ($latestSignal) {
            $query->where('id', '!=', $latestSignal->id);
        }

        // Apply filters
        if ($request->filled('pair')) {
            $query->where('pair', $request->pair);
        }

        if ($request->filled('signal_type')) {
            $query->where('signal_type', $request->signal_type);
        }

        if ($request->filled('confidence_level')) {
            $query->where('confidence_level', $request->confidence_level);
        }

        if ($request->filled('webhook_source')) {
            if ($request->webhook_source === 'manual') {
                $query->whereNull('webhook_source');
            } else {
                $query->where('webhook_source', $request->webhook_source);
            }
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->filled('signal_result')) {
            $query->where('signal_result', $request->signal_result);
        }

        if ($request->filled('timeframe')) {
            $query->where('timeframe', $request->timeframe);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        // Profit/Loss filter
        if ($request->filled('min_profit_loss')) {
            $query->where('profit_loss_percentage', '>=', $request->min_profit_loss);
        }

        if ($request->filled('max_profit_loss')) {
            $query->where('profit_loss_percentage', '<=', $request->max_profit_loss);
        }

        // Active signals only
        if ($request->filled('active_only') && $request->active_only) {
            $query->active();        }

        $otherSignals = $query->paginate(9); // Reduced to 9 since we have 1 latest signal

        // Get filter options
        $pairs = ExpertSignal::where('status', 'published')
            ->distinct()
            ->pluck('pair')
            ->filter()
            ->sort()
            ->values();

        $webhookSources = ExpertSignal::whereNotNull('webhook_source')
            ->distinct()
            ->pluck('webhook_source')
            ->filter()
            ->sort()
            ->values();

        $timeframes = ExpertSignal::where('status', 'published')
            ->whereNotNull('timeframe')
            ->distinct()
            ->pluck('timeframe')
            ->filter()
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total_signals' => ExpertSignal::where('status', 'published')->count(),
            'profitable_signals' => ExpertSignal::where('status', 'published')->where('signal_result', 'profit')->count(),
            'loss_signals' => ExpertSignal::where('status', 'published')->where('signal_result', 'loss')->count(),
            'pending_signals' => ExpertSignal::where('status', 'published')->whereNull('signal_result')->count(),
            'webhook_signals' => ExpertSignal::where('status', 'published')->whereNotNull('webhook_source')->count(),
            'avg_profit_loss' => ExpertSignal::where('status', 'published')->whereNotNull('profit_loss_percentage')->avg('profit_loss_percentage') ?? 0,
            'avg_strength' => ExpertSignal::where('status', 'published')->avg('overall_strength') ?? 0,
            'bullish_signals' => ExpertSignal::where('status', 'published')->where('current_trend', 'BULLISH')->count(),
            'bearish_signals' => ExpertSignal::where('status', 'published')->where('current_trend', 'BEARISH')->count(),
        ];

        return view('expert-signals.index', compact('latestSignal', 'otherSignals', 'pairs', 'webhookSources', 'timeframes', 'stats'));
    }

    public function show(ExpertSignal $expertSignal)
    {
        // Only show published signals to regular users
        if ($expertSignal->status !== 'published') {
            abort(404);
        }

        return view('expert-signals.show', compact('expertSignal'));
    }
}
