<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Trading Signals - Basic</title>
    <link rel="stylesheet" href="{{ asset('css/admin-one-charts.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="{{ asset('js/admin-one-charts.js') }}"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .signal-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(75, 85, 99, 0.4);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        .debug-info {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Expert Trading Signals</h1>
        
        <!-- Debug Information -->
        <div class="debug-info">
            <strong>Debug Info:</strong><br>
            Latest Signal: {{ $latestSignal ? $latestSignal->pair : 'None' }}<br>
            Other Signals Count: {{ $otherSignals ? $otherSignals->count() : 0 }}<br>
            Total Stats: {{ isset($stats) ? json_encode($stats) : 'None' }}<br>
            Chart Data Points: {{ $latestSignal && $latestSignal->chartDataPoints ? $latestSignal->chartDataPoints->count() : 0 }}
        </div>

        @if($latestSignal)
        <!-- Latest Signal -->
        <div class="signal-card">
            <h2>🔥 Latest Signal: {{ $latestSignal->pair }}</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <strong>Signal Type:</strong><br>
                    <span style="color: {{ $latestSignal->signal_type === 'BUY' ? '#10b981' : '#ef4444' }}">
                        {{ $latestSignal->signal_type }}
                    </span>
                </div>
                <div>
                    <strong>Entry Price:</strong><br>
                    ${{ number_format($latestSignal->entry_price, 5) }}
                </div>
                <div>
                    <strong>Confidence:</strong><br>
                    {{ $latestSignal->confidence_level ?? 'N/A' }}/5
                </div>
                <div>
                    <strong>Status:</strong><br>
                    {{ $latestSignal->status }}
                </div>
            </div>
            
            @if($latestSignal->chartDataPoints && $latestSignal->chartDataPoints->count() > 0)
            <div style="margin-top: 20px; background: rgba(15, 23, 42, 0.9); border-radius: 8px; padding: 15px;">
                <h3>Price Chart</h3>
                <canvas id="mainChart_{{ $latestSignal->id }}" style="height: 300px; width: 100%;"></canvas>
            </div>
            @else
            <div style="margin-top: 20px; background: rgba(15, 23, 42, 0.9); border-radius: 8px; padding: 15px; text-align: center;">
                <p>No chart data available for this signal</p>
            </div>
            @endif
        </div>
        @else
        <div class="signal-card">
            <h2>No Latest Signal Found</h2>
            <p>There are no published expert signals available at the moment.</p>
        </div>
        @endif

        @if($otherSignals && $otherSignals->count() > 0)
        <!-- Other Signals -->
        <h2>Previous Signals</h2>
        <div class="grid">
            @foreach($otherSignals as $signal)
            <div class="signal-card">
                <h3>{{ $signal->pair }}</h3>
                <div style="margin-top: 10px;">
                    <div style="margin-bottom: 8px;">
                        <strong>Type:</strong> 
                        <span style="color: {{ $signal->signal_type === 'BUY' ? '#10b981' : '#ef4444' }}">
                            {{ $signal->signal_type }}
                        </span>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Entry:</strong> ${{ number_format($signal->entry_price, 5) }}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Created:</strong> {{ $signal->created_at->format('M d, H:i') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div style="margin-top: 20px;">
            {{ $otherSignals->links() }}
        </div>
        @else
        <div class="signal-card">
            <h2>No Other Signals</h2>
            <p>No additional signals found.</p>
        </div>
        @endif
    </div>

    <script>
        console.log('Basic page loaded');
        
        // Simple chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking for charts...');
            
            @if($latestSignal && $latestSignal->chartDataPoints && $latestSignal->chartDataPoints->count() > 0)
            try {
                const chartData = @json($latestSignal->chartDataPoints->take(20)->values());
                console.log('Chart data:', chartData);
                
                if (chartData && chartData.length > 0) {
                    const ctx = document.getElementById('mainChart_{{ $latestSignal->id }}');
                    if (ctx) {
                        const chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData.map((_, i) => `Point ${i + 1}`),
                                datasets: [{
                                    label: '{{ $latestSignal->pair }}',
                                    data: chartData.map(item => item.close_price || item.price || Math.random() * 100),
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    tension: 0.1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: true }
                                }
                            }
                        });
                        console.log('Chart created successfully');
                    } else {
                        console.error('Chart canvas not found');
                    }
                } else {
                    console.warn('No valid chart data');
                }
            } catch (error) {
                console.error('Chart error:', error);
            }
            @else
            console.log('No chart data available');
            @endif
        });
    </script>
</body>
</html>
