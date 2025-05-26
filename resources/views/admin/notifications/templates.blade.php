@extends('layouts.admin')

@section('title', 'Notification Templates')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="mb-0">Notification Templates</p>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                            <i class="fas fa-plus me-1"></i>
                            Create Template
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

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="search" placeholder="Search templates...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="in_app">In-App</option>
                                <option value="push">Push Notification</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Template</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usage Count</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Modified</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates ?? [] as $template)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $template['name'] ?? 'Welcome Email' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ Str::limit($template['description'] ?? 'Welcome new users to the platform', 50) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $template['type'] == 'email' ? 'info' : ($template['type'] == 'sms' ? 'warning' : 'primary') }}">
                                            {{ strtoupper($template['type'] ?? 'email') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $template['category'] ?? 'User Onboarding' }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ ($template['status'] ?? 'active') == 'active' ? 'success' : (($template['status'] ?? 'active') == 'inactive' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($template['status'] ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $template['usage_count'] ?? rand(10, 500) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $template['updated_at'] ?? now()->format('M d, Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editTemplate({{ $template['id'] ?? 1 }})">Edit</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateTemplate({{ $template['id'] ?? 1 }})">Duplicate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="previewTemplate({{ $template['id'] ?? 1 }})">Preview</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate({{ $template['id'] ?? 1 }})">Delete</a></li>
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
                                                <h6 class="mb-0 text-sm">Welcome Email</h6>
                                                <p class="text-xs text-secondary mb-0">Welcome new users to the platform</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-info">EMAIL</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">User Onboarding</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Active</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">234</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->format('M d, Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editTemplate(1)">Edit</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateTemplate(1)">Duplicate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="previewTemplate(1)">Preview</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate(1)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Password Reset</h6>
                                                <p class="text-xs text-secondary mb-0">Password reset instructions for users</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-info">EMAIL</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Security</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Active</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">89</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subDays(3)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editTemplate(2)">Edit</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateTemplate(2)">Duplicate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="previewTemplate(2)">Preview</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate(2)">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Subscription Reminder</h6>
                                                <p class="text-xs text-secondary mb-0">Remind users about subscription renewal</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-sm bg-gradient-warning">SMS</span></td>
                                    <td><span class="text-secondary text-xs font-weight-bold">Billing</span></td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Active</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">156</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ now()->subDays(7)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v text-xs"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editTemplate(3)">Edit</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="duplicateTemplate(3)">Duplicate</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="previewTemplate(3)">Preview</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTemplate(3)">Delete</a></li>
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

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Notification Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.notifications.templates.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Template Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="in_app">In-App</option>
                                    <option value="push">Push Notification</option>
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
                                    <option value="user_onboarding">User Onboarding</option>
                                    <option value="security">Security</option>
                                    <option value="billing">Billing</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="system">System</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject/Title</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="8" required></textarea>
                        <small class="form-text text-muted">You can use variables like @{{ name }}, @{{ email }}, @{{ company }} in your template</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editTemplate(id) {
    // Implement edit functionality
    console.log('Edit template:', id);
}

function duplicateTemplate(id) {
    // Implement duplicate functionality
    console.log('Duplicate template:', id);
}

function previewTemplate(id) {
    // Implement preview functionality
    console.log('Preview template:', id);
}

function deleteTemplate(id) {
    if (confirm('Are you sure you want to delete this template?')) {
        // Implement delete functionality
        console.log('Delete template:', id);
    }
}

// Search functionality
document.getElementById('search').addEventListener('input', function() {
    // Implement search functionality
    console.log('Search:', this.value);
});

// Filter functionality
document.getElementById('typeFilter').addEventListener('change', function() {
    console.log('Type filter:', this.value);
});

document.getElementById('statusFilter').addEventListener('change', function() {
    console.log('Status filter:', this.value);
});
</script>
@endsection
