@extends('layouts.dashboard')

@section('title', $hospital->name)

@section('content')
<div class="row">
    <!-- Hospital Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        @if($hospital->logo_path)
                            <img src="{{ asset('storage/' . $hospital->logo_path) }}" 
                                 class="img-fluid rounded" 
                                 style="max-height: 150px;"
                                 alt="{{ $hospital->name }}">
                        @else
                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; font-size: 3rem; margin: 0 auto;">
                                {{ substr($hospital->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-10">
                        <h2>{{ $hospital->name }}</h2>
                        <p class="text-muted mb-3">
                            <i class="fas fa-map-marker-alt"></i> {{ $hospital->address }}, {{ $hospital->city }}, {{ $hospital->state }} - {{ $hospital->pincode }}
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong><i class="fas fa-phone text-primary"></i> Phone:</strong>
                                <a href="tel:{{ $hospital->mobile }}">{{ $hospital->mobile }}</a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong><i class="fas fa-envelope text-primary"></i> Email:</strong>
                                <a href="mailto:{{ $hospital->email }}">{{ $hospital->email }}</a>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Verified Partner
                            </span>
                            <span class="badge bg-info">
                                {{ $hospital->services->count() }} Services Available
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Services & Discounts -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Available Services & Discounts</h5>
            </div>
            <div class="card-body">
                @if($hospital->services->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Service Name</th>
                                    <th>Category</th>
                                    <th>Discount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hospital->services as $service)
                                    <tr>
                                        <td>
                                            <strong>{{ $service->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $service->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success fs-6">
                                                {{ $service->pivot->discount_percentage }}% OFF
                                            </span>
                                        </td>
                                        <td>
                                            {{ $service->pivot->description ?? $service->description }}
                                        </td>
                                        <td>
                                            @if($service->pivot->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">No services available</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- How to Avail -->
    <div class="col-12 mt-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">How to Avail Discount</h5>
                <ol>
                    <li>Visit the hospital with your Health Card</li>
                    <li>Show your card at the reception</li>
                    <li>Hospital staff will scan your QR code</li>
                    <li>Your card will be verified instantly</li>
                    <li>Discount will be applied to the bill automatically</li>
                </ol>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Note:</strong> Please carry a valid government ID along with your health card.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
