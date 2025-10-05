@extends('layouts.dashboard')

@section('title', 'Send Notifications')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Send New Notification</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.notifications.send') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Send To *</label>
                        <select name="recipient_type" class="form-control @error('recipient_type') is-invalid @enderror" 
                                id="recipient-type" required>
                            <option value="">Choose recipient</option>
                            <option value="all">All Users</option>
                            <option value="user">Specific User</option>
                            <option value="hospital">All Hospitals</option>
                            <option value="staff">All Staff</option>
                        </select>
                        @error('recipient_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3" id="specific-user" style="display: none;">
                        <label class="form-label">Select User</label>
                        <select name="recipient_id" class="form-control">
                            <option value="">Choose user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notification Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="info">Info</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                  rows="4" required></textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Notifications</h5>
            </div>
            <div class="card-body">
                @if($recentNotifications->count() > 0)
                    <div class="list-group">
                        @foreach($recentNotifications as $notification)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                        <p class="mb-1 small">{{ Str::limit($notification->message, 100) }}</p>
                                        <small class="text-muted">
                                            Sent to: {{ ucfirst($notification->recipient_type) }} | 
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $notification->type }}">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-4">No notifications sent yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#recipient-type').on('change', function() {
        if ($(this).val() === 'user') {
            $('#specific-user').show();
        } else {
            $('#specific-user').hide();
        }
    });
});
</script>
@endpush

