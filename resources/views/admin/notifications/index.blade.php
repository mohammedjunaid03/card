@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Send Notification</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                    <i class="fas fa-plus"></i> Send Notification
                </button>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Notifications ({{ $notifications->total() }})</h5>
                <div>
                    <button onclick="markAllAsRead()" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Type</th>
                                    <th>Priority</th>
                                    <th>Sent To</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                        <td>
                                            <strong>{{ $notification->title }}</strong>
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                                                {{ Str::limit($notification->message, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $notification->type == 'info' ? 'info' : ($notification->type == 'warning' ? 'warning' : 'success') }}">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $notification->priority == 'high' ? 'danger' : ($notification->priority == 'medium' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($notification->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($notification->target_type) }}</span>
                                        </td>
                                        <td>
                                            @if($notification->is_read)
                                                <span class="badge bg-success">Read</span>
                                            @else
                                                <span class="badge bg-warning">Unread</span>
                                            @endif
                                        </td>
                                        <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-primary" 
                                                        onclick="viewNotification({{ $notification->id }})" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-warning dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if(!$notification->is_read)
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.notifications.mark-read', $notification->id) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check"></i> Mark as Read
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" 
                                                              onsubmit="return confirm('Delete this notification?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications found</h5>
                        <p class="text-muted">Start by sending your first notification to users.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.notifications.send') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="info">Information</option>
                                    <option value="warning">Warning</option>
                                    <option value="success">Success</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority *</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="target_type" class="form-label">Send To *</label>
                                <select class="form-control" id="target_type" name="target_type" required>
                                    <option value="all">All Users</option>
                                    <option value="users">Users Only</option>
                                    <option value="hospitals">Hospitals Only</option>
                                    <option value="staff">Staff Only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Notification Modal -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="notificationDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function markAllAsRead() {
    if (confirm('Mark all notifications as read?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.notifications.mark-all-read") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

function viewNotification(id) {
    // In a real application, you would fetch the notification details via AJAX
    const modal = new bootstrap.Modal(document.getElementById('viewNotificationModal'));
    document.getElementById('notificationDetails').innerHTML = `
        <div class="alert alert-info">
            <h6>Notification #${id}</h6>
            <p>This is a sample notification content. In a real application, this would be fetched from the server.</p>
            <small class="text-muted">Created: ${new Date().toLocaleString()}</small>
        </div>
    `;
    modal.show();
}
</script>
@endsection
