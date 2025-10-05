@extends('layouts.dashboard')

@section('title', 'Support Tickets')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create New Ticket</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.support-tickets.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                  rows="5" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Submit Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Tickets</h5>
            </div>
            <div class="card-body">
                @if($tickets->count() > 0)
                    <div class="list-group">
                        @foreach($tickets as $ticket)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('user.support-tickets.show', $ticket->id) }}" 
                                               class="text-decoration-none">
                                                {{ $ticket->subject }}
                                            </a>
                                        </h6>
                                        <p class="mb-1 text-muted small">{{ Str::limit($ticket->message, 100) }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-ticket-alt"></i> {{ $ticket->ticket_number }} | 
                                            <i class="fas fa-clock"></i> {{ $ticket->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $statusColors = [
                                                'open' => 'primary',
                                                'in_progress' => 'warning',
                                                'resolved' => 'success',
                                                'closed' => 'secondary'
                                            ];
                                            $priorityColors = [
                                                'low' => 'info',
                                                'medium' => 'warning',
                                                'high' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$ticket->status] }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                        <br>
                                        <span class="badge bg-{{ $priorityColors[$ticket->priority] }} mt-1">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($ticket->admin_response)
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <strong class="text-success">Admin Response:</strong>
                                        <p class="mb-0 mt-2">{{ $ticket->admin_response }}</p>
                                        @if($ticket->resolved_at)
                                            <small class="text-muted">
                                                Resolved on {{ $ticket->resolved_at->format('d M Y H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        {{ $tickets->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No support tickets yet</h5>
                        <p>Create a ticket if you need any assistance</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection