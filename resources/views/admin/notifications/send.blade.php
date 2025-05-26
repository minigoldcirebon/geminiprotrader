@extends('layouts.admin')

@section('title', 'Send Notification')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Send Notification</p>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.notifications.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Notification Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="security" {{ old('type') == 'security' ? 'selected' : '' }}>Security Alert</option>
                                        <option value="promotion" {{ old('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
                                        <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System Update</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="target_audience" class="form-label">Target Audience</label>
                                    <select class="form-select" id="target_audience" name="target_audience" required>
                                        <option value="">Select Audience</option>
                                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>All Users</option>
                                        <option value="subscribed" {{ old('target_audience') == 'subscribed' ? 'selected' : '' }}>Subscribed Users</option>
                                        <option value="free" {{ old('target_audience') == 'free' ? 'selected' : '' }}>Free Users</option>
                                        <option value="admin" {{ old('target_audience') == 'admin' ? 'selected' : '' }}>Admins Only</option>
                                        <option value="specific" {{ old('target_audience') == 'specific' ? 'selected' : '' }}>Specific Users</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_method" class="form-label">Delivery Method</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="in_app" name="delivery_method[]" value="in_app" {{ in_array('in_app', old('delivery_method', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="in_app">In-App Notification</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email" name="delivery_method[]" value="email" {{ in_array('email', old('delivery_method', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email">Email</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sms" name="delivery_method[]" value="sms" {{ in_array('sms', old('delivery_method', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sms">SMS</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="specific_users_section" style="display: none;">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="specific_users" class="form-label">Specific User IDs (comma separated)</label>
                                    <input type="text" class="form-control" id="specific_users" name="specific_users" value="{{ old('specific_users') }}" placeholder="1,2,3,4,5">
                                    <small class="form-text text-muted">Enter user IDs separated by commas</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_at" class="form-label">Schedule For Later (Optional)</label>
                                    <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                                    <input type="datetime-local" class="form-control" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_immediately" name="send_immediately" value="1" {{ old('send_immediately') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_immediately">
                                            Send Immediately
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.notifications.history') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('target_audience').addEventListener('change', function() {
    const specificUsersSection = document.getElementById('specific_users_section');
    if (this.value === 'specific') {
        specificUsersSection.style.display = 'block';
    } else {
        specificUsersSection.style.display = 'none';
    }
});

// Show/hide specific users section on page load if already selected
document.addEventListener('DOMContentLoaded', function() {
    const targetAudience = document.getElementById('target_audience');
    if (targetAudience.value === 'specific') {
        document.getElementById('specific_users_section').style.display = 'block';
    }
});
</script>
@endsection
