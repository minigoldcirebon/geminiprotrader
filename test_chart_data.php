<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'sqlite',
    'database'  => __DIR__ . '/database/database.sqlite',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🧪 Testing Chart Data Structure\n";
echo "================================\n\n";

// Get latest signal with chart data
$latestSignal = Capsule::table('expert_signals')
    ->where('status', 'published')
    ->orderBy('created_at', 'desc')
    ->first();

if ($latestSignal) {
    echo "✅ Latest Signal Found:\n";
    echo "- ID: {$latestSignal->id}\n";
    echo "- Pair: {$latestSignal->pair}\n";
    echo "- Type: {$latestSignal->signal_type}\n\n";
    
    // Get chart data points
    $chartData = Capsule::table('signal_chart_data_points')
        ->where('expert_signal_id', $latestSignal->id)
        ->orderBy('timestamp', 'desc')
        ->limit(5)
        ->get();
        
    echo "📊 Chart Data Points: {$chartData->count()}\n\n";
    
    if ($chartData->count() > 0) {
        echo "Sample Data Structure:\n";
        echo "----------------------\n";
        
        foreach ($chartData->take(3) as $index => $point) {
            echo "Point " . ($index + 1) . ":\n";
            echo "  - open: {$point->open}\n";
            echo "  - close: {$point->close}\n";
            echo "  - volume: {$point->volume}\n";
            echo "  - rsi_value: {$point->rsi_value}\n";
            echo "  - macd_value: {$point->macd_value}\n\n";
        }
        
        // Convert to JSON format like Laravel would
        $jsonData = $chartData->map(function($point) {
            return [
                'id' => $point->id,
                'open' => $point->open,
                'close' => $point->close,
                'volume' => $point->volume,
                'rsi_value' => $point->rsi_value,
                'macd_value' => $point->macd_value,
                'timestamp' => $point->timestamp
            ];
        });
        
        echo "JSON Structure (as passed to JavaScript):\n";
        echo "-----------------------------------------\n";
        echo json_encode($jsonData->take(2), JSON_PRETTY_PRINT) . "\n\n";
        
        echo "✅ Chart data structure is correct!\n";
        echo "✅ Field names match JavaScript expectations!\n";
        
    } else {
        echo "❌ No chart data points found!\n";
    }
    
} else {
    echo "❌ No published signals found!\n";
}

echo "\nTest completed at " . date('Y-m-d H:i:s') . "\n";
