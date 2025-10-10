@extends('layouts.app')

@section('title', 'Home - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="content-wrapper">
        <div class="row align-items-center mx-0">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Your Health, Our Priority</h1>
                <p class="lead">Get instant access to quality healthcare with exclusive discounts at partner hospitals</p>
                <div class="mt-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-id-card"></i> Get Your Health Card
                    </a>
                    <a href="{{ route('how-it-works') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/hero-illustration.svg') }}" class="img-fluid" alt="Health Card" style="max-height: 300px;">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="content-wrapper">
        <div class="row text-center mx-0">
            <div class="col-md-3 stat-box">
                <i class="fas fa-users"></i>
                <h3 id="users-count">0</h3>
                <p>Registered Users</p>
            </div>
            <div class="col-md-3 stat-box">
                <i class="fas fa-hospital"></i>
                <h3 id="hospitals-count">0</h3>
                <p>Partner Hospitals</p>
            </div>
            <div class="col-md-3 stat-box">
                <i class="fas fa-id-card"></i>
                <h3 id="cards-count">0</h3>
                <p>Cards Issued</p>
            </div>
            <div class="col-md-3 stat-box">
                <i class="fas fa-rupee-sign"></i>
                <h3 id="savings-count">0</h3>
                <p>Total Savings (₹)</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-4 bg-light">
    <div class="content-wrapper">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Why Choose Health Card?</h2>
            <p class="text-muted">Experience hassle-free healthcare access with amazing benefits</p>
        </div>
        
        <div class="row mx-0">
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-percentage fa-3x text-primary mb-3"></i>
                        <h5>Exclusive Discounts</h5>
                        <p class="text-muted">Get up to 50% discount on medical services at partner hospitals</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-qrcode fa-3x text-primary mb-3"></i>
                        <h5>Digital Card</h5>
                        <p class="text-muted">Instant verification with QR code technology</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-network-wired fa-3x text-primary mb-3"></i>
                        <h5>Wide Network</h5>
                        <p class="text-muted">Access to 500+ hospitals across India</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                        <h5>Mobile Friendly</h5>
                        <p class="text-muted">Carry your card on your phone, no physical card needed</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Secure & Private</h5>
                        <p class="text-muted">Your data is encrypted and completely secure</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h5>24/7 Support</h5>
                        <p class="text-muted">Our support team is always ready to help you</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-4">
    <div class="content-wrapper">
        <div class="text-center mb-4">
            <h2 class="fw-bold">How It Works</h2>
            <p class="text-muted">Get started in 4 simple steps</p>
        </div>
        
        <div class="row mx-0">
            <div class="col-md-3 text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px; font-size: 2rem;">
                    1
                </div>
                <h5>Register Online</h5>
                <p class="text-muted">Fill the simple registration form with your details</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px; font-size: 2rem;">
                    2
                </div>
                <h5>Verify Email</h5>
                <p class="text-muted">Verify your email with OTP sent to your inbox</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px; font-size: 2rem;">
                    3
                </div>
                <h5>Get Your Card</h5>
                <p class="text-muted">Download your digital health card instantly</p>
            </div>
            
            <div class="col-md-3 text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px; font-size: 2rem;">
                    4
                </div>
                <h5>Start Saving</h5>
                <p class="text-muted">Use your card at partner hospitals and save money</p>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Now</a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-4 bg-primary text-white">
    <div class="content-wrapper">
        <div class="text-center">
            <h2 class="fw-bold mb-3">Ready to Save on Healthcare?</h2>
            <p class="lead mb-3">Join thousands of satisfied users and start your healthcare journey today</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-id-card"></i> Get Your Health Card Now
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animated counters
    function animateCounter(id, start, end, duration) {
        let obj = document.getElementById(id);
        let range = Math.abs(end - start);
        
        // If range is 0, just set the value immediately
        if (range === 0) {
            obj.textContent = end.toLocaleString();
            return;
        }
        
        let increment = end > start ? 1 : -1;
        let stepTime = Math.max(1, Math.floor(duration / range)); // Ensure minimum step time
        let current = start;
        
        let timer = setInterval(function() {
            current += increment;
            obj.textContent = current.toLocaleString();
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
    
    // Fetch and animate stats
    fetch('/api/stats')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Ensure all values are positive numbers
            const users = Math.max(0, parseInt(data.users) || 0);
            const hospitals = Math.max(0, parseInt(data.hospitals) || 0);
            const cards = Math.max(0, parseInt(data.cards) || 0);
            const savings = Math.max(0, parseInt(data.savings) || 0);
            
            animateCounter('users-count', 0, users, 2000);
            animateCounter('hospitals-count', 0, hospitals, 2000);
            animateCounter('cards-count', 0, cards, 2000);
            animateCounter('savings-count', 0, savings, 2000);
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
            // Fallback to 0 values if API fails
            animateCounter('users-count', 0, 0, 2000);
            animateCounter('hospitals-count', 0, 0, 2000);
            animateCounter('cards-count', 0, 0, 2000);
            animateCounter('savings-count', 0, 0, 2000);
        });
</script>
@endpush