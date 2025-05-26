@extends('layouts.admin')

@section('title', 'Notification History')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Notification History</p>
                        <a href="{{ route('admin.notifications.send') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Send New Notification
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search" placeholder="Search notifications...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="announcement">Announcement</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="security">Security</option>
                                <option value="promotion">Promotion</option>
                                <option value="system">System</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="sent">Sent</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="failed">Failed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateFrom" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="dateTo" placeholder="To Date">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-primary" onclick="applyFilters()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Sent</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['total_sent'] ?? '1,248' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                <i class="fas fa-paper-plane text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Delivery Rate</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['delivery_rate'] ?? '98.2' }}%
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Open Rate</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['open_rate'] ?? '74.8' }}%
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fas fa-envelope-open text-lg opacity-10"></i>
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
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Scheduled</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $stats['scheduled'] ?? '12' }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fas fa-clock text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notification</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Recipients</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Stats</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sent Date</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications ?? [] as $notification)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $notification['title'] ?? 'System Maintenance Notice' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($notification['message'] ?? 'Scheduled maintenance window this weekend', 60) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $notification['type'] == 'maintenance' ? 'warning' : ($notification['type'] == 'security' ? 'danger' : 'info') }}">
                                            {{ strtoupper($notification['type'] ?? 'maintenance') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $notification['recipient_count'] ?? rand(100, 1000) }} users</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ ($notification['status'] ?? 'sent') == 'sent' ? 'success' : (($notification['status'] ?? 'sent') == 'failed' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($notification['status'] ?? 'sent') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="text-xs">
                                            <div>Delivered: <strong>{{ $notification['delivered'] ?? rand(95, 99) }}%</strong></div>
                                            <div>Opened: <strong>{{ $notification['opened'] ?? rand(70, 85) }}%</strong></div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $notification['sent_at'] ?? now()->subHours(rand(1, 48))->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewDetails({{ $notification['id'] ?? 1 }})">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewRecipients({{ $notification['id'] ?? 1 }})">View Recipients</a></li>
                                                @if(($notification['status'] ?? 'sent') == 'scheduled')
                                                <li><a class="dropdown-item" href="#" onclick="editScheduled({{ $notification['id'] ?? 1 }})">Edit</a></li>
                                                <li><a class="dropdown-item text-warning" href="#" onclick="cancelScheduled({{ $notification['id'] ?? 1 }})">Cancel</a></li>
                                                @endif
                                                @if(($notification['status'] ?? 'sent') == 'failed')
                                                <li><a class="dropdown-item text-info" href="#" onclick="resendNotification({{ $notification['id'] ?? 1 }})">Resend</a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateNotification({{ $notification['id'] ?? 1 }})">Duplicate</a></li>
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
                                                <h6 class="mb-0 text-sm">System Maintenance Notice</h6>
                                                <p class="text-xs text-secondary mb-0">Scheduled maintenance window this weekend</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-warning">MAINTENANCE</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">1,234 users</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Sent</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="text-xs">
                                            <div>Delivered: <strong>98%</strong></div>
                                            <div>Opened: <strong>76%</strong></div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subHours(2)->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewDetails(1)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewRecipients(1)">View Recipients</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateNotification(1)">Duplicate</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Security Alert</h6>
                                                <p class="text-xs text-secondary mb-0">Important security update notification</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-danger">SECURITY</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">892 users</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Sent</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="text-xs">
                                            <div>Delivered: <strong>99%</strong></div>
                                            <div>Opened: <strong>89%</strong></div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subDays(1)->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewDetails(2)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewRecipients(2)">View Recipients</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateNotification(2)">Duplicate</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Weekend Promotion</h6>
                                                <p class="text-xs text-secondary mb-0">Special weekend discount offer</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-info">PROMOTION</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">567 users</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">Scheduled</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="text-xs text-muted">
                                            Pending delivery
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->addHours(6)->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewDetails(3)">View Details</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="editScheduled(3)">Edit</a></li>
                                                <li><a class="dropdown-item text-warning" href="#" onclick="cancelScheduled(3)">Cancel</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateNotification(3)">Duplicate</a></li>
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

<script>
function viewDetails(id) {
    console.log('View details for notification:', id);
    // Implement view details functionality
}

function viewRecipients(id) {
    console.log('View recipients for notification:', id);
    // Implement view recipients functionality
}

function editScheduled(id) {
    console.log('Edit scheduled notification:', id);
    // Implement edit functionality
}

function cancelScheduled(id) {
    if (confirm('Are you sure you want to cancel this scheduled notification?')) {
        console.log('Cancel scheduled notification:', id);
        // Implement cancel functionality
    }
}

function resendNotification(id) {
    if (confirm('Are you sure you want to resend this notification?')) {
        console.log('Resend notification:', id);
        // Implement resend functionality
    }
}

function duplicateNotification(id) {
    console.log('Duplicate notification:', id);
    // Implement duplicate functionality
}

function applyFilters() {
    const search = document.getElementById('search').value;
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    console.log('Apply filters:', { search, type, status, dateFrom, dateTo });
    // Implement filter functionality
}
</script>
@endsection
