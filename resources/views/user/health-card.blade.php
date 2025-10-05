@extends('layouts.dashboard')

@section('title', 'My Health Card')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">My Health Card</h5>
                <div>
                    <a href="{{ route('user.health-card.download') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Card Preview -->
                    <div class="col-md-8">
                        <div class="health-card-preview border rounded p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-inner bg-white rounded p-4">
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <h3 class="text-primary mb-0">HEALTH CARD</h3>
                                        <small class="text-muted">Health Card System</small>
                                    </div>
                                    @if($healthCard->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($healthCard->status) }}</span>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <small class="text-muted">Card Number</small>
                                            <h5 class="mb-0 font-monospace">{{ $healthCard->card_number }}</h5>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Name</small>
                                                <p class="mb-0"><strong>{{ auth()->user()->name }}</strong></p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Blood Group</small>
                                                <p class="mb-0"><strong>{{ auth()->user()->blood_group }}</strong></p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Gender</small>
                                                <p class="mb-0">{{ auth()->user()->gender }}</p>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small class="text-muted">Age</small>
                                                <p class="mb-0">{{ auth()->user()->age }} years</p>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">Valid From</small>
                                            <p class="mb-0">{{ $healthCard->issued_date->format('d M Y') }}</p>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <small class="text-muted">Valid Until</small>
                                            <p class="mb-0">{{ $healthCard->expiry_date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 text-center">
                                        @if(auth()->user()->photo_path)
                                            <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" 
                                                 class="img-thumbnail mb-3" 
                                                 style="width: 120px; height: 120px; object-fit: cover;"
                                                 alt="Photo">
                                        @endif
                                        
                                        <div class="qr-code-section">
                                            <img src="{{ asset('storage/' . $healthCard->qr_code_path) }}" 
                                                 class="img-fluid" 
                                                 style="max-width: 150px;"
                                                 alt="QR Code">
                                            <p class="small text-muted mt-2">Scan at Hospital</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Information -->
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Card Status</h6>
                            </div>
                            <div class="card-body">
                                @if($healthCard->isExpired())
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Your card has expired
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i>
                                        Card is active
                                    </div>
                                    
                                    @php
                                        $daysLeft = $healthCard->daysUntilExpiry();
                                    @endphp
                                    
                                    @if($daysLeft <= 30)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock"></i>
                                            Expires in {{ $daysLeft }} days
                                        </div>
                                    @endif
                                @endif
                                
                                <h6 class="mt-3">Benefits</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Access to partner hospitals</li>
                                    <li><i class="fas fa-check text-success"></i> Discount on medical services</li>
                                    <li><i class="fas fa-check text-success"></i> Priority consultation</li>
                                    <li><i class="fas fa-check text-success"></i> Digital health records</li>
                                </ul>
                                
                                <hr>
                                
                                <h6>Usage Instructions</h6>
                                <ol class="small">
                                    <li>Present this card at reception</li>
                                    <li>Hospital will scan QR code</li>
                                    <li>Verification will be instant</li>
                                    <li>Discount applied automatically</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection