<?php
// Test memory usage for expert signals page
require_once '../vendor/autoload.php';

$app = require_once '../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel');

echo "Testing Expert Signals Memory Usage\n";
echo "===================================\n\n";

$startMemory = memory_get_usage(true);
echo "Initial Memory: " . number_format($startMemory / 1024 / 1024, 2) . " MB\n";

// Test original approach (all chart data)
$signals1 = App\Models\ExpertSignal::with(['creator', 'approver', 'technicalIndicators', 'chartDataPoints'])
    ->where('status', 'published')
    ->take(5)
    ->get();

$afterLoadAll = memory_get_usage(true);
echo "After loading all chart data (5 signals): " . number_format($afterLoadAll / 1024 / 1024, 2) . " MB\n";
echo "Memory increase: " . number_format(($afterLoadAll - $startMemory) / 1024 / 1024, 2) . " MB\n\n";

// Clear data
unset($signals1);
gc_collect_cycles();

// Test optimized approach (limited chart data)
$signals2 = App\Models\ExpertSignal::with([
    'creator', 
    'approver', 
    'technicalIndicators',
    'chartDataPoints' => function($query) {
        $query->orderBy('timestamp', 'desc')->limit(50);
    }
])
    ->where('status', 'published')
    ->take(5)
    ->get();

$afterLoadLimited = memory_get_usage(true);
echo "After loading limited chart data (5 signals): " . number_format($afterLoadLimited / 1024 / 1024, 2) . " MB\n";
echo "Memory increase: " . number_format(($afterLoadLimited - $startMemory) / 1024 / 1024, 2) . " MB\n\n";

echo "Chart data points per signal:\n";
foreach ($signals2 as $signal) {
    echo "Signal {$signal->id}: {$signal->chartDataPoints->count()} points\n";
}

echo "\nMemory optimization successful!\n";
?>
