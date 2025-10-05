@extends('layouts.dashboard')

@section('title', 'Hospital Profile')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($hospital->logo_path)
                    <img src="{{ asset('storage/' . $hospital->logo_path) }}" 
                         class="img-fluid rounded mb-3" 
                         style="max-height: 200px;"
                         alt="Hospital Logo">
                @else
                    <div class="bg-primary text-white rounded d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px; font-size: 3rem;">
                        {{ substr($hospital->name, 0, 1) }}
                    </div>
                @endif
                
                <h4>{{ $hospital->name }}</h4>
                <p class="text-muted">{{ $hospital->city }}, {{ $hospital->state }}</p>
                
                <span class="badge bg-{{ $hospital->status == 'approved' ? 'success' : 'warning' }}">
                    {{ ucfirst($hospital->status) }}
                </span>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Contact Information</h6>
                <hr>
                <div class="mb-2">
                    <small class="text-muted">Email</small>
                    <p class="mb-0">{{ $hospital->email }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Mobile</small>
                    <p class="mb-0">{{ $hospital->mobile }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Address</small>
                    <p class="mb-0">{{ $hospital->address }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Hospital Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hospital Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $hospital->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                   value="{{ old('mobile', $hospital->mobile) }}" required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                   value="{{ old('city', $hospital->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State *</label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
                                   value="{{ old('state', $hospital->state) }}" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pincode *</label>
                            <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror" 
                                   value="{{ old('pincode', $hospital->pincode) }}" required>
                            @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Full Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address', $hospital->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Update Logo</label>
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                                   accept=".jpg,.jpeg,.png">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6>Change Password (Optional)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="{{ route('hospital.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection