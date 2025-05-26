@extends('layouts.admin')

@section('title', 'Admin Activity Logs')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Admin Activity Logs</p>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="exportLogs()">
                                <i class="fas fa-download me-1"></i>
                                Export
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearOldLogs()">
                                <i class="fas fa-trash me-1"></i>
                                Clear Old Logs
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

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Actions</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['today_actions'] ?? '47' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="fas fa-user-shield text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Admins</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['active_admins'] ?? '5' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fas fa-users text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Critical Actions</p>
                                                <h5 class="font-weight-bolder mb-0 text-warning">
                                                    {{ $stats['critical_actions'] ?? '3' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-exclamation-triangle text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Failed Actions</p>
                                                <h5 class="font-weight-bolder mb-0 text-danger">
                                                    {{ $stats['failed_actions'] ?? '2' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                                <i class="fas fa-times-circle text-lg opacity-10"></i>
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
                            <select class="form-select" id="adminFilter">
                                <option value="">All Admins</option>
                                @foreach($admins ?? [] as $admin)
                                <option value="{{ $admin['id'] }}">{{ $admin['name'] }}</option>
                                @endforeach
                                <!-- Sample data -->
                                <option value="1">John Admin</option>
                                <option value="2">Jane Manager</option>
                                <option value="3">Mike SuperAdmin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="actionFilter">
                                <option value="">All Actions</option>
                                <option value="create">Create</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                                <option value="settings">Settings</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="levelFilter">
                                <option value="">All Levels</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="critical">Critical</option>
                                <option value="error">Error</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFrom" placeholder="From Date">
                        </div>
                        <div class="col-md-1">
                            <input type="date" class="form-control" id="dateTo" placeholder="To Date">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-primary" onclick="applyFilters()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Resource</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Level</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IP Address</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Timestamp</th>
                                    <th class="text-secondary opacity-7">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs ?? [] as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $log['admin_name'] ?? 'John Admin' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $log['admin_email'] ?? 'john@admin.com' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['action'] ?? 'Updated User' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['resource'] ?? 'User Profile' }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ ($log['level'] ?? 'info') == 'critical' ? 'danger' : (($log['level'] ?? 'info') == 'warning' ? 'warning' : 'info') }}">
                                            {{ strtoupper($log['level'] ?? 'info') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['ip_address'] ?? '192.168.1.100' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $log['created_at'] ?? now()->subMinutes(rand(5, 1440))->format('M d, H:i:s') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewLogDetails({{ $log['id'] ?? 1 }})" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <!-- Sample data for demonstration -->
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">John Admin</h6>
                                                <p class="text-xs text-secondary mb-0">john@admin.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Updated User</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">User Profile</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-info">INFO</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">192.168.1.100</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(15)->format('M d, H:i:s') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewLogDetails(1)" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Jane Manager</h6>
                                                <p class="text-xs text-secondary mb-0">jane@admin.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Deleted Subscription</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Subscription Plan</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">WARNING</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">192.168.1.101</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(45)->format('M d, H:i:s') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewLogDetails(2)" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Mike SuperAdmin</h6>
                                                <p class="text-xs text-secondary mb-0">mike@admin.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">System Settings Change</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">System Configuration</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-danger">CRITICAL</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">192.168.1.102</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(2)->format('M d, H:i:s') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewLogDetails(3)" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">John Admin</h6>
                                                <p class="text-xs text-secondary mb-0">john@admin.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Login Attempt</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Authentication</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-info">INFO</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">192.168.1.100</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(8)->format('M d, H:i:s') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0" onclick="viewLogDetails(4)" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted">Showing 1 to 4 of 127 entries</span>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewLogDetails(id) {
    // Show modal with log details
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    
    // Sample log details
    const logDetails = {
        1: {
            admin: 'John Admin',
            action: 'Updated User Profile',
            resource: 'User ID: 123',
            timestamp: '{{ now()->subMinutes(15)->format("Y-m-d H:i:s") }}',
            ip: '192.168.1.100',
            user_agent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            details: 'Updated user email from old@email.com to new@email.com',
            changes: {
                'email': { old: 'old@email.com', new: 'new@email.com' },
                'name': { old: 'Old Name', new: 'New Name' }
            }
        }
    };
    
    const log = logDetails[id] || logDetails[1];
    
    document.getElementById('logDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Admin:</strong> ${log.admin}<br>
                <strong>Action:</strong> ${log.action}<br>
                <strong>Resource:</strong> ${log.resource}<br>
                <strong>Timestamp:</strong> ${log.timestamp}
            </div>
            <div class="col-md-6">
                <strong>IP Address:</strong> ${log.ip}<br>
                <strong>User Agent:</strong> ${log.user_agent.substring(0, 50)}...
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Details:</strong><br>
                <p>${log.details}</p>
                <strong>Changes Made:</strong>
                <pre class="bg-light p-3">${JSON.stringify(log.changes, null, 2)}</pre>
            </div>
        </div>
    `;
    
    modal.show();
}

function exportLogs() {
    console.log('Export logs functionality');
    // Implement export functionality
}

function clearOldLogs() {
    if (confirm('Are you sure you want to clear logs older than 90 days? This action cannot be undone.')) {
        console.log('Clear old logs functionality');
        // Implement clear old logs functionality
    }
}

function applyFilters() {
    const search = document.getElementById('search').value;
    const admin = document.getElementById('adminFilter').value;
    const action = document.getElementById('actionFilter').value;
    const level = document.getElementById('levelFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    console.log('Apply filters:', { search, admin, action, level, dateFrom, dateTo });
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
