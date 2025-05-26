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

echo "<h2>🔍 Chart Data Debug Report</h2>";
echo "<hr>";

// Check Expert Signals
echo "<h3>📊 Expert Signals</h3>";
$signals = Capsule::table('expert_signals')->get();
echo "<p>Total Expert Signals: <strong>" . $signals->count() . "</strong></p>";

if ($signals->count() > 0) {
    $latestSignal = $signals->first();
    echo "<p>Latest Signal ID: <strong>{$latestSignal->id}</strong></p>";
    echo "<p>Latest Signal Pair: <strong>{$latestSignal->pair}</strong></p>";
    echo "<p>Latest Signal Type: <strong>{$latestSignal->signal_type}</strong></p>";
    
    // Check Chart Data Points
    echo "<h4>📈 Chart Data Points for Latest Signal</h4>";
    $chartData = Capsule::table('signal_chart_data_points')
        ->where('expert_signal_id', $latestSignal->id)
        ->get();
    
    echo "<p>Chart Data Points: <strong>" . $chartData->count() . "</strong></p>";
    
    if ($chartData->count() > 0) {
        echo "<h5>Sample Chart Data (First 3 points):</h5>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Open</th><th>Close</th><th>Volume</th><th>RSI</th><th>MACD</th><th>Created At</th></tr>";
        
        foreach ($chartData->take(3) as $point) {
            echo "<tr>";
            echo "<td>{$point->id}</td>";            echo "<td>{$point->open}</td>";
            echo "<td>{$point->close}</td>";
            echo "<td>{$point->volume}</td>";
            echo "<td>{$point->rsi_value}</td>";
            echo "<td>{$point->macd_value}</td>";
            echo "<td>{$point->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check for zeros
        $zeroCount = Capsule::table('signal_chart_data_points')
            ->where('expert_signal_id', $latestSignal->id)            ->where(function($query) {
                $query->where('open', 0)
                      ->orWhere('close', 0)
                      ->orWhere('volume', 0);
            })
            ->count();
        
        echo "<p>🚨 <strong>Zero Values Found:</strong> {$zeroCount} out of " . $chartData->count() . " points</p>";
        
        if ($zeroCount > 0) {
            echo "<p style='color: red;'>⚠️ This explains why charts show 0 values!</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ No chart data points found!</p>";
    }
    
    // Check Technical Indicators
    echo "<h4>⚙️ Technical Indicators for Latest Signal</h4>";
    $indicators = Capsule::table('signal_technical_indicators')
        ->where('expert_signal_id', $latestSignal->id)
        ->get();
    
    echo "<p>Technical Indicators: <strong>" . $indicators->count() . "</strong></p>";
    
    if ($indicators->count() > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Indicator</th><th>Value</th><th>Status</th><th>Strength</th></tr>";
        
        foreach ($indicators as $indicator) {
            echo "<tr>";
            echo "<td>{$indicator->indicator_name}</td>";
            echo "<td>{$indicator->value}</td>";
            echo "<td>{$indicator->status}</td>";
            echo "<td>{$indicator->strength}%</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} else {
    echo "<p style='color: red;'>❌ No expert signals found!</p>";
}

// Check the latest signal being used in the controller
echo "<hr>";
echo "<h3>🎯 Controller Logic Test</h3>";

$latestSignal = Capsule::table('expert_signals')
    ->orderBy('created_at', 'desc')
    ->first();

if ($latestSignal) {
    echo "<p>Latest Signal ID: <strong>{$latestSignal->id}</strong></p>";
    
    $chartDataPoints = Capsule::table('signal_chart_data_points')
        ->where('expert_signal_id', $latestSignal->id)
        ->orderBy('created_at', 'asc')
        ->limit(30)
        ->get();
    
    echo "<p>Chart Data Points for Controller: <strong>" . $chartDataPoints->count() . "</strong></p>";
    
    if ($chartDataPoints->count() > 0) {
        echo "<h5>Value Ranges:</h5>";
        echo "<ul>";        echo "<li>Open Price: " . $chartDataPoints->min('open') . " - " . $chartDataPoints->max('open') . "</li>";
        echo "<li>Close Price: " . $chartDataPoints->min('close') . " - " . $chartDataPoints->max('close') . "</li>";
        echo "<li>Volume: " . $chartDataPoints->min('volume') . " - " . $chartDataPoints->max('volume') . "</li>";
        echo "<li>RSI: " . $chartDataPoints->min('rsi_value') . " - " . $chartDataPoints->max('rsi_value') . "</li>";
        echo "</ul>";
    }
    
    $technicalIndicators = Capsule::table('signal_technical_indicators')
        ->where('expert_signal_id', $latestSignal->id)
        ->get();
    
    echo "<p>Technical Indicators for Controller: <strong>" . $technicalIndicators->count() . "</strong></p>";
}

echo "<hr>";
echo "<h3>✅ Summary</h3>";
echo "<p>This debug will help identify why charts show 0 values.</p>";
echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";

?>
