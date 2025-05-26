<?php

namespace App\Console\Commands;

use App\Models\ExpertSignal;
use Illuminate\Console\Command;

class WebhookStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:stats {--source= : Filter by webhook source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display webhook signal statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        
        $this->info('=== Webhook Signal Statistics ===');
        $this->newLine();

        // Total webhook signals
        $totalWebhookSignals = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->count();

        // Profitable signals
        $profitableSignals = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->where('signal_result', 'profit')
            ->count();

        // Loss signals
        $lossSignals = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->where('signal_result', 'loss')
            ->count();

        // Pending signals
        $pendingSignals = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->whereNull('signal_result')
            ->count();

        // Win rate
        $closedSignals = $profitableSignals + $lossSignals;
        $winRate = $closedSignals > 0 ? round(($profitableSignals / $closedSignals) * 100, 2) : 0;

        // Average profit/loss
        $avgProfitLoss = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->whereNotNull('profit_loss_percentage')
            ->avg('profit_loss_percentage');

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Webhook Signals', $totalWebhookSignals],
                ['Profitable Signals', $profitableSignals],
                ['Loss Signals', $lossSignals],
                ['Pending Signals', $pendingSignals],
                ['Win Rate', $winRate . '%'],
                ['Average P/L', round($avgProfitLoss, 2) . '%'],
            ]
        );

        $this->newLine();

        // Signals by source
        if (!$source) {
            $this->info('=== Signals by Source ===');
            $sourceStats = ExpertSignal::fromWebhook()
                ->selectRaw('webhook_source, count(*) as count')
                ->groupBy('webhook_source')
                ->get();

            $sourceData = [];
            foreach ($sourceStats as $stat) {
                $sourceData[] = [$stat->webhook_source, $stat->count];
            }

            $this->table(['Source', 'Count'], $sourceData);
            $this->newLine();
        }

        // Recent signals
        $this->info('=== Recent Webhook Signals ===');
        $recentSignals = ExpertSignal::fromWebhook()
            ->when($source, function($query, $source) {
                return $query->where('webhook_source', $source);
            })
            ->latest()
            ->take(10)
            ->get(['id', 'pair', 'signal_type', 'webhook_source', 'signal_result', 'profit_loss_percentage', 'created_at']);

        $recentData = [];
        foreach ($recentSignals as $signal) {
            $recentData[] = [
                $signal->id,
                $signal->pair,
                $signal->signal_type,
                $signal->webhook_source,
                $signal->signal_result ?? 'pending',
                $signal->profit_loss_percentage ? round($signal->profit_loss_percentage, 2) . '%' : '-',
                $signal->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table(
            ['ID', 'Pair', 'Type', 'Source', 'Result', 'P/L %', 'Created At'],
            $recentData
        );

        return 0;
    }
}
