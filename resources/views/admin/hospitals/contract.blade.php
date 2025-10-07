@extends('layouts.dashboard')

@section('title', 'Hospital Contract Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Contract Management - {{ $hospital->name }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.hospitals.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Hospitals
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-contract"></i> Contract Details</h5>
                </div>
                <div class="card-body">
                    @if($hospital->contract_start_date && $hospital->contract_end_date)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contract Status</label>
                                    <div>{!! $hospital->getContractStatusBadge() !!}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Days Remaining</label>
                                    <div>
                                        @if($hospital->getContractDaysRemaining() !== null)
                                            @if($hospital->getContractDaysRemaining() > 0)
                                                <span class="badge bg-info">{{ $hospital->getContractDaysRemaining() }} days</span>
                                            @else
                                                <span class="badge bg-danger">Expired {{ abs($hospital->getContractDaysRemaining()) }} days ago</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No contract dates set</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contract Start Date</label>
                                    <div class="form-control-plaintext">{{ $hospital->contract_start_date ? $hospital->contract_start_date->format('d M Y') : 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contract End Date</label>
                                    <div class="form-control-plaintext">{{ $hospital->contract_end_date ? $hospital->contract_end_date->format('d M Y') : 'Not set' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($hospital->contract_notes)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contract Notes</label>
                                <div class="form-control-plaintext">{{ $hospital->contract_notes }}</div>
                            </div>
                        @endif

                        @if($hospital->contract_document_path)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Contract Document</label>
                                <div>
                                    <a href="{{ asset('storage/' . $hospital->contract_document_path) }}" 
                                       target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf"></i> View Contract Document
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Contract Actions -->
                        @if($hospital->isContractExpired())
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Contract Expired</h6>
                                <p class="mb-3">This hospital's contract has expired. Please take action:</p>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#renewContractModal">
                                        <i class="fas fa-redo"></i> Renew Contract
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#terminateContractModal">
                                        <i class="fas fa-times"></i> Terminate Contract
                                    </button>
                                </div>
                            </div>
                        @elseif($hospital->getContractDaysRemaining() <= 30 && $hospital->getContractDaysRemaining() > 0)
                            <div class="alert alert-info">
                                <h6><i class="fas fa-clock"></i> Contract Expiring Soon</h6>
                                <p class="mb-3">This hospital's contract will expire in {{ $hospital->getContractDaysRemaining() }} days.</p>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#renewContractModal">
                                        <i class="fas fa-redo"></i> Renew Contract
                                    </button>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#terminateContractModal">
                                        <i class="fas fa-times"></i> Terminate Contract
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <h6><i class="fas fa-check-circle"></i> Contract Active</h6>
                                <p class="mb-3">This hospital's contract is active and in good standing.</p>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editContractModal">
                                        <i class="fas fa-edit"></i> Edit Contract
                                    </button>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#terminateContractModal">
                                        <i class="fas fa-times"></i> Terminate Contract
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> No Contract Information</h6>
                            <p>This hospital doesn't have contract information set up yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContractModal">
                                <i class="fas fa-plus"></i> Create Contract
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hospital Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hospital Name</label>
                        <div class="form-control-plaintext">{{ $hospital->name }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <div class="form-control-plaintext">{{ $hospital->email }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mobile</label>
                        <div class="form-control-plaintext">{{ $hospital->mobile }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">License Number</label>
                        <div class="form-control-plaintext">{{ $hospital->license_number }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div>
                            @if($hospital->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($hospital->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Contract History</h5>
                </div>
                <div class="card-body">
                    @if($hospital->contract_created_at)
                        <div class="mb-2">
                            <small class="text-muted">Created:</small><br>
                            <strong>{{ $hospital->contract_created_at->format('d M Y H:i') }}</strong>
                        </div>
                    @endif
                    @if($hospital->contract_updated_at)
                        <div class="mb-2">
                            <small class="text-muted">Last Updated:</small><br>
                            <strong>{{ $hospital->contract_updated_at->format('d M Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Contract Modal -->
<div class="modal fade" id="createContractModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.contract.store', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contract_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="contract_start_date" name="contract_start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contract_end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="contract_end_date" name="contract_end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="contract_document" class="form-label">Contract Document</label>
                        <input type="file" class="form-control" id="contract_document" name="contract_document" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Upload contract document (optional)</small>
                    </div>
                    <div class="mb-3">
                        <label for="contract_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="contract_notes" name="contract_notes" rows="3" placeholder="Contract terms and conditions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Contract Modal -->
<div class="modal fade" id="editContractModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.contract.update', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contract_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_contract_start_date" name="contract_start_date" 
                                       value="{{ $hospital->contract_start_date ? $hospital->contract_start_date->format('Y-m-d') : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_contract_end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_contract_end_date" name="contract_end_date" 
                                       value="{{ $hospital->contract_end_date ? $hospital->contract_end_date->format('Y-m-d') : '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contract_document" class="form-label">Contract Document</label>
                        <input type="file" class="form-control" id="edit_contract_document" name="contract_document" accept=".pdf,.doc,.docx">
                        @if($hospital->contract_document_path)
                            <small class="text-muted">Current: <a href="{{ asset('storage/' . $hospital->contract_document_path) }}" target="_blank">View Document</a></small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="edit_contract_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_contract_notes" name="contract_notes" rows="3">{{ $hospital->contract_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Renew Contract Modal -->
<div class="modal fade" id="renewContractModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Renew Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.contract.renew', $hospital->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This will create a new contract period for the hospital.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="renew_contract_start_date" class="form-label">New Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="renew_contract_start_date" name="contract_start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="renew_contract_end_date" class="form-label">New End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="renew_contract_end_date" name="contract_end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="renew_contract_document" class="form-label">New Contract Document</label>
                        <input type="file" class="form-control" id="renew_contract_document" name="contract_document" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Upload new contract document (optional)</small>
                    </div>
                    <div class="mb-3">
                        <label for="renew_contract_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="renew_contract_notes" name="contract_notes" rows="3" placeholder="Renewal terms and conditions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Renew Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Terminate Contract Modal -->
<div class="modal fade" id="terminateContractModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terminate Contract</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hospitals.contract.terminate', $hospital->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> This action will terminate the hospital's contract. This action cannot be undone.
                    </div>
                    <div class="mb-3">
                        <label for="termination_reason" class="form-label">Termination Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="termination_reason" name="termination_reason" rows="3" 
                                  placeholder="Please provide a reason for contract termination..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Terminate Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Set default start date to today for new contracts
document.getElementById('contract_start_date').valueAsDate = new Date();
document.getElementById('renew_contract_start_date').valueAsDate = new Date();

// Auto-calculate end date (1 year from start)
function setDefaultEndDate(startDateInput, endDateInput) {
    const startDate = new Date(startDateInput.value);
    if (startDate) {
        const endDate = new Date(startDate);
        endDate.setFullYear(endDate.getFullYear() + 1);
        endDateInput.value = endDate.toISOString().split('T')[0];
    }
}

document.getElementById('contract_start_date').addEventListener('change', function() {
    setDefaultEndDate(this, document.getElementById('contract_end_date'));
});

document.getElementById('renew_contract_start_date').addEventListener('change', function() {
    setDefaultEndDate(this, document.getElementById('renew_contract_end_date'));
});
</script>
@endsection