@extends('layouts.admin-one')

@section('title', 'Webhook Dashboard')
@section('page-title', 'Webhook Dashboard')
@section('page-heading', 'Webhook Dashboard')

@section('hero-actions')
<div class="buttons">
    <button class="button blue" onclick="refreshStats()">
        <span class="icon"><i class="mdi mdi-refresh"></i></span>
        <span>Refresh</span>
    </button>
    <a href="{{ route('admin.webhook-dashboard.stats') }}" class="button light" target="_blank">
        <span class="icon"><i class="mdi mdi-chart-line"></i></span>
        <span>API Stats</span>
    </a>
</div>
@endsection

@section('content')

<!-- Statistics Cards -->
<div class="grid gap-6 grid-cols-1 md:grid-cols-4 mb-6">
    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Total Webhook Signals</h3>
                    <h1>{{ number_format($totalWebhookSignals) }}</h1>
                </div>
                <span class="icon widget-icon text-blue-500"><i class="mdi mdi-chart-line mdi-48px"></i></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Profitable Signals</h3>
                    <h1>{{ number_format($totalProfit) }}</h1>
                </div>
                <span class="icon widget-icon text-green-500"><i class="mdi mdi-trending-up mdi-48px"></i></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                <div class="widget-label">
                    <h3>Win Rate</h3>
                    <h1>{{ $winRate }}%</h1>
                </div>
                <span class="icon widget-icon text-yellow-500"><i class="mdi mdi-percent mdi-48px"></i></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="flex items-center justify-between">
                    <h3>Avg Profit/Loss</h3>
                    <h1>{{ number_format($avgProfitLoss, 2) }}%</h1>
                </div>
                <span class="icon widget-icon text-purple-500"><i class="mdi mdi-calculator mdi-48px"></i></span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid gap-6 grid-cols-1 lg:grid-cols-2 mb-6">
    <!-- Signals by Source -->
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-pie"></i></span>
                Signals by Source
            </p>
        </header>
        <div class="card-content">
            <canvas id="sourceChart"></canvas>
        </div>
    </div>

    <!-- Performance by Source -->
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                <span class="icon"><i class="mdi mdi-chart-bar"></i></span>
                Performance by Source
            </p>
        </header>
        <div class="card-content">
            <div class="table-container">
                <table class="table is-striped is-hoverable">
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Total</th>
                            <th>Profits</th>
                            <th>Losses</th>
                            <th>Win Rate</th>
                            <th>Avg P/L</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performanceBySource as $perf)
                        @php
                            $winRate = ($perf->profits + $perf->losses) > 0 ? 
                                round(($perf->profits / ($perf->profits + $perf->losses)) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="tag is-primary">{{ $perf->webhook_source }}</span>
                            </td>
                            <td>{{ $perf->total }}</td>
                            <td class="has-text-success">{{ $perf->profits }}</td>
                            <td class="has-text-danger">{{ $perf->losses }}</td>
                            <td>
                                <span class="tag is-{{ $winRate >= 60 ? 'success' : ($winRate >= 40 ? 'warning' : 'danger') }}">
                                    {{ $winRate }}%
                                </span>
                            </td>
                            <td class="{{ $perf->avg_profit_loss >= 0 ? 'has-text-success' : 'has-text-danger' }}">
                                {{ number_format($perf->avg_profit_loss, 2) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Signals -->
<div class="card">
    <header class="card-header">
        <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-signal"></i></span>
            Recent Webhook Signals
        </p>
    </header>
    <div class="card-content">
        <div class="table-container">
            <table class="table is-striped is-hoverable" id="recentSignalsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Source</th>
                        <th>Pair</th>
                        <th>Type</th>
                        <th>Entry Price</th>
                        <th>TP/SL</th>
                        <th>Result</th>
                        <th>P/L %</th>
                        <th>Created</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSignals as $signal)
                    <tr>
                        <td>{{ $signal->id }}</td>
                        <td>
                            <span class="tag is-info">{{ $signal->webhook_source }}</span>
                        </td>
                        <td><strong>{{ $signal->pair }}</strong></td>
                        <td>
                            <span class="tag is-{{ $signal->signal_type === 'BUY' ? 'success' : ($signal->signal_type === 'SELL' ? 'danger' : 'warning') }}">
                                {{ $signal->signal_type }}
                            </span>
                        </td>
                        <td>{{ number_format($signal->entry_price, 4) }}</td>
                        <td>
                            <small>
                                TP: {{ $signal->take_profit ? number_format($signal->take_profit, 4) : 'N/A' }}<br>
                                SL: {{ $signal->stop_loss ? number_format($signal->stop_loss, 4) : 'N/A' }}
                            </small>
                        </td>
                        <td>
                            @if($signal->signal_result)
                                <span class="tag is-{{ $signal->signal_result === 'profit' ? 'success' : 'danger' }}">
                                    {{ ucfirst($signal->signal_result) }}
                                </span>
                            @else
                                <span class="tag is-light">Pending</span>
                            @endif
                        </td>
                        <td class="{{ $signal->profit_loss_percentage ? ($signal->profit_loss_percentage > 0 ? 'has-text-success' : 'has-text-danger') : '' }}">
                            {{ $signal->profit_loss_percentage ? number_format($signal->profit_loss_percentage, 2) . '%' : '-' }}
                        </td>
                        <td>{{ $signal->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="tag is-{{ $signal->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($signal->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Signals by Source Chart
const sourceData = @json($signalsBySource);
const sourceLabels = sourceData.map(item => item.webhook_source);
const sourceCounts = sourceData.map(item => item.total);

const sourceCtx = document.getElementById('sourceChart').getContext('2d');
new Chart(sourceCtx, {
    type: 'doughnut',
    data: {
        labels: sourceLabels,
        datasets: [{
            data: sourceCounts,
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#858796'
            ],
            hoverBackgroundColor: [
                '#2e59d9',
                '#17a673',
                '#2c9faf',
                '#f4b619',
                '#e02d1b',
                '#6f7074'
            ],
            borderWidth: 2,
        }]
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: true,
            position: 'bottom'
        },
        cutoutPercentage: 60,
    },
});

// DataTable initialization
$(document).ready(function() {
    $('#recentSignalsTable').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25,
        "responsive": true
    });
});

// Refresh function
function refreshStats() {
    location.reload();
}
</script>
@endpush
@endsection
