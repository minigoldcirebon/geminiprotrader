<?php

namespace Database\Seeders;

use App\Models\ExpertSignal;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpertSignalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systemUser = User::first();
        
        $signals = [
            [
                'pair' => 'EURUSD',
                'signal_type' => 'BUY',
                'entry_price' => 1.0850,
                'take_profit' => 1.0920,
                'stop_loss' => 1.0800,
                'actual_close_price' => 1.0890,
                'profit_loss_percentage' => 3.69,
                'signal_result' => 'profit',
                'closed_at' => now()->subHours(2),
                'priority' => 'high',
                'confidence_level' => 4,
                'risk_level' => 'medium',
                'timeframe' => '1H',
                'analysis_reason' => 'Strong bullish momentum with RSI divergence',
                'status' => 'published',
                'created_by' => $systemUser->id,
                'published_at' => now()->subHours(6),
                'expires_at' => now()->addHours(18),
            ],
            [
                'pair' => 'GBPUSD',
                'signal_type' => 'SELL',
                'entry_price' => 1.2650,
                'take_profit' => 1.2580,
                'stop_loss' => 1.2700,
                'priority' => 'medium',
                'confidence_level' => 5,
                'risk_level' => 'low',
                'timeframe' => '4H',
                'analysis_reason' => 'Double top pattern with strong resistance',
                'status' => 'published',
                'created_by' => $systemUser->id,
                'published_at' => now()->subHours(3),
                'expires_at' => now()->addHours(21),
            ],
            [
                'pair' => 'USDJPY',
                'signal_type' => 'BUY',
                'entry_price' => 150.25,
                'take_profit' => 152.00,
                'stop_loss' => 148.50,
                'actual_close_price' => 147.80,
                'profit_loss_percentage' => -1.63,
                'signal_result' => 'loss',
                'closed_at' => now()->subHours(1),
                'priority' => 'low',
                'confidence_level' => 2,
                'risk_level' => 'high',
                'timeframe' => '1D',
                'analysis_reason' => 'Failed breakout above key resistance level',
                'status' => 'published',
                'created_by' => $systemUser->id,
                'published_at' => now()->subDays(1),
                'expires_at' => now()->subHours(1),
            ],
            [
                'pair' => 'AUDUSD',
                'signal_type' => 'SELL',
                'entry_price' => 0.6580,
                'take_profit' => 0.6520,
                'stop_loss' => 0.6620,
                'priority' => 'high',
                'confidence_level' => 4,
                'risk_level' => 'medium',
                'timeframe' => '2H',
                'analysis_reason' => 'Bearish engulfing pattern at resistance',
                'status' => 'published',
                'created_by' => $systemUser->id,
                'published_at' => now()->subMinutes(30),
                'expires_at' => now()->addHours(12),
                'webhook_source' => 'tradingview',
                'external_id' => 'TV_AUDUSD_' . time(),
            ],
            [
                'pair' => 'USDCAD',
                'signal_type' => 'HODL',
                'entry_price' => 1.3580,
                'take_profit' => 1.3650,
                'stop_loss' => 1.3520,
                'priority' => 'medium',
                'confidence_level' => 3,
                'risk_level' => 'medium',
                'timeframe' => '6H',
                'analysis_reason' => 'Consolidation phase, wait for breakout',
                'status' => 'published',
                'created_by' => $systemUser->id,
                'published_at' => now()->subHours(1),
                'expires_at' => now()->addHours(18),
                'webhook_source' => 'mt4',
                'external_id' => 'MT4_USDCAD_' . time(),
            ],
        ];

        foreach ($signals as $signal) {
            ExpertSignal::create($signal);
        }
    }
}
