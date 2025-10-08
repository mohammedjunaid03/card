@extends('layouts.dashboard')

@section('title', 'Support Ticket #' . $ticket->id)

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Support Ticket #{{ $ticket->ticket_number ?? $ticket->id }}</h4>
            <div>
                @if($ticket->status === 'open')
                    <a href="{{ route('user.support-tickets.edit', $ticket->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Ticket
                    </a>
                @endif
                <a href="{{ route('user.support-tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Tickets
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $ticket->subject }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Your Message:</h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($ticket->message)) !!}
                    </div>
                </div>
                
                @if($ticket->admin_response)
                    <div class="mb-4">
                        <h6>Admin Response:</h6>
                        <div class="bg-success bg-opacity-10 p-3 rounded border-start border-success border-4">
                            {!! nl2br(e($ticket->admin_response)) !!}
                            @if($ticket->resolved_at)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-check-circle text-success"></i> 
                                        Resolved on {{ $ticket->resolved_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Your ticket is being reviewed by our support team. We'll respond as soon as possible.
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Ticket Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Status:</strong><br>
                    @php
                        $statusColors = [
                            'open' => 'primary',
                            'in_progress' => 'warning',
                            'resolved' => 'success',
                            'closed' => 'secondary'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Priority:</strong><br>
                    @php
                        $priorityColors = [
                            'low' => 'info',
                            'medium' => 'warning',
                            'high' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $priorityColors[$ticket->priority] ?? 'secondary' }} fs-6">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                
                @if(isset($ticket->category))
                <div class="mb-3">
                    <strong>Category:</strong><br>
                    <span class="badge bg-secondary fs-6">
                        {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                    </span>
                </div>
                @endif
                
                <div class="mb-3">
                    <strong>Created:</strong><br>
                    <small class="text-muted">
                        {{ $ticket->created_at->format('d M Y H:i') }}<br>
                        {{ $ticket->created_at->diffForHumans() }}
                    </small>
                </div>
                
                @if($ticket->updated_at && $ticket->updated_at != $ticket->created_at)
                <div class="mb-3">
                    <strong>Last Updated:</strong><br>
                    <small class="text-muted">
                        {{ $ticket->updated_at->format('d M Y H:i') }}<br>
                        {{ $ticket->updated_at->diffForHumans() }}
                    </small>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                @if($ticket->status === 'open')
                    <a href="{{ route('user.support-tickets.edit', $ticket->id) }}" class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="fas fa-edit"></i> Edit Ticket
                    </a>
                @endif
                
                <a href="{{ route('user.support-tickets.create') }}" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-plus"></i> Create New Ticket
                </a>
            </div>
        </div>
        
        <!-- Help -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Need More Help?</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">If this ticket doesn't resolve your issue, you can:</p>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-phone text-primary"></i> Call: +91-XXX-XXX-XXXX</li>
                    <li><i class="fas fa-envelope text-primary"></i> Email: support@healthcard.com</li>
                    <li><i class="fas fa-comments text-primary"></i> Live Chat (Available 9 AM - 6 PM)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
