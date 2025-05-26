@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">System Logs</p>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="refreshLogs()">
                                <i class="fas fa-sync me-1"></i>
                                Refresh
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm me-2" onclick="downloadLogs()">
                                <i class="fas fa-download me-1"></i>
                                Download
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearLogs()">
                                <i class="fas fa-broom me-1"></i>
                                Clear Logs
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- System Health Overview -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">System Status</p>
                                                <h5 class="font-weight-bolder mb-0 text-success">
                                                    Healthy
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fas fa-heartbeat text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Error Rate</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['error_rate'] ?? '0.02' }}%
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fas fa-exclamation-circle text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Logs</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['total_logs'] ?? '12,459' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="fas fa-list text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Disk Usage</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['disk_usage'] ?? '2.3' }}GB
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-hdd text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="search" placeholder="Search logs...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="levelFilter">
                                <option value="">All Levels</option>
                                <option value="debug">Debug</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="componentFilter">
                                <option value="">All Components</option>
                                <option value="authentication">Authentication</option>
                                <option value="database">Database</option>
                                <option value="cache">Cache</option>
                                <option value="mail">Mail</option>
                                <option value="queue">Queue</option>
                                <option value="payment">Payment</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="datetime-local" class="form-control" id="dateFrom" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="datetime-local" class="form-control" id="dateTo" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-search me-1"></i>
                                Filter
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Timestamp</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Level</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Component</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Message</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Context</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs ?? [] as $log)
                                <tr>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['timestamp'] ?? now()->subMinutes(rand(1, 60))->format('Y-m-d H:i:s') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ ($log['level'] ?? 'info') == 'error' ? 'danger' : (($log['level'] ?? 'info') == 'warning' ? 'warning' : 'info') }}">
                                            {{ strtoupper($log['level'] ?? 'info') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['component'] ?? 'authentication' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">{{ Str::limit($log['message'] ?? 'User login successful', 80) }}</span>
                                            @if(isset($log['file']))
                                            <small class="text-xs text-muted">{{ $log['file'] }}:{{ $log['line'] ?? '0' }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if(isset($log['context']) && !empty($log['context']))
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewContext({{ $log['id'] ?? 1 }})" data-bs-toggle="tooltip" title="View Context">
                                            <i class="fas fa-info-circle text-xs"></i>
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewFullLog({{ $log['id'] ?? 1 }})">View Full Log</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="searchSimilar('{{ $log['component'] ?? 'authentication' }}')">Find Similar</a></li>
                                                @if(($log['level'] ?? 'info') == 'error')
                                                <li><a class="dropdown-item" href="#" onclick="reportBug({{ $log['id'] ?? 1 }})">Report as Bug</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <!-- Sample data for demonstration -->
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(5)->format('Y-m-d H:i:s') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-info">INFO</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">authentication</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">User login successful for user ID: 123</span>
                                            <small class="text-xs text-muted">app/Http/Controllers/Auth/LoginController.php:45</small>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewContext(1)" data-bs-toggle="tooltip" title="View Context">
                                            <i class="fas fa-info-circle text-xs"></i>
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewFullLog(1)">View Full Log</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="searchSimilar('authentication')">Find Similar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(12)->format('Y-m-d H:i:s') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-warning">WARNING</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">database</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">Slow query detected: SELECT * FROM users WHERE...</span>
                                            <small class="text-xs text-muted">database/QueryLogger.php:78</small>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewContext(2)" data-bs-toggle="tooltip" title="View Context">
                                            <i class="fas fa-info-circle text-xs"></i>
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewFullLog(2)">View Full Log</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="searchSimilar('database')">Find Similar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(28)->format('Y-m-d H:i:s') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-danger">ERROR</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">payment</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">Payment gateway timeout for transaction ID: TXN123456</span>
                                            <small class="text-xs text-muted">app/Services/PaymentService.php:156</small>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewContext(3)" data-bs-toggle="tooltip" title="View Context">
                                            <i class="fas fa-info-circle text-xs"></i>
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewFullLog(3)">View Full Log</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="searchSimilar('payment')">Find Similar</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="reportBug(3)">Report as Bug</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(1)->format('Y-m-d H:i:s') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-info">INFO</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">cache</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">Cache cleared successfully</span>
                                            <small class="text-xs text-muted">app/Console/Commands/ClearCache.php:23</small>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-muted">-</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewFullLog(4)">View Full Log</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="searchSimilar('cache')">Find Similar</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted">Showing 1 to 4 of 12,459 entries</span>
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">System Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copyLogDetails()">Copy to Clipboard</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewFullLog(id) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    
    // Sample log details
    const logDetails = {
        timestamp: '{{ now()->format("Y-m-d H:i:s") }}',
        level: 'INFO',
        component: 'authentication',
        message: 'User login successful for user ID: 123',
        file: 'app/Http/Controllers/Auth/LoginController.php',
        line: 45,
        context: {
            user_id: 123,
            ip_address: '192.168.1.100',
            user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            session_id: 'abc123def456'
        },
        stack_trace: null
    };
    
    document.getElementById('logDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Timestamp:</strong> ${logDetails.timestamp}<br>
                <strong>Level:</strong> <span class="badge bg-info">${logDetails.level}</span><br>
                <strong>Component:</strong> ${logDetails.component}<br>
                <strong>File:</strong> ${logDetails.file}:${logDetails.line}
            </div>
            <div class="col-md-6">
                <strong>Message:</strong><br>
                <p class="bg-light p-2">${logDetails.message}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Context:</strong>
                <pre class="bg-light p-3">${JSON.stringify(logDetails.context, null, 2)}</pre>
            </div>
        </div>
    `;
    
    modal.show();
}

function viewContext(id) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    
    const context = {
        user_id: 123,
        ip_address: '192.168.1.100',
        user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        session_id: 'abc123def456',
        request_id: 'req_789xyz'
    };
    
    document.getElementById('logDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-12">
                <h6>Log Context Information</h6>
                <pre class="bg-light p-3">${JSON.stringify(context, null, 2)}</pre>
            </div>
        </div>
    `;
    
    modal.show();
}

function searchSimilar(component) {
    document.getElementById('componentFilter').value = component;
    applyFilters();
}

function reportBug(id) {
    if (confirm('Report this error as a bug to the development team?')) {
        console.log('Report bug for log ID:', id);
        // Implement bug reporting functionality
    }
}

function refreshLogs() {
    console.log('Refresh logs');
    location.reload();
}

function downloadLogs() {
    console.log('Download logs');
    // Implement download functionality
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
        console.log('Clear logs');
        // Implement clear logs functionality
    }
}

function copyLogDetails() {
    const content = document.getElementById('logDetailsContent').innerText;
    navigator.clipboard.writeText(content).then(function() {
        alert('Log details copied to clipboard!');
    });
}

function applyFilters() {
    const search = document.getElementById('search').value;
    const level = document.getElementById('levelFilter').value;
    const component = document.getElementById('componentFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    console.log('Apply filters:', { search, level, component, dateFrom, dateTo });
    // Implement filter functionality
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
