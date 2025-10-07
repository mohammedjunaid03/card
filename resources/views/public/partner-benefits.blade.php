@extends('layouts.app')

@section('title', 'Partner Benefits - KCC HealthCard')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Partner Benefits</h1>
                <p class="lead">Discover the advantages of joining India's largest healthcare network</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Overview -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose KCC HealthCard?</h2>
            <p class="text-muted">Join over 500+ hospitals already benefiting from our network</p>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <h5 class="mb-0">Increased Patient Volume</h5>
                        </div>
                        <p class="text-muted">Access to millions of health card holders across India. Our network ensures a steady stream of patients to your hospital.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Guaranteed patient referrals</li>
                            <li><i class="fas fa-check text-success me-2"></i> Priority listing in search results</li>
                            <li><i class="fas fa-check text-success me-2"></i> Marketing support and promotion</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                            <h5 class="mb-0">Revenue Growth</h5>
                        </div>
                        <p class="text-muted">Boost your hospital's revenue with our comprehensive patient referral system and flexible pricing models.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Flexible discount structures</li>
                            <li><i class="fas fa-check text-success me-2"></i> Transparent billing system</li>
                            <li><i class="fas fa-check text-success me-2"></i> Quick payment processing</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-mobile-alt fa-lg"></i>
                            </div>
                            <h5 class="mb-0">Digital Integration</h5>
                        </div>
                        <p class="text-muted">Seamlessly integrate with our digital platform for easy patient management and streamlined operations.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> QR code verification system</li>
                            <li><i class="fas fa-check text-success me-2"></i> Real-time patient data</li>
                            <li><i class="fas fa-check text-success me-2"></i> Automated billing integration</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="fas fa-shield-alt fa-lg"></i>
                            </div>
                            <h5 class="mb-0">Trust & Credibility</h5>
                        </div>
                        <p class="text-muted">Enhance your hospital's reputation by being part of India's most trusted healthcare network.</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i> Verified partner status</li>
                            <li><i class="fas fa-check text-success me-2"></i> Quality assurance programs</li>
                            <li><i class="fas fa-check text-success me-2"></i> Patient feedback system</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Financial Benefits -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Financial Benefits</h2>
            <p class="text-muted">Transparent and profitable partnership model</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                    <h5>Flexible Pricing</h5>
                    <p class="text-muted">Set your own discount rates based on your services and profit margins.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h5>Quick Payments</h5>
                    <p class="text-muted">Receive payments within 7-15 days of service delivery through our secure payment system.</p>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-chart-bar fa-2x"></i>
                    </div>
                    <h5>Analytics Dashboard</h5>
                    <p class="text-muted">Track your performance, patient flow, and revenue through our comprehensive analytics.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Support & Training -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Support & Training</h2>
            <p class="text-muted">We provide comprehensive support to ensure your success</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Round-the-clock technical and operational support for all your needs.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h5>Training Programs</h5>
                        <p class="text-muted">Comprehensive training for your staff on our platform and processes.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5>Technical Integration</h5>
                        <p class="text-muted">Seamless integration with your existing hospital management systems.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Join Our Network?</h2>
        <p class="lead mb-4">Start your journey with KCC HealthCard today and unlock new opportunities for growth</p>
        <a href="{{ route('register-hospital') }}" class="btn btn-light btn-lg me-3">
            <i class="fas fa-hospital"></i> Register Your Hospital
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
            <i class="fas fa-phone"></i> Contact Us
        </a>
    </div>
</section>
@endsection
