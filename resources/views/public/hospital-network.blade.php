@extends('layouts.app')

@section('title', 'Hospital Network - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Our Hospital Network</h1>
                <p class="lead">Access quality healthcare at 500+ partner hospitals across India</p>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form class="row g-3" method="GET" action="{{ route('hospital-network') }}">
                    <div class="col-md-4">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="city" name="city">
                            <option value="">All Cities</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Kolkata">Kolkata</option>
                            <option value="Hyderabad">Hyderabad</option>
                            <option value="Pune">Pune</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="service" class="form-label">Service</label>
                        <select class="form-select" id="service" name="service">
                            <option value="">All Services</option>
                            <option value="general">General Medicine</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="orthopedics">Orthopedics</option>
                            <option value="pediatrics">Pediatrics</option>
                            <option value="gynecology">Gynecology</option>
                            <option value="dermatology">Dermatology</option>
                            <option value="ophthalmology">Ophthalmology</option>
                            <option value="dentistry">Dentistry</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="discount" class="form-label">Min. Discount</label>
                        <select class="form-select" id="discount" name="discount">
                            <option value="">Any Discount</option>
                            <option value="10">10% or more</option>
                            <option value="20">20% or more</option>
                            <option value="30">30% or more</option>
                            <option value="40">40% or more</option>
                            <option value="50">50% or more</option>
                        </select>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search Hospitals
                        </button>
                        <a href="{{ route('hospital-network') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-refresh"></i> Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Hospital List Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold">Partner Hospitals</h3>
                <p class="text-muted">Showing {{ $hospitals->count() ?? 0 }} hospitals</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="view" id="grid-view" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="grid-view">
                        <i class="fas fa-th"></i> Grid
                    </label>
                    <input type="radio" class="btn-check" name="view" id="list-view" autocomplete="off">
                    <label class="btn btn-outline-primary" for="list-view">
                        <i class="fas fa-list"></i> List
                    </label>
                </div>
            </div>
        </div>
        
        <div class="row" id="hospitals-grid">
            <!-- Sample Hospital Cards -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">Apollo Hospitals</h5>
                                <p class="text-muted small mb-0">Mumbai, Maharashtra</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Multi-specialty hospital with 24/7 emergency services and advanced medical facilities.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">General Medicine</span>
                                <span class="badge bg-light text-dark">Cardiology</span>
                                <span class="badge bg-light text-dark">Orthopedics</span>
                                <span class="badge bg-light text-dark">Pediatrics</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 40% OFF</span>
                                <p class="text-muted small mb-0">On all services</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">Fortis Healthcare</h5>
                                <p class="text-muted small mb-0">Delhi, NCR</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Leading healthcare provider with state-of-the-art technology and expert medical professionals.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">Cardiology</span>
                                <span class="badge bg-light text-dark">Neurology</span>
                                <span class="badge bg-light text-dark">Oncology</span>
                                <span class="badge bg-light text-dark">Gynecology</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 35% OFF</span>
                                <p class="text-muted small mb-0">On consultations</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">Max Healthcare</h5>
                                <p class="text-muted small mb-0">Bangalore, Karnataka</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Comprehensive healthcare services with focus on patient care and medical excellence.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">Dermatology</span>
                                <span class="badge bg-light text-dark">Ophthalmology</span>
                                <span class="badge bg-light text-dark">Dentistry</span>
                                <span class="badge bg-light text-dark">ENT</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 30% OFF</span>
                                <p class="text-muted small mb-0">On treatments</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">Manipal Hospitals</h5>
                                <p class="text-muted small mb-0">Chennai, Tamil Nadu</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Trusted healthcare provider with advanced medical technology and experienced doctors.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">General Medicine</span>
                                <span class="badge bg-light text-dark">Pediatrics</span>
                                <span class="badge bg-light text-dark">Orthopedics</span>
                                <span class="badge bg-light text-dark">Emergency</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 45% OFF</span>
                                <p class="text-muted small mb-0">On diagnostics</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">Narayana Health</h5>
                                <p class="text-muted small mb-0">Kolkata, West Bengal</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Affordable healthcare with world-class facilities and compassionate care.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">Cardiology</span>
                                <span class="badge bg-light text-dark">Neurology</span>
                                <span class="badge bg-light text-dark">Urology</span>
                                <span class="badge bg-light text-dark">Gastroenterology</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 50% OFF</span>
                                <p class="text-muted small mb-0">On surgeries</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('images/hospital-logo-placeholder.png') }}" 
                                 class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                            <div>
                                <h5 class="card-title mb-1">KIMS Hospitals</h5>
                                <p class="text-muted small mb-0">Hyderabad, Telangana</p>
                            </div>
                        </div>
                        <p class="card-text text-muted small">Leading healthcare institution with comprehensive medical services and expert care.</p>
                        
                        <div class="mb-3">
                            <h6 class="text-success mb-2">Available Services:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">Oncology</span>
                                <span class="badge bg-light text-dark">Transplant</span>
                                <span class="badge bg-light text-dark">Critical Care</span>
                                <span class="badge bg-light text-dark">Radiology</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-success fw-bold">Up to 25% OFF</span>
                                <p class="text-muted small mb-0">On consultations</p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="Hospital pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Network Statistics</h2>
            <p class="text-muted">Trusted by millions across India</p>
        </div>
        
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="fas fa-hospital fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">500+</h3>
                        <p class="text-muted">Partner Hospitals</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">50+</h3>
                        <p class="text-muted">Cities Covered</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="fas fa-stethoscope fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">25+</h3>
                        <p class="text-muted">Medical Specialties</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0">
                    <div class="card-body">
                        <i class="fas fa-percentage fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">50%</h3>
                        <p class="text-muted">Max Discount</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Don't Have a Health Card Yet?</h2>
        <p class="lead mb-4">Get your health card today and start saving at these amazing hospitals</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
            <i class="fas fa-id-card"></i> Get Your Health Card
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // View toggle functionality
    document.getElementById('list-view').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('hospitals-grid').classList.add('list-view');
        }
    });
    
    document.getElementById('grid-view').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('hospitals-grid').classList.remove('list-view');
        }
    });
</script>

<style>
    .list-view .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .list-view .card {
        flex-direction: row;
    }
    
    .list-view .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
@endpush
