@extends('layouts.dashboard')

@section('title', 'Document Verification')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Document Verification</h5>
            </div>
            <div class="card-body">
                <!-- Pending Users -->
                <div class="mb-5">
                    <h6 class="mb-3">Pending User Verifications</h6>
                    @if($pendingUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="verifyDocument({{ $user->id }}, 'user', 'approve')">
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="verifyDocument({{ $user->id }}, 'user', 'reject')">
                                                        Reject
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pendingUsers->links() }}
                    @else
                        <div class="alert alert-info">No pending user verifications.</div>
                    @endif
                </div>

                <!-- Pending Hospitals -->
                <div>
                    <h6 class="mb-3">Pending Hospital Verifications</h6>
                    @if($pendingHospitals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Hospital Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>License Number</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingHospitals as $hospital)
                                        <tr>
                                            <td>{{ $hospital->name }}</td>
                                            <td>{{ $hospital->email }}</td>
                                            <td>{{ $hospital->mobile }}</td>
                                            <td>{{ $hospital->license_number }}</td>
                                            <td>{{ $hospital->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="verifyDocument({{ $hospital->id }}, 'hospital', 'approve')">
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="verifyDocument({{ $hospital->id }}, 'hospital', 'reject')">
                                                        Reject
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pendingHospitals->links() }}
                    @else
                        <div class="alert alert-info">No pending hospital verifications.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Document Verification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verificationForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="verificationType" name="type">
                    <input type="hidden" id="verificationAction" name="action">
                    
                    <div class="mb-3">
                        <label for="verificationNotes" class="form-label">Verification Notes</label>
                        <textarea class="form-control" id="verificationNotes" name="notes" rows="3" 
                                  placeholder="Add any notes about the verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verifyDocument(id, type, action) {
    document.getElementById('verificationType').value = type;
    document.getElementById('verificationAction').value = action;
    document.getElementById('verificationForm').action = `/staff/verify-documents/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
    modal.show();
}
</script>
@endsection
