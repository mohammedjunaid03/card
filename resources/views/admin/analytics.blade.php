@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="row">
    <!-- Key Metrics Cards -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <h3 class="stat-value">{{ $totalUsers }}</h3>
                    <p class="stat-label">Total Users</p>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +12% this month
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon success">
                            <i class="fas fa-hospital"></i>
                        </div>
                    </div>
                    <h3 class="stat-value">{{ $totalHospitals }}</h3>
                    <p class="stat-label">Partner Hospitals</p>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +3 this month
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon info">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>
                    <h3 class="stat-value">{{ $totalHealthCards }}</h3>
                    <p class="stat-label">Health Cards Issued</p>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +8% this month
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon warning">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                    </div>
                    <h3 class="stat-value">{{ $totalAvailments }}</h3>
                    <p class="stat-label">Total Availments</p>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +15% this month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Registration Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="userTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hospital Status</h5>
            </div>
            <div class="card-body">
                <canvas id="hospitalStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top Services by Usage</h5>
            </div>
            <div class="card-body">
                <canvas id="servicesChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Geographic Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="geographicChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Real-time Activity -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Recent User Registrations</h6>
                        <div class="list-group">
                            @for($i = 0; $i < 5; $i++)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Demo User {{ $i + 1 }}</strong><br>
                                        <small class="text-muted">Registered {{ now()->subDays($i)->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">New</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Recent Hospital Activities</h6>
                        <div class="list-group">
                            @for($i = 0; $i < 5; $i++)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Demo Hospital {{ $i + 1 }}</strong><br>
                                        <small class="text-muted">Updated services {{ now()->subHours($i * 2)->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-success rounded-pill">Active</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Trend Chart
    const userCtx = document.getElementById('userTrendChart').getContext('2d');
    const monthlyData = @json($monthlyUsers);
    
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const userCounts = new Array(12).fill(0);
    
    monthlyData.forEach(data => {
        userCounts[data.month - 1] = data.count;
    });
    
    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'New Users',
                data: userCounts,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Hospital Status Chart
    const hospitalCtx = document.getElementById('hospitalStatusChart').getContext('2d');
    const hospitalData = @json($hospitalStats);
    
    new Chart(hospitalCtx, {
        type: 'doughnut',
        data: {
            labels: hospitalData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
            datasets: [{
                data: hospitalData.map(item => item.count),
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Services Chart
    const servicesCtx = document.getElementById('servicesChart').getContext('2d');
    new Chart(servicesCtx, {
        type: 'bar',
        data: {
            labels: ['General Medicine', 'Cardiology', 'Dermatology', 'Pediatrics', 'Orthopedics'],
            datasets: [{
                label: 'Usage Count',
                data: [45, 32, 28, 25, 20],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Geographic Chart
    const geoCtx = document.getElementById('geographicChart').getContext('2d');
    new Chart(geoCtx, {
        type: 'pie',
        data: {
            labels: ['Delhi', 'Mumbai', 'Bangalore', 'Chennai', 'Kolkata'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection
