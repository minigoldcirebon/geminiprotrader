@extends('layouts.admin-one')

@section('title', 'Expert Trading Signals')
@section('page-title', 'Expert Signals')
@section('page-heading', 'Expert Trading Signals')

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/admin-one-charts.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="{{ asset('js/admin-one-charts.js') }}"></script>
    @endpush
      <style>
        .dark-bg { 
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); 
            min-height: 100vh;
        }
        .signal-card { 
            background: rgba(30, 41, 59, 0.95); 
            backdrop-filter: blur(10px); 
        }
        .indicator-bar { transition: all 0.3s ease; }

        /* Enhanced Layout and Typography */
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #fff;
            font-size: 2.25rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }        /* Fixed Chart Layout */
        .chart-section {
            margin-bottom: 2rem;
        }

        .chart-card {
            background: rgba(30, 41, 59, 0.98);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .chart-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(75, 85, 99, 0.4);
        }

        .chart-card-title {
            color: #fff;
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Enhanced Fixed Chart Area */
        .chart-area {
            background: rgba(15, 23, 42, 0.9);
            border-radius: 0.75rem;
            padding: 1rem;
            position: relative;
            height: 500px; /* Increased fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-area canvas {
            border-radius: 0.5rem;
            height: 450px !important; /* Strictly enforce height */
            width: 100% !important;
            max-height: 450px !important;
            max-width: 100% !important;
        }

        /* Enhanced Sub-charts Grid */
        .sub-charts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Fixed 3 columns */
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .sub-chart-container {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 0.75rem;
            padding: 1.25rem;
            height: 280px; /* Increased fixed height */
            display: flex;
            flex-direction: column;
        }

        .sub-chart-title {
            color: #e2e8f0;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .sub-chart-canvas-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .sub-chart-container canvas {
            height: 200px !important; /* Strictly enforce height */
            width: 100% !important;
            max-height: 200px !important;
            max-width: 100% !important;
        }

        /* Enhanced Signal Info Grid */
        .signal-info-section {
            margin-top: 2rem;
        }

        .info-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }

        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1200px) {
            .info-grid {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }

        /* Enhanced Signal Card */
        .main-signal-card {
            background: rgba(30, 41, 59, 0.98);
            border: 1px solid rgba(75, 85, 99, 0.4);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Enhanced Trading Stats */
        .trading-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            margin: 0.5rem 0;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-icon {
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }

        /* Chart Actions */
        .chart-card-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .timeframe-buttons {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }

        .timeframe-btn {
            background: rgba(75, 85, 99, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 0.375rem;
            color: #e2e8f0;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
            min-width: 2.5rem;
            text-align: center;
        }

        .timeframe-btn:hover,
        .timeframe-btn.active {
            background: rgba(59, 130, 246, 0.7);
            border-color: rgba(59, 130, 246, 0.8);
            color: #fff;
        }

        .chart-refresh-btn {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.375rem;
            color: #3b82f6;
            cursor: pointer;
            padding: 0.5rem;
            transition: all 0.2s ease;
        }

        .chart-refresh-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: translateY(-1px);
        }        /* Enhanced Responsive Improvements */
        @media (max-width: 1200px) {
            .sub-charts-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .chart-card {
                padding: 1rem;
            }
            
            .chart-area {
                height: 400px;
                padding: 0.75rem;
            }
            
            .chart-area canvas {
                height: 350px !important;
                max-height: 350px !important;
            }
            
            .sub-charts-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .sub-chart-container {
                height: 250px;
                padding: 1rem;
            }
            
            .sub-chart-container canvas {
                height: 180px !important;
                max-height: 180px !important;
            }
            
            .trading-stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .timeframe-buttons {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            .timeframe-btn {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
                min-width: 2rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.875rem;
            }
            
            .chart-area {
                height: 350px;
                padding: 0.5rem;
            }
            
            .chart-area canvas {
                height: 300px !important;
                max-height: 300px !important;
            }
            
            .sub-chart-container {
                height: 220px;
                padding: 0.75rem;
            }
            
            .sub-chart-container canvas {
                height: 150px !important;
                max-height: 150px !important;
            }

            .trading-stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

@section('content')
<!-- Statistics Summary -->
<div class="grid gap-6 grid-cols-1 md:grid-cols-4 mb-6">
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Total Signals</h3>
                    <h1>{{ $stats['total_signals'] ?? 0 }}</h1>
                </div>
                <span class="icon widget-icon text-blue-500"><i class="mdi mdi-chart-line mdi-48px"></i></span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Profitable</h3>
                    <h1>{{ $stats['profitable_signals'] ?? 0 }}</h1>
                </div>
                <span class="icon widget-icon text-green-500"><i class="mdi mdi-trending-up mdi-48px"></i></span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Loss Signals</h3>
                    <h1>{{ $stats['loss_signals'] ?? 0 }}</h1>
                </div>
                <span class="icon widget-icon text-red-500"><i class="mdi mdi-trending-down mdi-48px"></i></span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Pending</h3>
                    <h1>{{ $stats['pending_signals'] ?? 0 }}</h1>
                </div>
                <span class="icon widget-icon text-yellow-500"><i class="mdi mdi-clock mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>
@if($latestSignal)
<!-- Latest Signal Performance Stats -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-signal"></i></span>
            Latest Signal Performance
        </p>
    </header>
    <div class="card-content">
        <div class="grid gap-6 grid-cols-1 md:grid-cols-4">
            <div class="has-text-centered">
                <span class="icon is-large has-text-info">
                    <i class="mdi mdi-trending-up mdi-48px"></i>
                </span>
                <h1 class="title is-4">{{ $latestSignal->overall_strength ?? 75 }}%</h1>
                <p class="subtitle is-6">Signal Strength</p>
            </div>

            <div class="has-text-centered">
                <span class="icon is-large has-text-primary">
                    <i class="mdi mdi-chart-line mdi-48px"></i>
                </span>
                <h1 class="title is-4 {{ ($latestSignal->profit_loss_percentage ?? 0) >= 0 ? 'has-text-success' : 'has-text-danger' }}">
                    {{ number_format($latestSignal->profit_loss_percentage ?? 0, 2) }}%
                </h1>
                <p class="subtitle is-6">P&L Performance</p>
            </div>

            <div class="has-text-centered">
                <span class="icon is-large has-text-warning">
                    <i class="mdi mdi-star mdi-48px"></i>
                </span>
                <h1 class="title is-4">{{ $latestSignal->confidence_level ?? 3 }}/5</h1>
                <p class="subtitle is-6">Confidence Level</p>
            </div>

            <div class="has-text-centered">
                <span class="icon is-large has-text-grey">
                    <i class="mdi mdi-clock mdi-48px"></i>
                </span>
                <h1 class="title is-4">{{ $latestSignal->timeframe ?? 'H1' }}</h1>
                <p class="subtitle is-6">Timeframe</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="card mb-6">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-finance"></i></span>
            {{ $latestSignal->chart_symbol ?? $latestSignal->pair }} Performance Chart
        </p>
        <div class="card-header-icon">
            <div class="buttons has-addons">
                @foreach(['M1', 'M5', 'M15', 'M30', 'H1', 'H4', 'D1'] as $tf)
                <button class="button is-small {{ $tf === ($latestSignal->timeframe ?? 'H1') ? 'is-primary' : '' }}"
                        onclick="switchTimeframe('{{ $latestSignal->id }}', '{{ $tf }}')">
                    {{ $tf }}
                </button>
                @endforeach
            </div>
            <button class="button is-small" onclick="refreshCharts('{{ $latestSignal->id }}')">
                <span class="icon"><i class="mdi mdi-refresh"></i></span>
            </button>
        </div>
    </header>
    <div class="card-content">
        <canvas id="mainChart_{{ $latestSignal->id }}"></canvas>
    </div>
</div>

<!-- Technical Indicators Charts -->
<div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-6">
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-line"></i></span>
                RSI (14)
            </p>
        </header>
        <div class="card-content">
            <canvas id="rsiChart_{{ $latestSignal->id }}"></canvas>
        </div>
    </div>

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-histogram"></i></span>
                MACD
            </p>
        </header>
        <div class="card-content">
            <canvas id="macdChart_{{ $latestSignal->id }}"></canvas>
        </div>
    </div>

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                Volume Analysis
            </p>
        </header>
        <div class="card-content">
            <canvas id="volumeChart_{{ $latestSignal->id }}"></canvas>
        </div>
    </div>
</div>

<!-- Signal Information Section -->
<div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-6">
    <!-- Latest Signal Details -->
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-signal"></i></span>
                Latest Signal
            </p>
        </header>
        <div class="card-content">
            <div class="has-text-centered mb-6">
                <span class="tag is-{{ $latestSignal->signal_type === 'BUY' ? 'success' : ($latestSignal->signal_type === 'SELL' ? 'danger' : 'warning') }} is-large">
                    {{ $latestSignal->signal_type }}
                </span>
                <h3 class="title is-4 mt-3">{{ $latestSignal->chart_symbol ?? $latestSignal->pair }}</h3>
                <p class="subtitle is-6">{{ $latestSignal->provider_name ?? 'Expert Provider' }}</p>
            </div>

                        <div class="bg-slate-800 rounded-lg p-4 mb-4">
                            <div class="text-center mb-4">
                                <div class="text-3xl font-bold mb-2 text-white">{{ $latestSignal->overall_strength ?? 75 }}%</div>
                                <div class="text-sm text-gray-400">Overall Signal Strength</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-400">Market Trend</span>
                                    <span class="font-semibold
                                        @if($latestSignal->current_trend === 'BULLISH') text-green-400
                                        @elseif($latestSignal->current_trend === 'BEARISH') text-red-400
                                        @else text-yellow-400 @endif">
                                        {{ $latestSignal->current_trend ?? 'NEUTRAL' }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-3">
                                    <div class="h-3 rounded-full transition-all duration-500
                                        @if($latestSignal->current_trend === 'BULLISH') bg-green-500
                                        @elseif($latestSignal->current_trend === 'BEARISH') bg-red-500
                                        @else bg-yellow-500 @endif"
                                        style="width: {{ $latestSignal->overall_strength ?? 75 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Analysis -->
                    <div class="main-signal-card">
                        <h4 class="font-semibold mb-4 text-white text-lg">Technical Analysis</h4>
                        <div class="space-y-4">
                            @forelse($latestSignal->technicalIndicators as $indicator)
                            <div class="flex items-center justify-between bg-slate-800 rounded-lg p-3">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium w-16 text-white">{{ $indicator->indicator_name }}</span>
                                    <div class="flex-1 bg-gray-700 rounded-full h-2 w-24">
                                        <div class="h-2 rounded-full indicator-bar transition-all duration-300
                                            @if($indicator->status === 'bullish') bg-green-500
                                            @elseif($indicator->status === 'bearish') bg-red-500
                                            @else bg-yellow-500 @endif"
                                            style="width: {{ $indicator->strength }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-white">{{ number_format($indicator->value, 2) }}</div>
                                    <div class="text-xs font-medium
                                        @if($indicator->status === 'bullish') text-green-400
                                        @elseif($indicator->status === 'bearish') text-red-400
                                        @else text-yellow-400 @endif">
                                        {{ ucfirst($indicator->status) }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-sm text-gray-400 text-center py-4">No technical indicators available.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Trading Details & Market Status -->
                    <div class="space-y-4">
                        <div class="main-signal-card">
                            <h4 class="font-semibold mb-4 text-white text-lg">Trading Details</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Entry Price:</span>
                                    <span class="text-white font-medium text-lg">${{ number_format($latestSignal->entry_price, 5) }}</span>
                                </div>
                                @if($latestSignal->take_profit)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Take Profit:</span>
                                    <span class="text-green-400 font-medium">${{ number_format($latestSignal->take_profit, 5) }}</span>
                                </div>
                                @endif
                                @if($latestSignal->stop_loss)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Stop Loss:</span>
                                    <span class="text-red-400 font-medium">${{ number_format($latestSignal->stop_loss, 5) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Risk Level:</span>
                                    <span class="font-medium
                                        @if($latestSignal->risk_level === 'low') text-green-400
                                        @elseif($latestSignal->risk_level === 'medium') text-yellow-400
                                        @else text-red-400 @endif">
                                        {{ ucfirst($latestSignal->risk_level ?? 'Medium') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Timeframe:</span>
                                    <span class="text-white font-medium">{{ $latestSignal->timeframe ?? 'H1' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="main-signal-card">
                            <h4 class="font-semibold mb-4 text-white text-lg">Market Status</h4>
                            <div class="text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-2
                                    {{ $latestSignal->market_status === 'OPEN' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $latestSignal->market_status ?? 'OPEN' }}
                                </div>
                                <div class="text-sm text-gray-400">
                                    {{ now()->format('H:i T') }}
                                </div>
                            </div>
                        </div>

                        <div class="main-signal-card">
                            <h4 class="font-semibold mb-4 text-white text-lg">Analysis Summary</h4>
                            <p class="text-sm text-gray-300 leading-relaxed">
                                {{ $latestSignal->analysis_summary ?? 'Technical analysis indicates potential price movement based on current market conditions and indicator convergence.' }}
                            </p>
                        </div>

                        <div class="main-signal-card">
                             <h4 class="font-semibold mb-3 text-white text-lg">Performance</h4>
                             <div class="space-y-3">
                                 @if($latestSignal->profit_loss_percentage)
                                 <div class="flex justify-between items-center">
                                     <span class="text-gray-400 text-sm">P&L:</span>
                                     <span class="font-medium {{ $latestSignal->profit_loss_percentage >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                         {{ number_format($latestSignal->profit_loss_percentage, 2) }}%
                                     </span>
                                 </div>
                                 @endif
                                 <div class="flex justify-between items-center">
                                     <span class="text-gray-400 text-sm">Confidence:</span>
                                     <div class="flex items-center space-x-1">
                                         @for($i = 1; $i <= 5; $i++)
                                         <svg class="w-3 h-3 {{ $i <= ($latestSignal->confidence_level ?? 3) ? 'text-yellow-400' : 'text-gray-600' }}"
                                              fill="currentColor" viewBox="0 0 20 20">
                                             <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                         </svg>
                                         @endfor
                                     </div>
                                 </div>
                                 <div class="flex justify-between items-center">
                                     <span class="text-gray-400 text-sm">Status:</span>
                                     <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                         @if($latestSignal->signal_result === 'profit') bg-green-100 text-green-800
                                         @elseif($latestSignal->signal_result === 'loss') bg-red-100 text-red-800
                                         @else bg-blue-100 text-blue-800 @endif">
                                         {{ ucfirst($latestSignal->signal_result ?? 'Active') }}
                                     </span>
                                 </div>
                             </div>
                        </div>

                        <div class="main-signal-card">
                            <div class="space-y-2">
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                                    Follow Signal
                                </button>
                                <button class="w-full bg-gray-700 hover:bg-gray-600 text-gray-300 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                                    View Details
                                </button>
                            </div>
                        </div>                    </div>
                </div>
            </div>
@else
<!-- No Latest Signal - Show Alternative Content -->
<div class="card">
    <div class="card-content">
        <div class="has-text-centered">
            <span class="icon is-large has-text-warning">
                <i class="mdi mdi-information-outline mdi-48px"></i>
            </span>
            <h3 class="title is-5 has-text-warning">No Recent Signals Available</h3>
            <p class="subtitle is-6">No expert trading signals are currently available. Check back later for new signals.</p>
        </div>
    </div>
</div>
@endif

            @if($otherSignals && $otherSignals->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-6 text-white border-b border-gray-700 pb-2">Previous Signals</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($otherSignals as $signal)
                    <div class="signal-card rounded-xl border border-gray-700 p-6 hover:border-gray-500 transition-colors">
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-2
                                @if($signal->signal_type === 'BUY') bg-green-500 text-white
                                @elseif($signal->signal_type === 'SELL') bg-red-500 text-white
                                @else bg-yellow-500 text-black @endif">
                                {{ $signal->signal_type }}
                            </div>
                            <h3 class="text-lg font-bold text-white">{{ $signal->chart_symbol ?? $signal->pair }}</h3>
                            <p class="text-gray-400 text-sm">{{ $signal->provider_name ?? 'Expert Provider' }}</p>
                        </div>
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Entry Price:</span>
                                <span class="text-white font-medium">${{ number_format($signal->entry_price, 5) }}</span>
                            </div>
                            @if($signal->take_profit)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Take Profit:</span>
                                <span class="text-green-400 font-medium">${{ number_format($signal->take_profit, 5) }}</span>
                            </div>
                            @endif
                            @if($signal->stop_loss)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Stop Loss:</span>
                                <span class="text-red-400 font-medium">${{ number_format($signal->stop_loss, 5) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Risk Level:</span>
                                <span class="
                                    @if($signal->risk_level === 'low') text-green-400
                                    @elseif($signal->risk_level === 'medium') text-yellow-400
                                    @else text-red-400 @endif font-medium">
                                    {{ ucfirst($signal->risk_level ?? 'Medium') }}
                                </span>
                            </div>
                        </div>
                        <button class="w-full bg-gray-700 hover:bg-gray-600 text-gray-300 py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                            View Details
                        </button>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $otherSignals->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>    <script>
        // Add global error handler
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
        });

        // Initialize Admin One Chart Manager
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing Admin One Charts...');
            console.log('Chart.js version:', Chart.version);
            
            // Check if AdminOneChartManager is available
            if (typeof AdminOneChartManager === 'undefined') {
                console.error('AdminOneChartManager is not defined');
                return;
            }
            
            // Create chart manager instance
            const chartManager = new AdminOneChartManager();
            
            @if($latestSignal && $latestSignal->chartDataPoints && $latestSignal->chartDataPoints->count() > 0)
                try {
                    const signalId = '{{ $latestSignal->id }}';
                    
                    // Limit and validate chart data
                    const rawChartData = @json($latestSignal->chartDataPoints->take(30)->values());
                    
                    if (rawChartData && Array.isArray(rawChartData) && rawChartData.length > 0) {
                        console.log(`Processing ${rawChartData.length} chart data points`);
                        
                        // Initialize charts with timeout to prevent hanging
                        setTimeout(() => {
                            chartManager.initializeCharts(signalId, rawChartData);
                            console.log(`Successfully initialized Admin One charts for signal ${signalId}`);
                        }, 100);
                    } else {
                        console.warn('Invalid chart data format');
                    }
                } catch (error) {
                    console.error('Error initializing Admin One charts:', error);
                }
            @else
                console.log('No chart data available for latest signal');
            @endif
        });
        
        // Timeframe switching function
        function switchTimeframe(signalId, timeframe) {
            // Update button states
            document.querySelectorAll(`button[onclick*="${signalId}"]`).forEach(btn => {
                btn.classList.remove('timeframe-btn-active');
                btn.classList.add('timeframe-btn-light');
            });
            event.target.classList.remove('timeframe-btn-light');
            event.target.classList.add('timeframe-btn-active');
            
            console.log(`Switching to ${timeframe} for signal ${signalId}`);
            
            // TODO: Implement AJAX call to fetch new data for the selected timeframe
        }        // Refresh charts function
        function refreshCharts(signalId) {
            console.log(`Refreshing charts for signal ${signalId}`);
            
            // TODO: Implement AJAX call to refresh chart data
        }
    </script>

    <script>
        // Global functions for chart interaction
        function switchTimeframe(signalId, timeframe) {
            console.log(`Switching to timeframe: ${timeframe} for signal: ${signalId}`);
            
            // Update active button
            document.querySelectorAll('.timeframe-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Here you would typically fetch new data for the timeframe
            // For now, we'll just refresh the existing charts
            if (window.chartManager) {
                window.chartManager.handleResize();
            }
        }

        function refreshCharts(signalId) {
            console.log(`Refreshing charts for signal: ${signalId}`);
            
            // Add loading state
            const refreshBtn = event.target;
            const originalContent = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';
            refreshBtn.disabled = true;
            
            // Simulate refresh delay
            setTimeout(() => {
                if (window.chartManager) {
                    window.chartManager.handleResize();
                }
                
                // Restore button
                refreshBtn.innerHTML = originalContent;
                refreshBtn.disabled = false;
            }, 1000);
        }

        // Enhanced chart initialization
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing Expert Signals Charts...');
            
            // Check dependencies
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }
            
            if (typeof AdminOneChartManager === 'undefined') {
                console.error('AdminOneChartManager is not loaded');
                return;
            }
            
            // Create global chart manager instance
            window.chartManager = new AdminOneChartManager();
            
            @if($latestSignal && $latestSignal->chartDataPoints && $latestSignal->chartDataPoints->count() > 0)
            try {
                const signalId = '{{ $latestSignal->id }}';
                const rawChartData = @json($latestSignal->chartDataPoints->take(30)->values());
                
                if (rawChartData && Array.isArray(rawChartData) && rawChartData.length > 0) {
                    console.log(`Initializing charts with ${rawChartData.length} data points`);
                    
                    // Add small delay to ensure DOM is fully ready
                    setTimeout(() => {
                        window.chartManager.initializeCharts(signalId, rawChartData);
                    }, 100);
                } else {
                    console.warn('No valid chart data available');
                    showNoDataMessage();
                }
            } catch (error) {
                console.error('Error initializing charts:', error);
                showErrorMessage();
            }
            @else
            console.warn('No signal data available for charts');
            showNoDataMessage();
            @endif
        });

        function showNoDataMessage() {
            const chartContainers = document.querySelectorAll('.chart-area, .sub-chart-canvas-wrapper');
            chartContainers.forEach(container => {
                container.innerHTML = `
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <div class="text-center">
                            <i class="mdi mdi-chart-line text-4xl mb-2"></i>
                            <p>No chart data available</p>
                        </div>
                    </div>
                `;
            });
        }

        function showErrorMessage() {
            const chartContainers = document.querySelectorAll('.chart-area, .sub-chart-canvas-wrapper');
            chartContainers.forEach(container => {
                container.innerHTML = `
                    <div class="flex items-center justify-center h-full text-red-400">
                        <div class="text-center">
                            <i class="mdi mdi-alert-circle text-4xl mb-2"></i>
                            <p>Error loading chart data</p>
                        </div>
                    </div>
                `;
            });
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.chartManager) {
                window.chartManager.handleResize();
            }
        });
    </script>
@endsection