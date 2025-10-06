@extends('layouts.app')

@section('title', 'About Us - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">About Health Card System</h1>
                <p class="lead">Making healthcare accessible and affordable for everyone</p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Our Mission</h2>
                    <p class="text-muted">To democratize healthcare access by providing affordable medical services through our extensive network of partner hospitals</p>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                                <h5>Healthcare for All</h5>
                                <p class="text-muted">We believe quality healthcare should be accessible to everyone, regardless of their financial situation.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-3x text-primary mb-3"></i>
                                <h5>Trusted Partnerships</h5>
                                <p class="text-muted">We work with verified hospitals and healthcare providers to ensure quality service delivery.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-5">
                    <h3 class="fw-bold mb-4">Our Story</h3>
                    <p class="lead">Founded in 2024, Health Card System was born out of a simple yet powerful vision: to make healthcare affordable and accessible for every Indian citizen.</p>
                    
                    <p>We recognized that many people delay or avoid medical treatment due to financial constraints. Our platform bridges this gap by connecting patients with quality healthcare providers who offer significant discounts on medical services.</p>
                    
                    <p>Through our digital health card system, we've helped thousands of families save money on healthcare while ensuring they receive the best possible medical care.</p>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-4 text-center mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5>50,000+</h5>
                        <p class="text-muted">Happy Users</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h5>500+</h5>
                        <p class="text-muted">Partner Hospitals</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <h5>₹50 Cr+</h5>
                        <p class="text-muted">Total Savings</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <h3 class="fw-bold mb-4">Our Values</h3>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                            <h6>Security</h6>
                            <p class="small text-muted">Your data is protected with enterprise-grade security</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                            <h6>Reliability</h6>
                            <p class="small text-muted">24/7 system availability and support</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <i class="fas fa-star fa-2x text-primary mb-2"></i>
                            <h6>Quality</h6>
                            <p class="small text-muted">Only verified hospitals in our network</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <i class="fas fa-heart fa-2x text-primary mb-2"></i>
                            <h6>Care</h6>
                            <p class="small text-muted">Your health and wellbeing is our priority</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Join Our Mission</h2>
        <p class="lead mb-4">Be part of the healthcare revolution. Get your health card today and start saving on medical expenses.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-id-card"></i> Get Your Health Card
        </a>
    </div>
</section>
@endsection
