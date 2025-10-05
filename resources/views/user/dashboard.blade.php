@extends('layouts.dashboard')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3>Welcome, {{ $user->name }}!</h3>
                <p class="mb-0">Manage your health card and view your discount history</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Savings</h6>
                        <h3 class="mb-0">₹{{ number_format($totalSavings, 2) }}</h3>
                    </div>
                    <i class="fas fa-rupee-sign fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Card Status</h6>
                        <h3 class="mb-0">{{ ucfirst($healthCard->status ?? 'N/A') }}</h3>
                    </div>
                    <i class="fas fa-id-card fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Visits</h6>
                        <h3 class="mb-0">{{ $recentAvailments->count() }}</h3>
                    </div>
                    <i class="fas fa-hospital fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Days to Expiry</h6>
                        <h3 class="mb-0">{{ $healthCard ? $healthCard->daysUntilExpiry() : 'N/A' }}</h3>
                    </div>
                    <i class="fas fa-calendar fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Health Card Quick View -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">My Health Card</h5>
            </div>
            <div class="card-body text-center">
                @if($healthCard)
                    <img src="{{ asset('storage/' . $healthCard->qr_code_path) }}" 
                         alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>{{ $healthCard->card_number }}</h4>
                    <p class="mb-2">
                        <strong>Issued:</strong> {{ $healthCard->issued_date->format('d M Y') }}
                    </p>
                    <p class="mb-3">
                        <strong>Expires:</strong> {{ $healthCard->expiry_date->format('d M Y') }}
                    </p>
                    <a href="{{ route('user.health-card') }}" class="btn btn-primary me-2">
                        <i class="fas fa-eye"></i> View Full Card
                    </a>
                    <a href="{{ route('user.health-card.download') }}" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                @else
                    <p class="text-muted">No health card found</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Notifications</h5>
                <a href="{{ route('user.notifications') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($notifications as $notification)
                    <div class="alert alert-{{ $notification->type }} alert-dismissible fade show mb-2">
                        <strong>{{ $notification->title }}</strong><br>
                        <small>{{ $notification->message }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @empty
                    <p class="text-muted">No new notifications</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Availments -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Service Availments</h5>
                <a href="{{ route('user.discount-history') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Hospital</th>
                                <th>Service</th>
                                <th>Original Amount</th>
                                <th>Discount</th>
                                <th>Final Amount</th>
                                <th>Savings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAvailments as $availment)
                                <tr>
                                    <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                    <td>{{ $availment->hospital->name }}</td>
                                    <td>{{ $availment->service->name }}</td>
                                    <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                    <td>{{ $availment->discount_percentage }}%</td>
                                    <td>₹{{ number_format($availment->final_amount, 2) }}</td>
                                    <td class="text-success">
                                        <strong>₹{{ number_format($availment->discount_amount, 2) }}</strong>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No service availments yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection