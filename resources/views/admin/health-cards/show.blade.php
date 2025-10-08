@extends('layouts.dashboard')

@section('title', 'Health Card Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Health Card Details</h2>
                <a href="{{ route('admin.health-cards.pending') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Pending
                </a>
            </div>

            <div class="row">
                <!-- User Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($healthCard->user->photo_path)
                                    <img src="{{ asset('storage/' . $healthCard->user->photo_path) }}" 
                                         alt="User Photo" class="rounded-circle" width="100" height="100">
                                @else
                                    <div class="bg-secondary rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                         style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $healthCard->user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Birth:</strong></td>
                                    <td>{{ $healthCard->user->date_of_birth->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Age:</strong></td>
                                    <td>{{ $healthCard->user->age }} years</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ $healthCard->user->gender }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blood Group:</strong></td>
                                    <td>{{ $healthCard->user->blood_group }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $healthCard->user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mobile:</strong></td>
                                    <td>{{ $healthCard->user->mobile }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $healthCard->user->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Health Card Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Health Card Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Card Number:</strong></td>
                                    <td><span class="badge bg-primary">{{ $healthCard->card_number }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($healthCard->approval_status === 'pending')
                                            <span class="badge bg-warning">Pending Approval</span>
                                        @elseif($healthCard->approval_status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Issued Date:</strong></td>
                                    <td>{{ $healthCard->issued_date->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Expiry Date:</strong></td>
                                    <td>{{ $healthCard->expiry_date->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted:</strong></td>
                                    <td>{{ $healthCard->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                                @if($healthCard->approved_by)
                                    <tr>
                                        <td><strong>Approved By:</strong></td>
                                        <td>{{ $healthCard->approver->name ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Approved At:</strong></td>
                                        <td>{{ $healthCard->approved_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endif
                                @if($healthCard->rejection_reason)
                                    <tr>
                                        <td><strong>Rejection Reason:</strong></td>
                                        <td class="text-danger">{{ $healthCard->rejection_reason }}</td>
                                    </tr>
                                @endif
                            </table>

                            <!-- QR Code -->
                            <div class="text-center mt-3">
                                <h6>QR Code</h6>
                                @if($healthCard->qr_code_path && file_exists(storage_path('app/public/' . $healthCard->qr_code_path)))
                                    <img src="{{ asset('storage/' . $healthCard->qr_code_path) }}" 
                                         alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                @else
                                    <div class="bg-light border rounded p-3">
                                        <i class="fas fa-qrcode fa-2x text-muted"></i>
                                        <p class="text-muted mt-2">QR Code not available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($healthCard->approval_status === 'pending')
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Approval Actions</h5>
                                <p class="text-muted">Review the information above and take appropriate action.</p>
                                
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('admin.health-cards.approve', $healthCard) }}" 
                                          class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg me-2" 
                                                onclick="return confirm('Are you sure you want to approve this health card?')">
                                            <i class="fas fa-check me-2"></i>Approve Card
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger btn-lg" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal">
                                        <i class="fas fa-times me-2"></i>Reject Card
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.health-cards.reject', $healthCard) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Reject Health Card</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to reject the health card for <strong>{{ $healthCard->user->name }}</strong>?</p>
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
            @endif
        </div>
    </div>
</div>
@endsection
