@extends('layouts.app')

@section('title', 'Verify OTP - Health Card System')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Verify Your Email
                    </h4>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                        <h5>Check Your Email</h5>
                        <p class="text-muted">
                            We've sent a 6-digit verification code to your email address. 
                            Please enter the code below to complete your registration.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('otp.verify.submit') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="otp" class="form-label">Verification Code</label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                   id="otp" 
                                   name="otp" 
                                   placeholder="000000" 
                                   maxlength="6" 
                                   required 
                                   autofocus>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check me-2"></i>
                                Verify & Continue
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small">
                            Didn't receive the code? 
                            <a href="#" class="text-primary" id="resend-otp">Resend OTP</a>
                        </p>
                        <p class="text-muted small">
                            Wrong email? 
                            <a href="{{ route('register') }}" class="text-primary">Go back to registration</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-format OTP input
    document.getElementById('otp').addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Auto-submit when 6 digits are entered
        if (this.value.length === 6) {
            this.form.submit();
        }
    });

    // Resend OTP functionality
    document.getElementById('resend-otp').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show loading state
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        this.style.pointerEvents = 'none';
        
        // Simulate API call (replace with actual implementation)
        setTimeout(() => {
            this.innerHTML = 'Resend OTP';
            this.style.pointerEvents = 'auto';
            alert('OTP has been resent to your email address.');
        }, 2000);
    });

    // Focus on OTP input when page loads
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('otp').focus();
    });
</script>
@endpush
