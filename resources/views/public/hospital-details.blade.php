@extends('layouts.app')

@section('title', $hospital->name . ' - Hospital Details')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hospital-network') }}" class="text-white">Hospitals</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $hospital->name }}</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold">{{ $hospital->name }}</h1>
                <p class="lead">{{ $hospital->city }}, {{ $hospital->state }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Hospital Details Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Hospital Info Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $hospital->logo_path ? asset('storage/' . $hospital->logo_path) : asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded-circle mb-3" width="100" height="100" alt="Hospital Logo">
                            <h4 class="card-title">{{ $hospital->name }}</h4>
                            <p class="text-muted">{{ $hospital->city }}, {{ $hospital->state }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>Address</h6>
                            <p class="text-muted">{{ $hospital->address }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-phone text-primary me-2"></i>Phone</h6>
                            <p class="text-muted">{{ $hospital->mobile }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                            <p class="text-muted">{{ $hospital->email }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-certificate text-primary me-2"></i>License</h6>
                            <p class="text-muted">{{ $hospital->license_number }}</p>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-id-card"></i> Get Health Card
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Services and Benefits Tabs -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" id="hospitalTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="card-benefits-tab" data-bs-toggle="tab" 
                                        data-bs-target="#card-benefits" type="button" role="tab">
                                    <i class="fas fa-id-card text-success me-2"></i>With Health Card
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="without-card-tab" data-bs-toggle="tab" 
                                        data-bs-target="#without-card" type="button" role="tab">
                                    <i class="fas fa-user text-primary me-2"></i>Without Health Card
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="facilities-tab" data-bs-toggle="tab" 
                                        data-bs-target="#facilities" type="button" role="tab">
                                    <i class="fas fa-hospital text-info me-2"></i>Facilities
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <div class="tab-content" id="hospitalTabsContent">
                            <!-- With Health Card Tab -->
                            <div class="tab-pane fade show active" id="card-benefits" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="alert alert-success">
                                            <h5><i class="fas fa-star me-2"></i>Health Card Benefits</h5>
                                            <p class="mb-0">Enjoy exclusive discounts and benefits with your KCC HealthCard at this hospital.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <h5 class="mb-3">Services with Discounts</h5>
                                @if($hospital->ownedServices->count() > 0)
                                    <div class="row">
                                        @foreach($hospital->ownedServices as $service)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-success">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="card-title text-success">{{ $service->name }}</h6>
                                                                <p class="card-text small text-muted">{{ $service->description ?? 'Quality healthcare service' }}</p>
                                                                <span class="badge bg-success">
                                                                    {{ $service->discount_percentage }}% OFF
                                                                </span>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="text-success fw-bold">₹{{ number_format($service->price * (1 - $service->discount_percentage/100), 0) }}</div>
                                                                <small class="text-muted text-decoration-line-through">₹{{ number_format($service->price, 0) }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No specific services with discounts available. General services are available with standard pricing.</p>
                                    </div>
                                @endif
                                
                                <div class="mt-4">
                                    <h6>Additional Benefits with Health Card:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Priority appointment booking</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Express registration process</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Digital health records access</li>
                                        <li><i class="fas fa-check text-success me-2"></i>24/7 customer support</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Health card renewal reminders</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Without Health Card Tab -->
                            <div class="tab-pane fade" id="without-card" role="tabpanel">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <div class="alert alert-info">
                                            <h5><i class="fas fa-info-circle me-2"></i>Standard Services</h5>
                                            <p class="mb-0">These services are available to all patients without requiring a health card.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <h5 class="mb-3">Available Services (Standard Pricing)</h5>
                                @if($hospital->ownedServices->count() > 0)
                                    <div class="row">
                                        @foreach($hospital->ownedServices as $service)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-primary">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="card-title text-primary">{{ $service->name }}</h6>
                                                                <p class="card-text small text-muted">{{ $service->description ?? 'Quality healthcare service' }}</p>
                                                                <span class="badge bg-primary">Standard Rate</span>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="text-primary fw-bold">₹{{ number_format($service->price, 0) }}</div>
                                                                <small class="text-muted">Regular price</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-hospital fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">General healthcare services are available at standard rates.</p>
                                    </div>
                                @endif
                                
                                <div class="mt-4">
                                    <h6>Standard Services Include:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-primary me-2"></i>General consultation</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Emergency services</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Basic diagnostic tests</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Standard treatment procedures</li>
                                        <li><i class="fas fa-check text-primary me-2"></i>Regular follow-up consultations</li>
                                    </ul>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Get Your Health Card</h6>
                                    <p class="mb-2">Save money and enjoy exclusive benefits by getting your KCC HealthCard today!</p>
                                    <a href="{{ route('register') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-id-card"></i> Register for Health Card
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Facilities Tab -->
                            <div class="tab-pane fade" id="facilities" role="tabpanel">
                                <h5 class="mb-3">Hospital Facilities & Amenities</h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6><i class="fas fa-ambulance text-danger me-2"></i>Emergency Services</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>24/7 Emergency Department</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Ambulance Services</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Trauma Center</li>
                                            <li><i class="fas fa-check text-success me-2"></i>ICU Facilities</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <h6><i class="fas fa-microscope text-info me-2"></i>Diagnostic Services</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Laboratory Services</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Radiology & Imaging</li>
                                            <li><i class="fas fa-check text-success me-2"></i>MRI & CT Scan</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Pathology Services</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <h6><i class="fas fa-bed text-primary me-2"></i>Patient Care</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Private & General Wards</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Nursing Care</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Pharmacy</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Cafeteria</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <h6><i class="fas fa-parking text-warning me-2"></i>Convenience</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i>Parking Facilities</li>
                                            <li><i class="fas fa-check text-success me-2"></i>ATM Services</li>
                                            <li><i class="fas fa-check text-success me-2"></i>WiFi Access</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Wheelchair Access</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6>Specialized Departments:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-primary">Cardiology</span>
                                        <span class="badge bg-primary">Orthopedics</span>
                                        <span class="badge bg-primary">Neurology</span>
                                        <span class="badge bg-primary">Pediatrics</span>
                                        <span class="badge bg-primary">Gynecology</span>
                                        <span class="badge bg-primary">Dermatology</span>
                                        <span class="badge bg-primary">Ophthalmology</span>
                                        <span class="badge bg-primary">ENT</span>
                                    </div>
                                </div>
                            </div>
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
        <h2 class="fw-bold mb-3">Ready to Get Your Health Card?</h2>
        <p class="lead mb-4">Join thousands of patients who are already saving on healthcare costs</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-id-card"></i> Get Health Card Now
        </a>
        <a href="{{ route('hospital-network') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-hospital"></i> Browse More Hospitals
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#hospitalTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>
@endpush
