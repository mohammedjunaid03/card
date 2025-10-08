@extends('layouts.dashboard')

@section('title', 'Verify Health Card')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Verify Health Card</h2>
                <a href="{{ route('hospital.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('hospital.health-cards.verify') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="type" class="form-label">Search By</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="phone" {{ request('type') === 'phone' ? 'selected' : '' }}>Phone Number</option>
                                    <option value="card_number" {{ request('type') === 'card_number' ? 'selected' : '' }}>Card Number</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="query" class="form-label">Search Query</label>
                                <input type="text" class="form-control" id="query" name="query" 
                                       value="{{ request('query') }}" 
                                       placeholder="Enter phone number or card number">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(request('query'))
                @if($healthCard && $user)
                    <!-- Health Card Found -->
                    <div class="card">
                        <div class="card-header {{ $healthCard->isActive() ? 'bg-success' : 'bg-warning' }} text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-{{ $healthCard->isActive() ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                                Health Card {{ $healthCard->isActive() ? 'Verified' : 'Status Check' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        @if($user->photo_path)
                                            <img src="{{ asset('storage/' . $user->photo_path) }}" 
                                                 alt="User Photo" class="rounded-circle mb-3" width="120" height="120">
                                        @else
                                            <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 120px; height: 120px;">
                                                <i class="fas fa-user fa-3x text-white"></i>
                                            </div>
                                        @endif
                                        
                                        <h4>{{ $user->name }}</h4>
                                        <p class="text-muted">{{ $user->age }} years, {{ $user->gender }}</p>
                                        
                                        <!-- Status Badge -->
                                        <div class="mt-2">
                                            @if($healthCard->isActive())
                                                <span class="badge bg-success fs-6">Active & Valid</span>
                                            @elseif($healthCard->isPending())
                                                <span class="badge bg-warning fs-6">Pending Approval</span>
                                            @elseif($healthCard->isRejected())
                                                <span class="badge bg-danger fs-6">Rejected</span>
                                            @elseif($healthCard->isExpired())
                                                <span class="badge bg-secondary fs-6">Expired</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Card Number:</strong></td>
                                                    <td><span class="badge bg-primary fs-6">{{ $healthCard->card_number }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Blood Group:</strong></td>
                                                    <td><span class="badge bg-info">{{ $user->blood_group }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Mobile:</strong></td>
                                                    <td>{{ $user->mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>{{ $user->email }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Issued Date:</strong></td>
                                                    <td>{{ $healthCard->issued_date->format('d-m-Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Expiry Date:</strong></td>
                                                    <td>{{ $healthCard->expiry_date->format('d-m-Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Days Remaining:</strong></td>
                                                    <td>
                                                        @if($healthCard->daysUntilExpiry() > 0)
                                                            <span class="text-success">{{ $healthCard->daysUntilExpiry() }} days</span>
                                                        @else
                                                            <span class="text-danger">Expired</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($healthCard->approved_by)
                                                    <tr>
                                                        <td><strong>Approved By:</strong></td>
                                                        <td>{{ $healthCard->approver->name ?? 'Unknown' }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>

                                    <!-- QR Code -->
                                    <div class="text-center mt-3">
                                        <h6>QR Code</h6>
                                        @if($healthCard->qr_code_path && file_exists(storage_path('app/public/' . $healthCard->qr_code_path)))
                                            <img src="{{ asset('storage/' . $healthCard->qr_code_path) }}" 
                                                 alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                        @else
                                            <div class="bg-light border rounded p-3 d-inline-block">
                                                <i class="fas fa-qrcode fa-2x text-muted"></i>
                                                <p class="text-muted mt-2 mb-0">QR Code not available</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Service Eligibility -->
                                    <div class="mt-4">
                                        <h6>Service Eligibility</h6>
                                        @if($healthCard->isActive())
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Eligible for Services:</strong> This patient is eligible for healthcare services with applicable discounts.
                                            </div>
                                        @elseif($healthCard->isPending())
                                            <div class="alert alert-warning">
                                                <i class="fas fa-clock me-2"></i>
                                                <strong>Pending Approval:</strong> This health card is pending staff approval. Services may be provided at regular rates.
                                            </div>
                                        @elseif($healthCard->isRejected())
                                            <div class="alert alert-danger">
                                                <i class="fas fa-times-circle me-2"></i>
                                                <strong>Card Rejected:</strong> This health card has been rejected. Services should be provided at regular rates.
                                                @if($healthCard->rejection_reason)
                                                    <br><small>Reason: {{ $healthCard->rejection_reason }}</small>
                                                @endif
                                            </div>
                                        @elseif($healthCard->isExpired())
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-calendar-times me-2"></i>
                                                <strong>Card Expired:</strong> This health card has expired. Services should be provided at regular rates.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Health Card Not Found -->
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>Health Card Not Found
                            </h5>
                        </div>
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>No health card found</h4>
                            <p class="text-muted">
                                No health card found for the search query: <strong>{{ request('query') }}</strong>
                            </p>
                            <p class="text-muted">Please check the phone number or card number and try again.</p>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> If the patient doesn't have a health card, services should be provided at regular rates.
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Instructions -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h4>Verify Health Card</h4>
                        <p class="text-muted">
                            Enter a phone number or card number to verify a health card holder and check their eligibility for services.
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Search Options:</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><strong>Phone Number:</strong> Search by patient's mobile number</li>
                                        <li><strong>Card Number:</strong> Search by health card number</li>
                                    </ul>
                                    <hr>
                                    <h6><i class="fas fa-hospital me-2"></i>Service Guidelines:</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><span class="badge bg-success me-1">Active</span> Full services with applicable discounts</li>
                                        <li><span class="badge bg-warning me-1">Pending</span> Services at regular rates</li>
                                        <li><span class="badge bg-danger me-1">Rejected/Expired</span> Services at regular rates</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
