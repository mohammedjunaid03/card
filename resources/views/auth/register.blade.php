@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Health Card Registration</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Age -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Age *</label>
                                <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                                       value="{{ old('age') }}" required>
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Gender -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender *</label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Blood Group -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blood Group *</label>
                                <select name="blood_group" class="form-control @error('blood_group') is-invalid @enderror" required>
                                    <option value="">Select Blood Group</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Address -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Address *</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Mobile -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                       value="{{ old('mobile') }}" required maxlength="10">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            
                            <!-- Aadhaar Upload -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aadhaar Card *</label>
                                <input type="file" name="aadhaar" class="form-control @error('aadhaar') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('aadhaar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Photo Upload -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Photo (Optional)</label>
                                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                       accept=".jpg,.jpeg,.png">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                            <p><a href="{{ route('login', ['role' => 'staff']) }}">Staff Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection