@extends('layouts.admin')

@section('title', 'System Alerts')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">System Alerts</p>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAlertModal">
                            <i class="fas fa-plus me-1"></i>
                            Create Alert
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Alert Statistics -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Alerts</p>
                                                <h5 class="font-weight-bolder mb-0 text-danger">
                                                    {{ $stats['active'] ?? '3' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                                <i class="fas fa-exclamation-triangle text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Resolved Today</p>
                                                <h5 class="font-weight-bolder mb-0 text-success">
                                                    {{ $stats['resolved_today'] ?? '8' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fas fa-check-circle text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Critical Alerts</p>
                                                <h5 class="font-weight-bolder mb-0 text-warning">
                                                    {{ $stats['critical'] ?? '1' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-fire text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Avg Response Time</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['avg_response'] ?? '15' }}m
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fas fa-clock text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search" placeholder="Search alerts...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="severityFilter">
                                <option value="">All Severity</option>
                                <option value="critical">Critical</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="acknowledged">Acknowledged</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="system">System</option>
                                <option value="security">Security</option>
                                <option value="performance">Performance</option>
                                <option value="network">Network</option>
                                <option value="database">Database</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFilter" placeholder="Filter by date">
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alert</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Severity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assigned To</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts ?? [] as $alert)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $alert['title'] ?? 'High CPU Usage Detected' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($alert['description'] ?? 'Server CPU usage exceeded 90% threshold', 60) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ ($alert['severity'] ?? 'high') == 'critical' ? 'danger' : (($alert['severity'] ?? 'high') == 'high' ? 'warning' : 'info') }}">
                                            {{ strtoupper($alert['severity'] ?? 'high') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ ucfirst($alert['category'] ?? 'performance') }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ ($alert['status'] ?? 'active') == 'active' ? 'danger' : (($alert['status'] ?? 'active') == 'resolved' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($alert['status'] ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $alert['assigned_to'] ?? 'Admin Team' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $alert['created_at'] ?? now()->subMinutes(rand(5, 120))->format('M d, H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewAlert({{ $alert['id'] ?? 1 }})">View Details</a></li>
                                                @if(($alert['status'] ?? 'active') == 'active')
                                                <li><a class="dropdown-item" href="#" onclick="acknowledgeAlert({{ $alert['id'] ?? 1 }})">Acknowledge</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="assignAlert({{ $alert['id'] ?? 1 }})">Assign</a></li>
                                                @endif
                                                @if(in_array(($alert['status'] ?? 'active'), ['active', 'acknowledged']))
                                                <li><a class="dropdown-item text-success" href="#" onclick="resolveAlert({{ $alert['id'] ?? 1 }})">Mark Resolved</a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="escalateAlert({{ $alert['id'] ?? 1 }})">Escalate</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAlert({{ $alert['id'] ?? 1 }})">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <!-- Sample data for demonstration -->
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">High CPU Usage Detected</h6>
                                                <p class="text-xs text-secondary mb-0">Server CPU usage exceeded 90% threshold</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-danger">CRITICAL</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Performance</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-danger">Active</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">DevOps Team</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subMinutes(15)->format('M d, H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewAlert(1)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="acknowledgeAlert(1)">Acknowledge</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="assignAlert(1)">Assign</a></li>
                                                <li><a class="dropdown-item text-success" href="#" onclick="resolveAlert(1)">Mark Resolved</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="escalateAlert(1)">Escalate</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAlert(1)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Database Connection Timeout</h6>
                                                <p class="text-xs text-secondary mb-0">Multiple database connection timeouts reported</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-warning">HIGH</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Database</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-secondary">Acknowledged</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">DB Admin</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(1)->format('M d, H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewAlert(2)">View Details</a></li>
                                                <li><a class="dropdown-item text-success" href="#" onclick="resolveAlert(2)">Mark Resolved</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="escalateAlert(2)">Escalate</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAlert(2)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Disk Space Warning</h6>
                                                <p class="text-xs text-secondary mb-0">Disk usage reached 85% on server-02</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-info">MEDIUM</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">System</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Resolved</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">Sys Admin</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(3)->format('M d, H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewAlert(3)">View Details</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAlert(3)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted">Showing 1 to 3 of 3 entries</span>
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Alert Modal -->
<div class="modal fade" id="createAlertModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create System Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.notifications.system-alerts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Alert Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="system">System</option>
                                    <option value="security">Security</option>
                                    <option value="performance">Performance</option>
                                    <option value="network">Network</option>
                                    <option value="database">Database</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assign To</label>
                                <select class="form-select" id="assigned_to" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    <option value="admin_team">Admin Team</option>
                                    <option value="devops_team">DevOps Team</option>
                                    <option value="db_admin">Database Admin</option>
                                    <option value="security_team">Security Team</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="resolution_steps" class="form-label">Suggested Resolution Steps</label>
                        <textarea class="form-control" id="resolution_steps" name="resolution_steps" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Alert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewAlert(id) {
    console.log('View alert details:', id);
    // Implement view details functionality
}

function acknowledgeAlert(id) {
    if (confirm('Mark this alert as acknowledged?')) {
        console.log('Acknowledge alert:', id);
        // Implement acknowledge functionality
    }
}

function assignAlert(id) {
    console.log('Assign alert:', id);
    // Implement assign functionality
}

function resolveAlert(id) {
    if (confirm('Mark this alert as resolved?')) {
        console.log('Resolve alert:', id);
        // Implement resolve functionality
    }
}

function escalateAlert(id) {
    if (confirm('Escalate this alert to higher priority?')) {
        console.log('Escalate alert:', id);
        // Implement escalate functionality
    }
}

function deleteAlert(id) {
    if (confirm('Are you sure you want to delete this alert?')) {
        console.log('Delete alert:', id);
        // Implement delete functionality
    }
}

function applyFilters() {
    const search = document.getElementById('search').value;
    const severity = document.getElementById('severityFilter').value;
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    console.log('Apply filters:', { search, severity, status, category, date });
    // Implement filter functionality
}
</script>
@endsection
