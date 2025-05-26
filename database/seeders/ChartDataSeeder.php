<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpertSignal;
use App\Models\SignalChartDataPoint;
use App\Models\SignalTechnicalIndicator;
use Carbon\Carbon;

class ChartDataSeeder extends Seeder
{
    public function run()
    {
        $signals = ExpertSignal::where('status', 'published')->get();
        
        foreach ($signals as $signal) {
            // Create chart data points (50 points for last 50 hours)
            for ($i = 49; $i >= 0; $i--) {
                $timestamp = Carbon::now()->subHours($i);
                $basePrice = 1.2000; // EUR/USD base price
                $volatility = 0.001; // 100 pips volatility
                
                // Generate realistic OHLC data
                $open = $basePrice + (mt_rand(-100, 100) * $volatility);
                $change = mt_rand(-50, 50) * ($volatility / 10);
                $close = $open + $change;
                $high = max($open, $close) + (mt_rand(0, 20) * ($volatility / 20));
                $low = min($open, $close) - (mt_rand(0, 20) * ($volatility / 20));
                
                // Generate RSI (0-100)
                $rsi = mt_rand(20, 80);
                
                // Generate MACD
                $macd = (mt_rand(-100, 100) / 10000);
                $macdSignal = $macd + (mt_rand(-50, 50) / 20000);
                
                SignalChartDataPoint::create([
                    'expert_signal_id' => $signal->id,
                    'timeframe' => $signal->timeframe ?? 'H1',
                    'timestamp' => $timestamp,
                    'open' => $open,
                    'high' => $high,
                    'low' => $low,
                    'close' => $close,
                    'volume' => mt_rand(1000, 50000),
                    'rsi_value' => $rsi,
                    'macd_value' => $macd,
                    'macd_signal' => $macdSignal
                ]);
            }
            
            // Create technical indicators
            $indicators = [
                ['name' => 'RSI', 'value' => mt_rand(30, 70), 'status' => 'bullish'],
                ['name' => 'MACD', 'value' => mt_rand(-0.01, 0.01), 'status' => 'bullish'],
                ['name' => 'Moving Average', 'value' => 1.2000 + (mt_rand(-100, 100) * 0.0001), 'status' => 'bullish'],
                ['name' => 'Bollinger Bands', 'value' => 0.85, 'status' => 'neutral'],
                ['name' => 'Stochastic', 'value' => mt_rand(20, 80), 'status' => 'bearish']
            ];
            
            foreach ($indicators as $indicator) {
                SignalTechnicalIndicator::create([
                    'expert_signal_id' => $signal->id,
                    'indicator_name' => $indicator['name'],
                    'value' => $indicator['value'],
                    'signal_line_value' => $indicator['value'] * 1.05,
                    'status' => $indicator['status'],
                    'strength' => mt_rand(60, 95)
                ]);
            }
        }
    }
}
