@extends('layouts.dashboard')

@section('title', 'Support Tickets')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Support Tickets</h4>
            <a href="{{ route('user.support-tickets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Ticket
            </a>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Support Tickets</h5>
            </div>
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Category</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <strong>{{ $ticket->ticket_number ?? '#' . $ticket->id }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.support-tickets.show', $ticket->id) }}" 
                                               class="text-decoration-none">
                                                {{ $ticket->subject }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'open' => 'primary',
                                                    'in_progress' => 'warning',
                                                    'resolved' => 'success',
                                                    'closed' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'low' => 'info',
                                                    'medium' => 'warning',
                                                    'high' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $priorityColors[$ticket->priority] ?? 'secondary' }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(isset($ticket->category))
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $ticket->created_at->format('d M Y') }}<br>
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('user.support-tickets.show', $ticket->id) }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($ticket->status === 'open')
                                                    <a href="{{ route('user.support-tickets.edit', $ticket->id) }}" 
                                                       class="btn btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No support tickets yet</h5>
                        <p>Create a ticket if you need any assistance</p>
                        <a href="{{ route('user.support-tickets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Your First Ticket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
