<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ExpertSignal;

$signal = ExpertSignal::with('chartDataPoints')->latest()->first();

if ($signal) {
    echo "Signal ID: " . $signal->id . PHP_EOL;
    echo "Signal Pair: " . $signal->pair . PHP_EOL;
    echo "Chart Data Points: " . $signal->chartDataPoints->count() . PHP_EOL;
    
    if ($signal->chartDataPoints->count() > 0) {
        $firstPoint = $signal->chartDataPoints->first();
        echo "First Point Data:" . PHP_EOL;
        echo "- Timestamp: " . $firstPoint->timestamp . PHP_EOL;
        echo "- Close Price: " . $firstPoint->close_price . PHP_EOL;
        echo "- Volume: " . $firstPoint->volume . PHP_EOL;
        echo "- RSI: " . $firstPoint->rsi_value . PHP_EOL;
        echo "- MACD: " . $firstPoint->macd_value . PHP_EOL;
    }
} else {
    echo "No signals found" . PHP_EOL;
}
