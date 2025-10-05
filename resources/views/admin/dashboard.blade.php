@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h3>Welcome, Super Admin!</h3>
                <p class="mb-0">Complete system overview and management</p>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Users</h6>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up"></i> {{ $stats['active_users'] }} Active
                        </small>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Hospitals</h6>
                        <h3 class="mb-0">{{ $stats['total_hospitals'] }}</h3>
                        <small class="text-info">
                            Partner Network
                        </small>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-hospital fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Cards Issued</h6>
                        <h3 class="mb-0">{{ $stats['total_cards_issued'] }}</h3>
                        <small class="text-success">
                            {{ $stats['active_cards'] }} Active
                        </small>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-id-card fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Savings</h6>
                        <h3 class="mb-0">₹{{ number_format($stats['total_discount_given'], 2) }}</h3>
                        <small class="text-muted">
                            {{ $stats['total_availments'] }} Availments
                        </small>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-rupee-sign fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Monthly Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="trendsChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pending Approvals</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.hospitals.index', ['status' => 'pending']) }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-hospital text-warning"></i> Pending Hospital Approvals</span>
                    <span class="badge bg-warning">{{ $pendingHospitals ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.card-approvals') }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-id-card text-info"></i> Pending Card Approvals</span>
                    <span class="badge bg-info">{{ $pendingCards ?? 0 }}</span>
                </a>
                <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user text-primary"></i> Pending User Verifications</span>
                    <span class="badge bg-primary">{{ $pendingUsers ?? 0 }}</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                                      <i class="fas fa-users text-primary"></i> Manage Users
                </a>
                <a href="{{ route('admin.hospitals.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-hospital text-success"></i> Manage Hospitals
                </a>
                <a href="{{ route('admin.staff.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-tie text-info"></i> Manage Staff
                </a>
                <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-bar text-warning"></i> Generate Reports
                </a>
                <a href="{{ route('admin.notifications') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-bell text-danger"></i> Send Notifications
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($trends['users']->pluck('month')) !!},
        datasets: [{
            label: 'Users',
            data: {!! json_encode($trends['users']->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Cards',
            data: {!! json_encode($trends['cards']->pluck('count')) !!},
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Pending', 'Blocked'],
        datasets: [{
            data: [{{ $stats['active_users'] }}, {{ $stats['total_users'] - $stats['active_users'] }}, 0],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush


FILE: resources/views/admin/users/index.blade.php
---------------------------------------------------
@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name, email, mobile..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="blood_group" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users ({{ $users->total() }})</h5>
                <button onclick="exportUsers()" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export
                </button>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User Details</th>
                                    <th>Contact</th>
                                    <th>Age/Gender/Blood</th>
                                    <th>Card Status</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->photo_path)
                                                    <img src="{{ asset('storage/' . $user->photo_path) }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;"
                                                         alt="Photo">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->email }}<br>
                                            <small class="text-muted">{{ $user->mobile }}</small>
                                        </td>
                                        <td>
                                            {{ $user->age }} / {{ $user->gender }}<br>
                                            <span class="badge bg-danger">{{ $user->blood_group }}</span>
                                        </td>
                                        <td>
                                            @if($user->healthCard)
                                                <span class="badge bg-success">Issued</span><br>
                                                <small>{{ $user->healthCard->card_number }}</small>
                                            @else
                                                <span class="badge bg-warning">Not Issued</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($user->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Blocked</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-warning dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($user->status == 'active')
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="blocked">
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-ban"></i> Block User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check"></i> Activate User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                                              onsubmit="return confirm('Delete this user?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportUsers() {
    window.location.href = '{{ route("admin.reports.export") }}?type=users';
}
</script>
@endpush