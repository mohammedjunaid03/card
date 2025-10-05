@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Notifications</h5>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form method="POST" action="{{ route('user.notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-double"></i> Mark All as Read
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            @php
                                                $typeIcons = [
                                                    'info' => 'info-circle text-info',
                                                    'success' => 'check-circle text-success',
                                                    'warning' => 'exclamation-triangle text-warning',
                                                    'error' => 'times-circle text-danger'
                                                ];
                                            @endphp
                                            <i class="fas fa-{{ $typeIcons[$notification->type] }} me-2"></i>
                                            <h6 class="mb-0">{{ $notification->title }}</h6>
                                        </div>
                                        <p class="mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('user.notifications.read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-check"></i> Mark Read
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">Read</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="p-3">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications</h5>
                        <p>You're all caught up!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection