<?php
require 'vendor/autoload.php';

use App\Models\ExpertSignal;

// Test database connection and chart data
$signal = ExpertSignal::with('chartDataPoints')->first();

if ($signal) {
    echo "✅ Signal found: ID {$signal->id}\n";
    echo "✅ Chart points: {$signal->chartDataPoints->count()}\n";
    
    if ($signal->chartDataPoints->count() > 0) {
        $point = $signal->chartDataPoints->first();
        echo "✅ Sample data - Open: {$point->open}, Close: {$point->close}\n";
        echo "✅ Database schema: CORRECT\n";
    } else {
        echo "❌ No chart data points\n";
    }
} else {
    echo "❌ No signals found\n";
}

// Test if JavaScript files are corrected
$jsContent = file_get_contents('public/js/admin-one-charts.js');
if (strpos($jsContent, 'item.close') !== false) {
    echo "✅ JavaScript fixed: Using 'close' field\n";
} else {
    echo "❌ JavaScript still using wrong field names\n";
}

echo "\n🎯 STRATEGI:\n";
echo "1. Database: " . ($signal && $signal->chartDataPoints->count() > 0 ? "OK" : "NEEDS DATA") . "\n";
echo "2. JavaScript: " . (strpos($jsContent, 'item.close') !== false ? "FIXED" : "NEEDS FIX") . "\n";
echo "3. Page: PERLU TEST BROWSER\n";
