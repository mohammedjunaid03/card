@extends('layouts.dashboard')

@section('title', 'Edit Support Ticket #' . $ticket->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Support Ticket #{{ $ticket->ticket_number ?? $ticket->id }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.support-tickets.update', $ticket->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject', $ticket->subject) }}" required placeholder="Brief description of your issue">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority *</label>
                            <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                <option value="technical" {{ old('category', $ticket->category ?? '') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="billing" {{ old('category', $ticket->category ?? '') == 'billing' ? 'selected' : '' }}>Billing</option>
                                <option value="general" {{ old('category', $ticket->category ?? '') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="card_issue" {{ old('category', $ticket->category ?? '') == 'card_issue' ? 'selected' : '' }}>Health Card Issue</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                  rows="8" required placeholder="Please provide detailed information about your issue...">{{ old('message', $ticket->message) }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Please be as detailed as possible to help us assist you better.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('user.support-tickets.show', $ticket->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Ticket
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Current Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Current Ticket Status</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Status:</strong><br>
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
                    </div>
                    <div class="col-md-4">
                        <strong>Priority:</strong><br>
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
                    </div>
                    <div class="col-md-4">
                        <strong>Created:</strong><br>
                        <small class="text-muted">{{ $ticket->created_at->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Important Note -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i>
            <strong>Note:</strong> You can only edit tickets that are in "Open" status. Once a ticket is being processed or resolved, it cannot be modified.
        </div>
    </div>
</div>
@endsection
