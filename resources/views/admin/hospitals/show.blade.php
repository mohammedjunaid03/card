@extends('layouts.dashboard')

@section('title', 'Hospital Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Hospital Details - {{ $hospital->name }}</h4>
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
                    <h5 class="mb-0"><i class="fas fa-hospital"></i> Hospital Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hospital Name</label>
                                <div class="form-control-plaintext">{{ $hospital->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">License Number</label>
                                <div class="form-control-plaintext">{{ $hospital->license_number }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <div class="form-control-plaintext">{{ $hospital->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Mobile</label>
                                <div class="form-control-plaintext">{{ $hospital->mobile }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <div class="form-control-plaintext">{{ $hospital->address }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">City</label>
                                <div class="form-control-plaintext">{{ $hospital->city }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">State</label>
                                <div class="form-control-plaintext">{{ $hospital->state }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pincode</label>
                                <div class="form-control-plaintext">{{ $hospital->pincode }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Registered Date</label>
                                <div class="form-control-plaintext">{{ $hospital->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contract Information -->
            @if($hospital->contract_start_date && $hospital->contract_end_date)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-contract"></i> Contract Information</h5>
                    </div>
                    <div class="card-body">
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
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contract Start Date</label>
                                    <div class="form-control-plaintext">{{ $hospital->contract_start_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contract End Date</label>
                                    <div class="form-control-plaintext">{{ $hospital->contract_end_date->format('d M Y') }}</div>
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
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Hospital
                        </a>
                        <a href="{{ route('admin.hospitals.contract', $hospital->id) }}" class="btn btn-info">
                            <i class="fas fa-file-contract"></i> Contract Management
                        </a>
                        
                        @if($hospital->status === 'pending')
                            <form method="POST" action="{{ route('admin.hospitals.approve', $hospital->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this hospital?')">
                                    <i class="fas fa-check"></i> Approve Hospital
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.hospitals.reject', $hospital->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this hospital?')">
                                    <i class="fas fa-times"></i> Reject Hospital
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if($hospital->logo_path)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Hospital Logo</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $hospital->logo_path) }}" 
                             class="img-fluid rounded" 
                             style="max-height: 200px;" 
                             alt="Hospital Logo">
                    </div>
                </div>
            @endif

            <!-- Documents -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Documents</h5>
                </div>
                <div class="card-body">
                    @if($hospital->registration_doc)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $hospital->registration_doc) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-file-pdf"></i> Registration Document
                            </a>
                        </div>
                    @endif
                    
                    @if($hospital->pan_doc)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $hospital->pan_doc) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-file-pdf"></i> PAN Document
                            </a>
                        </div>
                    @endif
                    
                    @if($hospital->gst_doc)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $hospital->gst_doc) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-file-pdf"></i> GST Document
                            </a>
                        </div>
                    @endif

                    @if(!$hospital->registration_doc && !$hospital->pan_doc && !$hospital->gst_doc)
                        <p class="text-muted text-center">No documents uploaded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
