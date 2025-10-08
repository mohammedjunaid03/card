@extends('layouts.dashboard')

@section('title', 'Find Hospitals')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('user.hospitals') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by hospital name" 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" 
                                   placeholder="Enter city or address" 
                                   value="{{ request('location') }}">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Service</label>
                            <select name="service_id" class="form-control">
                                <option value="">All Services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" 
                                            {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Minimum Discount %</label>
                            <input type="number" name="min_discount" class="form-control" 
                                   placeholder="e.g., 10" min="0" max="100"
                                   value="{{ request('min_discount') }}">
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('user.hospitals') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @if($hospitals->count() > 0)
        <div class="col-12 mb-3">
            <p class="text-muted">Found {{ $hospitals->total() }} hospitals</p>
        </div>
        
        @foreach($hospitals as $hospital)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 hospital-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            @if($hospital->logo_path)
                                <img src="{{ asset('storage/' . $hospital->logo_path) }}" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: contain;"
                                     alt="{{ $hospital->name }}">
                            @else
                                <div class="bg-primary text-white rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ substr($hospital->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div>
                                <h5 class="card-title mb-1">{{ $hospital->name }}</h5>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt"></i> {{ $hospital->city }}, {{ $hospital->state }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="small text-muted mb-2">Services & Discounts:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($hospital->services->take(3) as $service)
                                    <span class="badge bg-success">
                                        {{ $service->name }} - {{ $service->pivot->discount_percentage }}%
                                    </span>
                                @endforeach
                                @if($hospital->services->count() > 3)
                                    <span class="badge bg-secondary">
                                        +{{ $hospital->services->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="small mb-1">
                                <i class="fas fa-phone"></i> {{ $hospital->mobile }}
                            </p>
                            <p class="small mb-0">
                                <i class="fas fa-envelope"></i> {{ $hospital->email }}
                            </p>
                        </div>
                        
                        <a href="{{ route('user.hospitals.show', $hospital->id) }}" 
                           class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        
        <div class="col-12">
            {{ $hospitals->links() }}
        </div>
    @else
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-hospital fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No hospitals found</h5>
                    <p>Try adjusting your search filters</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.hospital-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hospital-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
@endpush
