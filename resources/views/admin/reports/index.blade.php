@extends('layouts.dashboard')

@section('title', 'Reports & Analytics')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
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
                    <p class="stat-label">Total Hospitals</p>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Registration Trends (2025)</h5>
            </div>
            <div class="card-body">
                <canvas id="userRegistrationChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hospital Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="hospitalStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Export Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button onclick="exportReport('users')" class="btn btn-outline-primary">
                                <i class="fas fa-users"></i> Export Users
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button onclick="exportReport('hospitals')" class="btn btn-outline-success">
                                <i class="fas fa-hospital"></i> Export Hospitals
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button onclick="exportReport('health-cards')" class="btn btn-outline-info">
                                <i class="fas fa-id-card"></i> Export Health Cards
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid">
                            <button onclick="exportReport('availments')" class="btn btn-outline-warning">
                                <i class="fas fa-stethoscope"></i> Export Availments
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detailed Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Hospital Status Breakdown</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitalStats as $stat)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $stat->status == 'approved' ? 'success' : ($stat->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($stat->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $stat->count }}</td>
                                            <td>{{ $totalHospitals > 0 ? round(($stat->count / $totalHospitals) * 100, 1) : 0 }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Monthly User Registrations</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>New Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyUsers as $monthly)
                                        <tr>
                                            <td>{{ date('F', mktime(0, 0, 0, $monthly->month, 1)) }}</td>
                                            <td>{{ $monthly->count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    // User Registration Chart
    const userCtx = document.getElementById('userRegistrationChart').getContext('2d');
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
});

function exportReport(type) {
    window.location.href = `{{ route('admin.reports.export') }}?type=${type}`;
}
</script>
@endsection
