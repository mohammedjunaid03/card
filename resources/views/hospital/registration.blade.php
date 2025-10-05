@extends('layouts.app')

@section('title', 'Hospital Registration')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Hospital Registration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.register') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <h5 class="mb-3">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hospital Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                       value="{{ old('mobile') }}" required maxlength="10">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City *</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                       value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State *</label>
                                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
                                       value="{{ old('state') }}" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pincode *</label>
                                <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror" 
                                       value="{{ old('pincode') }}" required maxlength="6">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Full Address *</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Documents</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hospital Logo</label>
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                                       accept=".jpg,.jpeg,.png">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Registration Document *</label>
                                <input type="file" name="registration_doc" class="form-control @error('registration_doc') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('registration_doc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PAN Card *</label>
                                <input type="file" name="pan_doc" class="form-control @error('pan_doc') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('pan_doc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">GST Certificate</label>
                                <input type="file" name="gst_doc" class="form-control @error('gst_doc') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                @error('gst_doc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Login Credentials</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Register Hospital</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>Already registered? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection