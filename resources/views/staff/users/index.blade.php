@extends('layouts.dashboard')

@section('title', 'Registered Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registered Users</h5>
                <a href="{{ route('staff.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Register New User
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Age</th>
                                <th>Blood Group</th>
                                <th>Registration Date</th>
                                <th>Health Card</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->age }} years</td>
                                    <td>{{ $user->blood_group }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($user->healthCard)
                                            <span class="badge bg-success">Generated</span>
                                        @else
                                            <span class="badge bg-warning">Not Generated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($user->healthCard)
                                                <a href="{{ route('staff.health-card.download', $user) }}" 
                                                   class="btn btn-sm btn-success" title="Download Health Card">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('staff.health-card.print', $user) }}" 
                                                   class="btn btn-sm btn-info" title="Print Health Card" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="generateHealthCard({{ $user->id }})" 
                                                        title="Generate Health Card">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Generate Health Card Modal -->
<div class="modal fade" id="generateCardModal" tabindex="-1" aria-labelledby="generateCardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generateCardModalLabel">Generate Health Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generateCardForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to generate a health card for this user?</p>
                    <p class="text-muted">This will create a PDF health card with QR code that can be downloaded and printed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Health Card</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateHealthCard(userId) {
    document.getElementById('generateCardForm').action = `/staff/users/${userId}/generate-card`;
    const modal = new bootstrap.Modal(document.getElementById('generateCardModal'));
    modal.show();
}
</script>
@endsection
