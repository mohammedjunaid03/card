@extends('layouts.dashboard')

@section('title', 'Edit Hospital')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Hospital</h4>
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
                    <form method="POST" action="{{ route('admin.hospitals.update', $hospital->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Hospital Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $hospital->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $hospital->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                           id="mobile" name="mobile" value="{{ old('mobile', $hospital->mobile) }}" required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank to keep current password</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address', $hospital->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city', $hospital->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           id="state" name="state" value="{{ old('state', $hospital->state) }}" required>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('pincode') is-invalid @enderror" 
                                           id="pincode" name="pincode" value="{{ old('pincode', $hospital->pincode) }}" required>
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
                                           id="license_number" name="license_number" 
                                           value="{{ old('license_number', $hospital->license_number) }}"
                                           placeholder="e.g., LIC-000001 (leave blank to keep current)">
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave blank to keep the current license number</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="approved" {{ old('status', $hospital->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="pending" {{ old('status', $hospital->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ old('status', $hospital->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="blocked" {{ old('status', $hospital->status) === 'blocked' ? 'selected' : '' }}>Blocked</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    @if($hospital->logo_path)
                                        <small class="text-muted">Current: <a href="{{ asset('storage/' . $hospital->logo_path) }}" target="_blank">View</a></small>
                                    @endif
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
                                    @if($hospital->registration_doc)
                                        <small class="text-muted">Current: <a href="{{ asset('storage/' . $hospital->registration_doc) }}" target="_blank">View</a></small>
                                    @endif
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
                                    @if($hospital->pan_doc)
                                        <small class="text-muted">Current: <a href="{{ asset('storage/' . $hospital->pan_doc) }}" target="_blank">View</a></small>
                                    @endif
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
                            @if($hospital->gst_doc)
                                <small class="text-muted">Current: <a href="{{ asset('storage/' . $hospital->gst_doc) }}" target="_blank">View</a></small>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.hospitals.index') }}" class="btn btn-secondary me-2" 
                               onclick="return confirmCancel()">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Hospital
                            </button>
                        </div>
                    </form>
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
