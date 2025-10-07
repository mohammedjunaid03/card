@extends('layouts.dashboard')

@section('title', 'Register New Hospital')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Register New Hospital</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.hospitals.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                       id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_number" class="form-label">License Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                       id="license_number" name="license_number" value="{{ old('license_number') }}" required>
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                    <div class="mb-3">
                        <label class="form-label">Services Offered <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($services as $service)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="services[]" value="{{ $service->id }}" 
                                               id="service_{{ $service->id }}"
                                               {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_{{ $service->id }}">
                                            {{ $service->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('services')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Hospital Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept=".jpg,.jpeg,.png">
                                <small class="form-text text-muted">Upload JPG, JPEG, or PNG file (max 2MB)</small>
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="registration_document" class="form-label">Registration Document <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('registration_document') is-invalid @enderror" 
                                       id="registration_document" name="registration_document" accept=".pdf" required>
                                <small class="form-text text-muted">Upload PDF file (max 5MB)</small>
                                @error('registration_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pan_document" class="form-label">PAN Document <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('pan_document') is-invalid @enderror" 
                                       id="pan_document" name="pan_document" accept=".pdf" required>
                                <small class="form-text text-muted">Upload PDF file (max 5MB)</small>
                                @error('pan_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="gst_document" class="form-label">GST Document <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('gst_document') is-invalid @enderror" 
                                       id="gst_document" name="gst_document" accept=".pdf" required>
                                <small class="form-text text-muted">Upload PDF file (max 5MB)</small>
                                @error('gst_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Register Hospital</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
