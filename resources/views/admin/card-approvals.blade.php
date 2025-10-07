@extends('layouts.dashboard')

@section('title', 'Card Approvals')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pending Health Card Approvals ({{ $pendingCards->count() }})</h5>
                <div>
                    <button onclick="approveAll()" class="btn btn-success btn-sm" {{ $pendingCards->count() == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check-double"></i> Approve All
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($pendingCards->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User Details</th>
                                    <th>Card Information</th>
                                    <th>Application Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingCards as $card)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($card->user->photo_path)
                                                    <img src="{{ asset('storage/' . $card->user->photo_path) }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;"
                                                         alt="Photo">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($card->user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $card->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $card->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>Card #:</strong> {{ $card->card_number }}<br>
                                            <strong>Valid Until:</strong> {{ $card->expiry_date ? $card->expiry_date->format('d M Y') : 'Not Set' }}<br>
                                            <strong>Blood Group:</strong> <span class="badge bg-danger">{{ $card->user->blood_group }}</span>
                                        </td>
                                        <td>{{ $card->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-warning">Pending Approval</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $card->user->id) }}" 
                                                   class="btn btn-primary" title="View User Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <form method="POST" action="{{ route('admin.card-approvals.approve', $card->id) }}" 
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" 
                                                            onclick="return confirm('Approve this health card?')" title="Approve Card">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-warning dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.users.show', $card->user->id) }}" class="dropdown-item">
                                                            <i class="fas fa-user"></i> View User Profile
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.users.edit', $card->user->id) }}" class="dropdown-item">
                                                            <i class="fas fa-edit"></i> Edit User
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" 
                                                                onclick="rejectCard({{ $card->id }})">
                                                            <i class="fas fa-times"></i> Reject Card
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-id-card fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No pending card approvals</h5>
                        <p class="text-muted">All health cards have been processed.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Card Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Health Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
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
@endsection

@section('scripts')
<script>
function approveAll() {
    if (confirm('Are you sure you want to approve all pending health cards?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.card-approvals.approve-all") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

function rejectCard(cardId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.card-approvals.reject", ":id") }}'.replace(':id', cardId);
    modal.show();
}
</script>
@endsection
