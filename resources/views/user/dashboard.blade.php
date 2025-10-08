@extends('layouts.dashboard')

@section('title', 'User Dashboard')

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .stats-card {
        background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%);
        border: none;
        color: white;
    }
    
    .quick-action-btn {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: var(--bs-primary);
    }
    
    .notification-item {
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        background-color: #e9ecef !important;
        transform: translateX(5px);
    }
    
    .availment-item {
        transition: all 0.3s ease;
    }
    
    .availment-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .health-card-qr {
        transition: all 0.3s ease;
    }
    
    .health-card-qr:hover {
        transform: scale(1.05);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
</style>
@endpush

@section('content')
<!-- Modern Hero Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm fade-in-up" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Welcome back, {{ $user->name }}! 👋</h2>
                        <p class="mb-0 opacity-90">Manage your health card, track savings, and discover healthcare services</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end">
                            <div class="text-center me-4">
                                <div class="h4 mb-0">{{ $recentAvailments->count() }}</div>
                                <small class="opacity-75">Total Visits</small>
                            </div>
                            <div class="text-center">
                                <div class="h4 mb-0">₹{{ number_format($totalSavings, 0) }}</div>
                                <small class="opacity-75">Total Saved</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Health Card Status Alert -->
    @if($healthCard)
        @if($healthCard->isPending())
            <div class="col-12 mb-4">
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Health Card Pending Approval:</strong> Your health card ({{ $healthCard->card_number }}) is currently under review by our staff. You will be notified once it's approved.
                </div>
            </div>
        @elseif($healthCard->isRejected())
            <div class="col-12 mb-4">
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Health Card Rejected:</strong> Your health card application has been rejected.
                    @if($healthCard->rejection_reason)
                        <br><strong>Reason:</strong> {{ $healthCard->rejection_reason }}
                    @endif
                    <br>Please contact support for more information or submit a new application.
                </div>
            </div>
        @elseif($healthCard->isExpired())
            <div class="col-12 mb-4">
                <div class="alert alert-secondary alert-dismissible fade show">
                    <i class="fas fa-calendar-times me-2"></i>
                    <strong>Health Card Expired:</strong> Your health card expired on {{ $healthCard->expiry_date->format('d-m-Y') }}. Please renew it to continue using healthcare services.
                </div>
            </div>
        @endif
    @endif
    
<!-- Modern Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Savings</h6>
                        <h3 class="mb-0 fw-bold">₹{{ number_format($totalSavings, 0) }}</h3>
                        <small class="opacity-75">Lifetime savings</small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-rupee-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 {{ $healthCard && $healthCard->isActive() ? 'bg-success' : ($healthCard && $healthCard->isPending() ? 'bg-warning' : 'bg-secondary') }}" style="{{ $healthCard && $healthCard->isActive() ? 'background: linear-gradient(135deg, #28a745 0%, #20c997 100%);' : ($healthCard && $healthCard->isPending() ? 'background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);' : 'background: linear-gradient(135deg, #6c757d 0%, #495057 100%);') }}">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Card Status</h6>
                        <h3 class="mb-0 fw-bold">
                            @if($healthCard)
                                @if($healthCard->isActive())
                                    <span class="badge bg-white text-success">Active</span>
                                @elseif($healthCard->isPending())
                                    <span class="badge bg-white text-warning">Pending</span>
                                @elseif($healthCard->isRejected())
                                    <span class="badge bg-white text-danger">Rejected</span>
                                @elseif($healthCard->isExpired())
                                    <span class="badge bg-white text-secondary">Expired</span>
                                @else
                                    <span class="badge bg-white text-dark">{{ ucfirst($healthCard->status) }}</span>
                                @endif
                            @else
                                <span class="badge bg-white text-dark">No Card</span>
                            @endif
                        </h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-id-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Visits</h6>
                        <h3 class="mb-0 fw-bold">{{ $recentAvailments->count() }}</h3>
                        <small class="opacity-75">Healthcare visits</small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-hospital fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Days to Expiry</h6>
                        <h3 class="mb-0 fw-bold">{{ $healthCard ? $healthCard->daysUntilExpiry() : 'N/A' }}</h3>
                        <small class="opacity-75">Card validity</small>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-circle p-3">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
@if($totalVisits > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold">Your Healthcare Analytics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                            <h3 class="mb-1 fw-bold">{{ $totalHospitals }}</h3>
                            <small class="opacity-75">Hospitals Visited</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); color: white;">
                            <h3 class="mb-1 fw-bold">₹{{ number_format($averageSavings, 0) }}</h3>
                            <small class="opacity-75">Average Savings per Visit</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); color: white;">
                            <h3 class="mb-1 fw-bold">{{ $totalVisits }}</h3>
                            <small class="opacity-75">Total Healthcare Visits</small>
                        </div>
                    </div>
                </div>
                
                @if($monthlySavings->count() > 0)
                <div class="mt-4">
                    <h6 class="fw-bold mb-3">Monthly Savings Trend</h6>
                    <canvas id="savingsChart" height="100"></canvas>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
    
<!-- Health Card & Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="mb-0 fw-bold">My Health Card</h5>
            </div>
            <div class="card-body text-center">
                @if($healthCard)
                    <div class="position-relative mb-3">
                        <img src="{{ asset('storage/' . $healthCard->qr_code_path) }}" 
                             alt="QR Code" class="img-fluid rounded shadow-sm health-card-qr" style="max-width: 180px;">
                        <div class="position-absolute top-0 end-0">
                            @if($healthCard->isActive())
                                <span class="badge bg-success">Active</span>
                            @elseif($healthCard->isPending())
                                <span class="badge bg-warning">Pending</span>
                            @elseif($healthCard->isRejected())
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($healthCard->isExpired())
                                <span class="badge bg-secondary">Expired</span>
                            @endif
                        </div>
                    </div>
                    <h4 class="fw-bold text-primary">{{ $healthCard->card_number }}</h4>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Issued</small>
                            <strong>{{ $healthCard->issued_date->format('d M Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Expires</small>
                            <strong>{{ $healthCard->expiry_date->format('d M Y') }}</strong>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('user.health-card') }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i> View Full Card
                        </a>
                        <a href="{{ route('user.health-card.download') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No health card found</p>
                        <a href="{{ route('register') }}" class="btn btn-primary">Apply for Health Card</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pb-0">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('user.hospitals') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 quick-action-btn">
                            <i class="fas fa-hospital fa-2x mb-2"></i>
                            <span class="fw-bold">Find Hospitals</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('user.discount-history') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 quick-action-btn">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <span class="fw-bold">Savings History</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 quick-action-btn">
                            <i class="fas fa-user-edit fa-2x mb-2"></i>
                            <span class="fw-bold">Update Profile</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('user.support-tickets.create') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 quick-action-btn">
                            <i class="fas fa-headset fa-2x mb-2"></i>
                            <span class="fw-bold">Get Support</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<!-- Notifications & Recent Activity -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Notifications</h5>
                <a href="{{ route('user.notifications') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-bell me-1"></i>View All
                </a>
            </div>
            <div class="card-body">
                @forelse($notifications as $notification)
                    <div class="d-flex align-items-start mb-3 p-3 rounded notification-item" style="background-color: #f8f9fa;">
                        <div class="me-3">
                            @if($notification->type === 'success')
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            @elseif($notification->type === 'warning')
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                            @elseif($notification->type === 'error')
                                <i class="fas fa-times-circle text-danger fa-lg"></i>
                            @else
                                <i class="fas fa-info-circle text-info fa-lg"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $notification->title }}</h6>
                            <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-bell-slash fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No new notifications</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Availments -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Service Availments</h5>
                <a href="{{ route('user.discount-history') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-history me-1"></i>View All
                </a>
            </div>
            <div class="card-body">
                @forelse($recentAvailments as $availment)
                    <div class="d-flex align-items-center mb-3 p-3 rounded border availment-item">
                        <div class="me-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="fas fa-hospital text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $availment->hospital->name }}</h6>
                            <p class="mb-1 text-muted small">{{ $availment->service->name }}</p>
                            <small class="text-muted">{{ $availment->availment_date->format('d M Y') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="text-success fw-bold">₹{{ number_format($availment->discount_amount, 0) }}</div>
                            <small class="text-muted">Saved</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No service availments yet</p>
                        <a href="{{ route('user.hospitals') }}" class="btn btn-primary btn-sm">Find Hospitals</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recent Availments Table (Full Width) -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Service Availments History</h5>
                <a href="{{ route('user.discount-history') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-chart-line me-1"></i>Detailed View
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Hospital</th>
                                <th>Service</th>
                                <th class="text-end">Original Amount</th>
                                <th class="text-center">Discount</th>
                                <th class="text-end">Final Amount</th>
                                <th class="text-end">Savings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAvailments as $availment)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $availment->availment_date->format('d M') }}</div>
                                        <small class="text-muted">{{ $availment->availment_date->format('Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $availment->hospital->name }}</div>
                                        <small class="text-muted">{{ $availment->hospital->city ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $availment->service->name }}
                                        </span>
                                    </td>
                                    <td class="text-end">₹{{ number_format($availment->original_amount, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $availment->discount_percentage }}%</span>
                                    </td>
                                    <td class="text-end">₹{{ number_format($availment->final_amount, 2) }}</td>
                                    <td class="text-end">
                                        <span class="text-success fw-bold">₹{{ number_format($availment->discount_amount, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-receipt fa-2x text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">No service availments yet</p>
                                        <a href="{{ route('user.hospitals') }}" class="btn btn-primary btn-sm mt-2">Find Hospitals</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($monthlySavings->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('savingsChart').getContext('2d');
    
    const monthlyData = @json($monthlySavings);
    const labels = monthlyData.map(item => {
        const date = new Date(item.month + '-01');
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });
    const savings = monthlyData.map(item => parseFloat(item.savings));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monthly Savings (₹)',
                data: savings,
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(102, 126, 234)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });
});
</script>
@endif
@endpush
@endsection