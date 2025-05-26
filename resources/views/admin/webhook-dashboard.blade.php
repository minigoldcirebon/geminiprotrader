@extends('layouts.admin')

@section('title', 'Webhook Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Webhook Dashboard</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('admin.webhook-dashboard.stats') }}" class="btn btn-outline-info" target="_blank">
                <i class="fas fa-chart-line"></i> API Stats
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Webhook Signals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalWebhookSignals) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Profitable Signals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalProfit) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Win Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $winRate }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg Profit/Loss</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgProfitLoss, 2) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Signals by Source -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Signals by Source</h6>
                </div>
                <div class="card-body">
                    <canvas id="sourceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance by Source -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Performance by Source</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
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
                                        <span class="badge badge-primary">{{ $perf->webhook_source }}</span>
                                    </td>
                                    <td>{{ $perf->total }}</td>
                                    <td class="text-success">{{ $perf->profits }}</td>
                                    <td class="text-danger">{{ $perf->losses }}</td>
                                    <td>
                                        <span class="badge badge-{{ $winRate >= 60 ? 'success' : ($winRate >= 40 ? 'warning' : 'danger') }}">
                                            {{ $winRate }}%
                                        </span>
                                    </td>
                                    <td class="{{ $perf->avg_profit_loss >= 0 ? 'text-success' : 'text-danger' }}">
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
    </div>

    <!-- Recent Signals -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Webhook Signals</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="recentSignalsTable" width="100%" cellspacing="0">
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
                                <span class="badge badge-info">{{ $signal->webhook_source }}</span>
                            </td>
                            <td><strong>{{ $signal->pair }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $signal->signal_type === 'BUY' ? 'success' : ($signal->signal_type === 'SELL' ? 'danger' : 'warning') }}">
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
                                    <span class="badge badge-{{ $signal->signal_result === 'profit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($signal->signal_result) }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary">Pending</span>
                                @endif
                            </td>
                            <td class="{{ $signal->profit_loss_percentage ? ($signal->profit_loss_percentage > 0 ? 'text-success' : 'text-danger') : '' }}">
                                {{ $signal->profit_loss_percentage ? number_format($signal->profit_loss_percentage, 2) . '%' : '-' }}
                            </td>
                            <td>{{ $signal->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $signal->status === 'published' ? 'success' : 'warning' }}">
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
