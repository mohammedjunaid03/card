@extends('layouts.dashboard')

@section('title', 'Pending Health Card Approvals')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Pending Health Card Approvals</h2>
                <div class="btn-group">
                    <a href="{{ route('admin.health-cards.all') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>All Cards
                    </a>
                    <a href="{{ route('admin.health-cards.verify') }}" class="btn btn-outline-info">
                        <i class="fas fa-search me-2"></i>Verify Card
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($pendingCards->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Card Number</th>
                                        <th>User Details</th>
                                        <th>Contact Info</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingCards as $card)
                                        <tr>
                                            <td>
                                                <strong>{{ $card->card_number }}</strong>
                                                <br>
                                                <small class="text-muted">Valid: {{ $card->expiry_date->format('d-m-Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($card->user->photo_path)
                                                        <img src="{{ asset('storage/' . $card->user->photo_path) }}" 
                                                             alt="User Photo" class="rounded-circle me-2" width="40" height="40">
                                                    @else
                                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $card->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $card->user->age }} years, {{ $card->user->gender }}
                                                            <br>
                                                            Blood Group: {{ $card->user->blood_group }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <i class="fas fa-envelope me-1"></i> {{ $card->user->email }}
                                                    <br>
                                                    <i class="fas fa-phone me-1"></i> {{ $card->user->mobile }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $card->created_at->format('d-m-Y H:i') }}
                                                <br>
                                                <small class="text-muted">{{ $card->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.health-cards.show', $card) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.health-cards.approve', $card) }}" 
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                onclick="return confirm('Are you sure you want to approve this health card?')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $card->id }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $card->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.health-cards.reject', $card) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Health Card</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to reject the health card for <strong>{{ $card->user->name }}</strong>?</p>
                                                            <div class="mb-3">
                                                                <label for="rejection_reason" class="form-label">Reason for rejection *</label>
                                                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                                                          rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Card</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $pendingCards->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Pending Approvals</h4>
                        <p class="text-muted">All health card applications have been processed.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
