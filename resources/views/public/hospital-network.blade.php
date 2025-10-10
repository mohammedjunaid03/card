@extends('layouts.app')

@section('title', 'Hospital Network - Health Card System')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="content-wrapper">
        <div class="row align-items-center mx-0">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold">Our Hospital Network</h1>
                <p class="lead">Access quality healthcare at 500+ partner hospitals across India</p>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-4 bg-light">
    <div class="content-wrapper">
        <div class="row mx-0">
            <div class="col-lg-8 mx-auto">
                <form class="row g-2 search-form" method="GET" action="{{ route('hospital-network') }}">
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city" name="city">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="service" class="form-label">Service</label>
                            <select class="form-select" id="service" name="service">
                                <option value="">All Services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>
                                        {{ ucfirst($service) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label for="discount" class="form-label">Min. Discount</label>
                            <select class="form-select" id="discount" name="discount">
                                <option value="">Any Discount</option>
                                <option value="10" {{ request('discount') == '10' ? 'selected' : '' }}>10% or more</option>
                                <option value="20" {{ request('discount') == '20' ? 'selected' : '' }}>20% or more</option>
                                <option value="30" {{ request('discount') == '30' ? 'selected' : '' }}>30% or more</option>
                                <option value="40" {{ request('discount') == '40' ? 'selected' : '' }}>40% or more</option>
                                <option value="50" {{ request('discount') == '50' ? 'selected' : '' }}>50% or more</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group d-flex align-items-end justify-content-center">
                            <button type="submit" class="btn btn-primary me-1">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('hospital-network') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Hospital List Section -->
<section class="py-5">
    <div class="content-wrapper">
        <div class="row mb-4 mx-0">
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
        
        <div class="row mx-0" id="hospitals-grid">
            @forelse($hospitals as $hospital)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hospital-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $hospital->logo_path ? asset('storage/' . $hospital->logo_path) : asset('images/hospital-logo-placeholder.png') }}" 
                                     class="rounded me-3" width="60" height="60" alt="Hospital Logo">
                                <div>
                                    <h5 class="card-title mb-1">{{ $hospital->name }}</h5>
                                    <p class="text-muted small mb-0">{{ $hospital->city }}, {{ $hospital->state }}</p>
                                </div>
                            </div>
                            <p class="card-text text-muted small">{{ Str::limit($hospital->description ?? 'Quality healthcare services with modern facilities and experienced medical professionals.', 100) }}</p>
                            
                            <div class="mb-3">
                                <h6 class="text-success mb-2">Available Services:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($hospital->ownedServices->take(4) as $service)
                                        <span class="badge bg-light text-dark">{{ $service->name }}</span>
                                    @empty
                                        <span class="badge bg-light text-dark">General Services</span>
                                    @endforelse
                                    @if($hospital->ownedServices->count() > 4)
                                        <span class="badge bg-secondary">+{{ $hospital->ownedServices->count() - 4 }} more</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @php
                                        $maxDiscount = $hospital->ownedServices->max('discount_percentage') ?? 0;
                                    @endphp
                                    <span class="text-success fw-bold">Up to {{ $maxDiscount }}% OFF</span>
                                    <p class="text-muted small mb-0">On selected services</p>
                                </div>
                                <a href="{{ route('hospital.details', $hospital->id) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-hospital fa-3x mb-3"></i>
                        <h4>No hospitals found</h4>
                        <p>Try adjusting your search filters to find more hospitals.</p>
                        <a href="{{ route('hospital-network') }}" class="btn btn-primary">Clear Filters</a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($hospitals->hasPages())
        <div class="row">
            <div class="col-12">
                <nav aria-label="Hospital pagination">
                    {{ $hospitals->appends(request()->query())->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="content-wrapper">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Network Statistics</h2>
            <p class="text-muted">Trusted by millions across India</p>
        </div>
        
        <div class="row text-center mx-0">
            <div class="col-md-3 mb-4">
                <div class="network-stat-card text-center">
                    <i class="fas fa-hospital fa-3x mb-3"></i>
                    <h3 class="fw-bold">500+</h3>
                    <p class="text-muted">Partner Hospitals</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="network-stat-card text-center">
                    <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                    <h3 class="fw-bold">50+</h3>
                    <p class="text-muted">Cities Covered</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="network-stat-card text-center">
                    <i class="fas fa-stethoscope fa-3x mb-3"></i>
                    <h3 class="fw-bold">25+</h3>
                    <p class="text-muted">Medical Specialties</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="network-stat-card text-center">
                    <i class="fas fa-percentage fa-3x mb-3"></i>
                    <h3 class="fw-bold">50%</h3>
                    <p class="text-muted">Max Discount</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="content-wrapper">
        <div class="text-center">
            <h2 class="fw-bold mb-3">Don't Have a Health Card Yet?</h2>
            <p class="lead mb-4">Get your health card today and start saving at these amazing hospitals</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                <i class="fas fa-id-card"></i> Get Your Health Card
            </a>
        </div>
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
