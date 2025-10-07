@extends('layouts.dashboard')

@section('title', 'Add New Hospital')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Add New Hospital</h4>
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
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.hospitals.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Hospital Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="hospital@example.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Must be a valid email format and unique</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                           id="mobile" name="mobile" value="{{ old('mobile') }}" 
                                           placeholder="9876543210" required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">10-15 digits, can include +, -, spaces, parentheses</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Enter secure password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Min 6 chars, must include uppercase, lowercase, and number</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           id="state" name="state" value="{{ old('state') }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                                           id="pincode" name="pincode" value="{{ old('pincode') }}" required>
                                    @error('pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                           id="license_number" name="license_number" value="{{ old('license_number') }}"
                                           placeholder="LIC-123456 (auto-generated if empty)">
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to auto-generate, or enter custom license number</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contract Information Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-file-contract"></i> Contract Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contract_start_date" class="form-label">Contract Start Date</label>
                                            <input type="date" class="form-control @error('contract_start_date') is-invalid @enderror" 
                                                   id="contract_start_date" name="contract_start_date" 
                                                   value="{{ old('contract_start_date') }}">
                                            @error('contract_start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">When the contract becomes effective</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contract_end_date" class="form-label">Contract End Date</label>
                                            <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror" 
                                                   id="contract_end_date" name="contract_end_date" 
                                                   value="{{ old('contract_end_date') }}">
                                            @error('contract_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">When the contract expires</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="contract_document" class="form-label">Contract Document</label>
                                    <input type="file" class="form-control @error('contract_document') is-invalid @enderror" 
                                           id="contract_document" name="contract_document" accept=".pdf,.doc,.docx">
                                    @error('contract_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload contract document (PDF, DOC, DOCX - optional)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="contract_notes" class="form-label">Contract Notes</label>
                                    <textarea class="form-control @error('contract_notes') is-invalid @enderror" 
                                              id="contract_notes" name="contract_notes" rows="3" 
                                              placeholder="Additional contract details or terms...">{{ old('contract_notes') }}</textarea>
                                    @error('contract_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Optional notes about the contract terms</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Hospital Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                           id="logo" name="logo" accept="image/*">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload hospital logo (optional)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="registration_doc" class="form-label">Registration Document</label>
                                    <input type="file" class="form-control @error('registration_doc') is-invalid @enderror" 
                                           id="registration_doc" name="registration_doc" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('registration_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload registration document (optional)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pan_doc" class="form-label">PAN Document</label>
                                    <input type="file" class="form-control @error('pan_doc') is-invalid @enderror" 
                                           id="pan_doc" name="pan_doc" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('pan_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload PAN document (optional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="gst_doc" class="form-label">GST Document</label>
                            <input type="file" class="form-control @error('gst_doc') is-invalid @enderror" 
                                   id="gst_doc" name="gst_doc" accept=".pdf,.jpg,.jpeg,.png">
                            @error('gst_doc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload GST document (optional)</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary me-2" 
                               onclick="return confirmCancel()">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Hospital
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hospital Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                        <ul class="mb-0">
                            <li>All fields marked with <span class="text-danger">*</span> are required</li>
                            <li><strong>Email:</strong> Must be valid format (e.g., hospital@example.com) and unique</li>
                            <li><strong>Mobile:</strong> 10-15 digits, can include +, -, spaces, parentheses</li>
                            <li><strong>Password:</strong> Min 6 chars, must include uppercase, lowercase, and number</li>
                            <li><strong>License Number:</strong> Auto-generated if empty, or enter custom (letters, numbers, hyphens only)</li>
                            <li><strong>City/State:</strong> Letters and spaces only</li>
                            <li><strong>Pincode:</strong> Numbers only, 5-10 digits</li>
                            <li><strong>File Uploads:</strong> All documents are optional and can be uploaded later</li>
                            <li>Status can be changed after creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmCancel() {
    return confirm('Are you sure you want to cancel? Any unsaved changes will be lost.');
}
</script>
@endsection
