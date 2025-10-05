@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;"
                         alt="Profile Photo">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px; font-size: 3rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <p class="text-muted">{{ $user->mobile }}</p>
                
                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Account Information</h6>
                <hr>
                <div class="mb-2">
                    <small class="text-muted">Member Since</small>
                    <p class="mb-0">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Email Verified</small>
                    <p class="mb-0">
                        @if($user->email_verified)
                            <i class="fas fa-check-circle text-success"></i> Verified
                        @else
                            <i class="fas fa-times-circle text-danger"></i> Not Verified
                        @endif
                    </p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Mobile Verified</small>
                    <p class="mb-0">
                        @if($user->mobile_verified)
                            <i class="fas fa-check-circle text-success"></i> Verified
                        @else
                            <i class="fas fa-times-circle text-danger"></i> Not Verified
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email (Cannot be changed)</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                   value="{{ old('mobile', $user->mobile) }}" required maxlength="10">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth (Cannot be changed)</label>
                            <input type="date" class="form-control" value="{{ $user->date_of_birth->format('Y-m-d') }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" value="{{ $user->age }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ $user->gender }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Group</label>
                            <input type="text" class="form-control" value="{{ $user->blood_group }}" disabled>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Update Photo</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                   accept=".jpg,.jpeg,.png">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6>Change Password (Optional)</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection