<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpertSignal;
use App\Models\SignalTechnicalIndicator;
use App\Models\SignalChartDataPoint;
use Carbon\Carbon;

class SignalTechnicalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing expert signals with new fields
        $signals = ExpertSignal::all();
        
        foreach ($signals as $signal) {
            // Update signal with enhanced data
            $signal->update([
                'overall_strength' => rand(30, 95),
                'current_trend' => ['BEARISH', 'BULLISH', 'NEUTRAL'][array_rand(['BEARISH', 'BULLISH', 'NEUTRAL'])],
                'analysis_summary' => $this->generateAnalysisSummary($signal->signal_type),
                'provider_name' => ['Artruristltk', 'Test-provider', 'ProSignal', 'TradeMaster'][array_rand(['Artruristltk', 'Test-provider', 'ProSignal', 'TradeMaster'])],
                'chart_symbol' => $signal->pair ?: 'XAUUSD',
                'current_price' => $signal->entry_price + (rand(-100, 100) / 10000),
                'market_status' => rand(0, 1) ? 'OPEN' : 'CLOSED'
            ]);

            // Create technical indicators
            $this->createTechnicalIndicators($signal);
            
            // Create chart data points (only for first few signals to avoid too much data)
            if ($signal->id <= 3) {
                $this->createChartDataPoints($signal);
            }
        }
    }

    private function generateAnalysisSummary($signalType): string
    {
        $summaries = [
            'BUY' => [
                'Harga berpotensi naik berdasarkan analisis teknikal yang menunjukkan momentum bullish kuat.',
                'Indikator teknikal menunjukkan sinyal beli yang kuat dengan potensi kenaikan harga jangka pendek.',
                'Momentum bullish terdeteksi dengan volume yang meningkat, memberikan peluang entry yang baik.',
            ],
            'SELL' => [
                'Tekanan jual terlihat dari indikator teknikal dengan potensi penurunan harga.',
                'Sinyal bearish kuat terdeteksi dengan momentum penurunan yang berkelanjutan.',
                'Analisis menunjukkan pelemahan harga dengan target penurunan yang jelas.',
            ]
        ];

        $typeArr = $summaries[$signalType] ?? $summaries['BUY'];
        return $typeArr[array_rand($typeArr)];
    }

    private function createTechnicalIndicators(ExpertSignal $signal): void
    {
        $indicators = [
            ['name' => 'RSI', 'min' => 20, 'max' => 80],
            ['name' => 'STOCH', 'min' => 15, 'max' => 85],
            ['name' => 'CCI', 'min' => -200, 'max' => 200],
            ['name' => 'ADX', 'min' => 20, 'max' => 60],
            ['name' => 'MACD', 'min' => -0.5, 'max' => 0.5],
            ['name' => 'WILLIAMS', 'min' => -80, 'max' => -20],
        ];

        foreach ($indicators as $indicator) {
            $value = rand($indicator['min'] * 100, $indicator['max'] * 100) / 100;
            $signalLineValue = $indicator['name'] === 'MACD' ? $value + (rand(-20, 20) / 100) : null;
            
            // Determine status based on indicator type and value
            $status = $this->determineIndicatorStatus($indicator['name'], $value);
            $strength = $this->calculateIndicatorStrength($indicator['name'], $value, $indicator['min'], $indicator['max']);

            SignalTechnicalIndicator::create([
                'expert_signal_id' => $signal->id,
                'indicator_name' => $indicator['name'],
                'value' => $value,
                'signal_line_value' => $signalLineValue,
                'status' => $status,
                'strength' => $strength
            ]);
        }
    }

    private function determineIndicatorStatus(string $name, float $value): string
    {
        switch ($name) {
            case 'RSI':
                if ($value > 70) return 'bearish';
                if ($value < 30) return 'bullish';
                return 'neutral';
            case 'STOCH':
                if ($value > 80) return 'bearish';
                if ($value < 20) return 'bullish';
                return 'neutral';
            case 'CCI':
                if ($value > 100) return 'bullish';
                if ($value < -100) return 'bearish';
                return 'neutral';
            case 'ADX':
                if ($value > 40) return 'bullish';
                if ($value < 25) return 'bearish';
                return 'neutral';
            case 'MACD':
                return $value > 0 ? 'bullish' : 'bearish';
            case 'WILLIAMS':
                if ($value > -20) return 'bearish';
                if ($value < -80) return 'bullish';
                return 'neutral';
            default:
                return 'neutral';
        }
    }

    private function calculateIndicatorStrength(string $name, float $value, float $min, float $max): int
    {
        // Normalize value to 0-100 scale
        $normalized = (($value - $min) / ($max - $min)) * 100;
        
        // Adjust based on indicator characteristics
        switch ($name) {
            case 'RSI':
            case 'STOCH':
                // Extreme values indicate stronger signals
                if ($value > 70 || $value < 30) {
                    return min(100, $normalized + 20);
                }
                break;
            case 'ADX':
                // Higher ADX = stronger trend
                return min(100, $normalized);
            case 'MACD':
                // Absolute value indicates strength
                return min(100, abs($value) * 100);
        }

        return max(10, min(90, $normalized));
    }

    private function createChartDataPoints(ExpertSignal $signal): void
    {
        $timeframes = ['M5', 'M15', 'M30', 'H1'];
        $basePrice = $signal->entry_price ?: 2000.0;
        
        foreach ($timeframes as $timeframe) {
            $dataPoints = $this->generateOHLCData($basePrice, 50); // 50 data points per timeframe
            
            foreach ($dataPoints as $index => $point) {
                SignalChartDataPoint::create([
                    'expert_signal_id' => $signal->id,
                    'timeframe' => $timeframe,
                    'timestamp' => Carbon::now()->subMinutes(50 - $index),
                    'open' => $point['open'],
                    'high' => $point['high'],
                    'low' => $point['low'],
                    'close' => $point['close'],
                    'volume' => rand(1000, 10000),
                    'rsi_value' => rand(20, 80),
                    'macd_value' => (rand(-50, 50) / 100),
                    'macd_signal' => (rand(-45, 45) / 100)
                ]);
            }
        }
    }

    private function generateOHLCData(float $basePrice, int $count): array
    {
        $data = [];
        $currentPrice = $basePrice;
        
        for ($i = 0; $i < $count; $i++) {
            $volatility = 0.001; // 0.1% volatility
            $change = (rand(-100, 100) / 100) * $volatility * $currentPrice;
            
            $open = $currentPrice;
            $close = $currentPrice + $change;
            $high = max($open, $close) + (rand(0, 50) / 10000) * $currentPrice;
            $low = min($open, $close) - (rand(0, 50) / 10000) * $currentPrice;
            
            $data[] = [
                'open' => round($open, 5),
                'high' => round($high, 5),
                'low' => round($low, 5),
                'close' => round($close, 5)
            ];
            
            $currentPrice = $close;
        }
        
        return $data;
    }
}
