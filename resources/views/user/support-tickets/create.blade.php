@extends('layouts.dashboard')

@section('title', 'Create Support Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create New Support Ticket</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.support-tickets.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                               value="{{ old('subject') }}" required placeholder="Brief description of your issue">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority *</label>
                            <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                                <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="card_issue" {{ old('category') == 'card_issue' ? 'selected' : '' }}>Health Card Issue</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control @error('message') is-invalid @enderror" 
                                  rows="8" required placeholder="Please provide detailed information about your issue...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Please be as detailed as possible to help us assist you better.
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.support-tickets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Tickets
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Need Help?</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Common Issues:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Health card not working</li>
                            <li><i class="fas fa-check text-success"></i> Login problems</li>
                            <li><i class="fas fa-check text-success"></i> Payment issues</li>
                            <li><i class="fas fa-check text-success"></i> Account verification</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Response Time:</h6>
                        <ul class="list-unstyled">
                            <li><span class="badge bg-danger">High Priority:</span> Within 2 hours</li>
                            <li><span class="badge bg-warning">Medium Priority:</span> Within 24 hours</li>
                            <li><span class="badge bg-info">Low Priority:</span> Within 48 hours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
