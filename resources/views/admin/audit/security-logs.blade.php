@extends('layouts.admin')

@section('title', 'Security Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Security Logs</p>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#securityAlertModal">
                                <i class="fas fa-shield-alt me-1"></i>
                                Security Alert
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="exportSecurityReport()">
                                <i class="fas fa-file-shield me-1"></i>
                                Export Report
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

                    <!-- Security Overview -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Threat Level</p>
                                                <h5 class="font-weight-bolder mb-0 text-success">
                                                    Low
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fas fa-shield-alt text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Failed Logins</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['failed_logins'] ?? '23' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-user-times text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Blocked IPs</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['blocked_ips'] ?? '7' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                                <i class="fas fa-ban text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Suspicious Activity</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['suspicious_activity'] ?? '5' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fas fa-eye text-lg opacity-10"></i>
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
                            <select class="form-select" id="eventTypeFilter">
                                <option value="">All Events</option>
                                <option value="login_failed">Failed Login</option>
                                <option value="login_success">Successful Login</option>
                                <option value="password_change">Password Change</option>
                                <option value="account_locked">Account Locked</option>
                                <option value="suspicious_activity">Suspicious Activity</option>
                                <option value="permission_denied">Permission Denied</option>
                                <option value="data_breach_attempt">Data Breach Attempt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="severityFilter">
                                <option value="">All Severity</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="ipFilter" placeholder="IP Address">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFilter" placeholder="Date">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Event Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User/Source</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">IP Address</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Severity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($securityLogs ?? [] as $log)
                                <tr>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['timestamp'] ?? now()->subMinutes(rand(1, 1440))->format('M d, H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ ($log['event_type'] ?? 'login_failed') == 'login_failed' ? 'warning' : (($log['event_type'] ?? 'login_failed') == 'suspicious_activity' ? 'danger' : 'info') }}">
                                            {{ str_replace('_', ' ', strtoupper($log['event_type'] ?? 'login_failed')) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">{{ $log['user'] ?? 'Unknown User' }}</span>
                                            <small class="text-xs text-muted">{{ $log['user_agent'] ?? 'Chrome/118.0' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['ip_address'] ?? '192.168.1.' . rand(100, 200) }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ ($log['severity'] ?? 'medium') == 'critical' ? 'danger' : (($log['severity'] ?? 'medium') == 'high' ? 'warning' : 'info') }}">
                                            {{ strtoupper($log['severity'] ?? 'medium') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-sm">{{ Str::limit($log['description'] ?? 'Multiple failed login attempts detected', 50) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewSecurityDetails({{ $log['id'] ?? 1 }})">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="blockIP('{{ $log['ip_address'] ?? '192.168.1.100' }}')">Block IP</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="investigateUser({{ $log['user_id'] ?? 1 }})">Investigate User</a></li>
                                                @if(($log['severity'] ?? 'medium') == 'critical')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="escalateIncident({{ $log['id'] ?? 1 }})">Escalate Incident</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <!-- Sample data for demonstration -->
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(15)->format('M d, H:i') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-warning">FAILED LOGIN</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">john.doe@example.com</span>
                                            <small class="text-xs text-muted">Chrome/118.0</small>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">192.168.1.150</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">HIGH</span>
                                    </td>
                                    <td><span class="text-sm">5 consecutive failed login attempts</span></td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewSecurityDetails(1)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="blockIP('192.168.1.150')">Block IP</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="investigateUser(1)">Investigate User</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(32)->format('M d, H:i') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-danger">SUSPICIOUS ACTIVITY</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">Anonymous</span>
                                            <small class="text-xs text-muted">Unknown Bot</small>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">10.0.0.45</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-danger">CRITICAL</span>
                                    </td>
                                    <td><span class="text-sm">SQL injection attempt detected</span></td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewSecurityDetails(2)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="blockIP('10.0.0.45')">Block IP</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="escalateIncident(2)">Escalate Incident</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(1)->format('M d, H:i') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-info">LOGIN SUCCESS</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">admin@system.com</span>
                                            <small class="text-xs text-muted">Firefox/118.0</small>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">192.168.1.100</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-info">LOW</span>
                                    </td>
                                    <td><span class="text-sm">Admin login from trusted location</span></td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewSecurityDetails(3)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="investigateUser(3)">Investigate User</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(2)->format('M d, H:i') }}</span></td>
                                    <td><span class="badge badge-sm bg-gradient-warning">PERMISSION DENIED</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm">user123@example.com</span>
                                            <small class="text-xs text-muted">Safari/16.0</small>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">192.168.1.175</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">MEDIUM</span>
                                    </td>
                                    <td><span class="text-sm">Attempted access to admin panel</span></td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewSecurityDetails(4)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="investigateUser(4)">Investigate User</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted">Showing 1 to 4 of 89 entries</span>
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

<!-- Security Alert Modal -->
<div class="modal fade" id="securityAlertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Security Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.audit.security-logs.alert') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alert_type" class="form-label">Alert Type</label>
                        <select class="form-select" id="alert_type" name="alert_type" required>
                            <option value="">Select Alert Type</option>
                            <option value="security_breach">Security Breach</option>
                            <option value="suspicious_activity">Suspicious Activity</option>
                            <option value="brute_force_attack">Brute Force Attack</option>
                            <option value="unauthorized_access">Unauthorized Access</option>
                            <option value="data_leak">Data Leak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="severity" class="form-label">Severity</label>
                        <select class="form-select" id="severity" name="severity" required>
                            <option value="">Select Severity</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="affected_systems" class="form-label">Affected Systems</label>
                        <input type="text" class="form-control" id="affected_systems" name="affected_systems" placeholder="e.g., User authentication, Payment system">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Create Alert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Security Details Modal -->
<div class="modal fade" id="securityDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Security Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="securityDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="blockCurrentIP()">Block IP</button>
                <button type="button" class="btn btn-danger" onclick="escalateCurrentIncident()">Escalate</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentLogData = null;

function viewSecurityDetails(id) {
    const modal = new bootstrap.Modal(document.getElementById('securityDetailsModal'));
    
    // Sample security event details
    currentLogData = {
        id: id,
        timestamp: '{{ now()->format("Y-m-d H:i:s") }}',
        event_type: 'Failed Login',
        user: 'john.doe@example.com',
        ip_address: '192.168.1.150',
        user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36',
        severity: 'High',
        description: '5 consecutive failed login attempts from suspicious IP',
        geolocation: 'New York, USA',
        attempts: 5,
        duration: '2 minutes',
        threat_score: 85
    };
    
    document.getElementById('securityDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Event Type:</strong> ${currentLogData.event_type}<br>
                <strong>Timestamp:</strong> ${currentLogData.timestamp}<br>
                <strong>User:</strong> ${currentLogData.user}<br>
                <strong>IP Address:</strong> ${currentLogData.ip_address}<br>
                <strong>Geolocation:</strong> ${currentLogData.geolocation}
            </div>
            <div class="col-md-6">
                <strong>Severity:</strong> <span class="badge bg-warning">${currentLogData.severity}</span><br>
                <strong>Threat Score:</strong> ${currentLogData.threat_score}/100<br>
                <strong>Attempts:</strong> ${currentLogData.attempts}<br>
                <strong>Duration:</strong> ${currentLogData.duration}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Description:</strong><br>
                <p>${currentLogData.description}</p>
                <strong>User Agent:</strong><br>
                <code>${currentLogData.user_agent}</code>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Recommended Actions:</strong>
                <ul>
                    <li>Monitor this IP address for continued suspicious activity</li>
                    <li>Consider temporarily blocking the IP if attempts continue</li>
                    <li>Notify the user about the failed login attempts</li>
                    <li>Review user account for any security compromises</li>
                </ul>
            </div>
        </div>
    `;
    
    modal.show();
}

function blockIP(ip) {
    if (confirm(`Are you sure you want to block IP address ${ip}?`)) {
        console.log('Block IP:', ip);
        // Implement IP blocking functionality
        alert(`IP address ${ip} has been blocked.`);
    }
}

function blockCurrentIP() {
    if (currentLogData && currentLogData.ip_address) {
        blockIP(currentLogData.ip_address);
    }
}

function investigateUser(userId) {
    console.log('Investigate user:', userId);
    // Implement user investigation functionality
    alert('User investigation initiated. Check admin dashboard for details.');
}

function escalateIncident(id) {
    if (confirm('Escalate this security incident to the security team?')) {
        console.log('Escalate incident:', id);
        // Implement incident escalation functionality
        alert('Security incident has been escalated to the security team.');
    }
}

function escalateCurrentIncident() {
    if (currentLogData && currentLogData.id) {
        escalateIncident(currentLogData.id);
    }
}

function exportSecurityReport() {
    console.log('Export security report');
    // Implement security report export functionality
}

function applyFilters() {
    const search = document.getElementById('search').value;
    const eventType = document.getElementById('eventTypeFilter').value;
    const severity = document.getElementById('severityFilter').value;
    const ip = document.getElementById('ipFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    console.log('Apply filters:', { search, eventType, severity, ip, date });
    // Implement filter functionality
}

// Real-time updates simulation
function simulateRealTimeUpdates() {
    // This would be replaced with actual WebSocket or Server-Sent Events implementation
    setInterval(() => {
        const threatLevelElement = document.querySelector('.text-success');
        if (threatLevelElement && Math.random() > 0.95) {
            threatLevelElement.textContent = 'Medium';
            threatLevelElement.className = 'font-weight-bolder mb-0 text-warning';
        }
    }, 30000); // Check every 30 seconds
}

// Initialize real-time updates
document.addEventListener('DOMContentLoaded', function() {
    simulateRealTimeUpdates();
});
</script>
@endsection
