@extends('layouts.app')

@section('title', 'How It Works - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">How It Works</h1>
                <p class="lead">Simple steps to get your health card and start saving</p>
            </div>
        </div>
    </div>
</section>

<!-- Steps Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Get Started in 4 Easy Steps</h2>
            <p class="text-muted">From registration to using your card at hospitals</p>
        </div>
        
        <div class="row">
            <!-- Step 1 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto;">
                            1
                        </div>
                        <h5 class="fw-bold">Register Online</h5>
                        <p class="text-muted">Fill out our simple registration form with your personal details, medical information, and upload required documents.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Personal Information</li>
                            <li><i class="fas fa-check text-success me-2"></i>Medical Details</li>
                            <li><i class="fas fa-check text-success me-2"></i>Document Upload</li>
                            <li><i class="fas fa-check text-success me-2"></i>Contact Verification</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto;">
                            2
                        </div>
                        <h5 class="fw-bold">Verify & Approve</h5>
                        <p class="text-muted">Our team verifies your documents and approves your application. You'll receive an OTP for email verification.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Document Verification</li>
                            <li><i class="fas fa-check text-success me-2"></i>Email OTP</li>
                            <li><i class="fas fa-check text-success me-2"></i>Application Review</li>
                            <li><i class="fas fa-check text-success me-2"></i>Approval Notification</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto;">
                            3
                        </div>
                        <h5 class="fw-bold">Get Your Card</h5>
                        <p class="text-muted">Your digital health card is generated instantly with a unique QR code. Download the PDF or access it from your dashboard.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Instant Generation</li>
                            <li><i class="fas fa-check text-success me-2"></i>QR Code Included</li>
                            <li><i class="fas fa-check text-success me-2"></i>PDF Download</li>
                            <li><i class="fas fa-check text-success me-2"></i>Mobile Access</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Step 4 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto;">
                            4
                        </div>
                        <h5 class="fw-bold">Start Saving</h5>
                        <p class="text-muted">Visit any partner hospital, show your health card, and enjoy instant discounts on medical services.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Find Hospitals</li>
                            <li><i class="fas fa-check text-success me-2"></i>Show Your Card</li>
                            <li><i class="fas fa-check text-success me-2"></i>Get Discounts</li>
                            <li><i class="fas fa-check text-success me-2"></i>Track Savings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Process -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Detailed Process</h2>
            <p class="text-muted">Everything you need to know about using your health card</p>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            Registration Process
                        </h5>
                        <p class="card-text">Our registration process is designed to be quick and secure:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>1.</strong> Enter your personal details (name, DOB, gender, address)</li>
                            <li class="mb-2"><strong>2.</strong> Provide medical information (blood group, medical history)</li>
                            <li class="mb-2"><strong>3.</strong> Upload Aadhaar card and photo (if available)</li>
                            <li class="mb-2"><strong>4.</strong> Verify email and mobile number with OTP</li>
                            <li class="mb-2"><strong>5.</strong> Set a secure password for your account</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            Card Features
                        </h5>
                        <p class="card-text">Your health card comes with powerful features:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>QR Code:</strong> Instant verification at hospitals</li>
                            <li class="mb-2"><strong>Digital Format:</strong> Access from any device</li>
                            <li class="mb-2"><strong>5-Year Validity:</strong> Long-term healthcare access</li>
                            <li class="mb-2"><strong>Secure:</strong> Encrypted and tamper-proof</li>
                            <li class="mb-2"><strong>Portable:</strong> No physical card needed</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-hospital text-primary me-2"></i>
                            Using at Hospitals
                        </h5>
                        <p class="card-text">How to use your card at partner hospitals:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>1.</strong> Find a partner hospital near you</li>
                            <li class="mb-2"><strong>2.</strong> Visit the hospital for treatment</li>
                            <li class="mb-2"><strong>3.</strong> Show your health card at reception</li>
                            <li class="mb-2"><strong>4.</strong> Hospital staff will scan your QR code</li>
                            <li class="mb-2"><strong>5.</strong> Discounts are applied automatically</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Track Your Savings
                        </h5>
                        <p class="card-text">Monitor your healthcare savings:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>Dashboard:</strong> View all your transactions</li>
                            <li class="mb-2"><strong>History:</strong> Complete discount history</li>
                            <li class="mb-2"><strong>Reports:</strong> Monthly and yearly summaries</li>
                            <li class="mb-2"><strong>Notifications:</strong> Updates on new offers</li>
                            <li class="mb-2"><strong>Support:</strong> 24/7 customer assistance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose Our Health Card?</h2>
            <p class="text-muted">Experience the benefits of digital healthcare</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-percentage fa-3x text-primary mb-3"></i>
                    <h5>Up to 50% Discount</h5>
                    <p class="text-muted">Save significantly on medical expenses with our partner hospitals</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                    <h5>Instant Access</h5>
                    <p class="text-muted">Get your card immediately after verification - no waiting period</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5>Secure & Private</h5>
                    <p class="text-muted">Your personal and medical data is encrypted and protected</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead mb-4">Join thousands of users who are already saving on healthcare</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
            <i class="fas fa-id-card"></i> Register Now
        </a>
    </div>
</section>
@endsection
