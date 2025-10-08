@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3>Welcome, {{ auth()->guard('admin')->user()->name }}!</h3>
                <p class="mb-0">Administrator Dashboard</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Users</p>
                            <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle">
                                <span class="avatar-title rounded-circle bg-primary font-size-18">
                                    <i class="fas fa-users text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Hospitals</p>
                            <h4 class="mb-0">{{ \App\Models\Hospital::count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle">
                                <span class="avatar-title rounded-circle bg-success font-size-18">
                                    <i class="fas fa-hospital text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Health Cards</p>
                            <h4 class="mb-0">{{ \App\Models\HealthCard::count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle">
                                <span class="avatar-title rounded-circle bg-info font-size-18">
                                    <i class="fas fa-id-card text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Staff Members</p>
                            <h4 class="mb-0">{{ \App\Models\Staff::count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle">
                                <span class="avatar-title rounded-circle bg-warning font-size-18">
                                    <i class="fas fa-user-tie text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Manage Users</h5>
            </div>
            <div class="card-body">
                <p>View and manage all registered users</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> View Users
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-hospital"></i> Manage Hospitals</h5>
            </div>
            <div class="card-body">
                <p>View and manage all registered hospitals</p>
                <a href="{{ route('admin.hospitals.index') }}" class="btn btn-success">
                    <i class="fas fa-list"></i> View Hospitals
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-user-tie"></i> Manage Staff</h5>
            </div>
            <div class="card-body">
                <p>View and manage all staff members</p>
                <a href="{{ route('admin.staff.index') }}" class="btn btn-warning">
                    <i class="fas fa-list"></i> View Staff
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Analytics</h5>
            </div>
            <div class="card-body">
                <p>View system analytics and reports</p>
                <a href="{{ route('admin.analytics') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> View Analytics
                </a>
            </div>
        </div>
    </div>
</div>
@endsection