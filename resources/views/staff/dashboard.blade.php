@extends('layouts.dashboard')

@section('title', 'Staff Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h3>Welcome, {{ auth()->guard('staff')->user()->name }}!</h3>
                <p class="mb-0">Staff Member Dashboard</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus"></i> Register New User</h5>
            </div>
            <div class="card-body">
                <p>Help users register for health cards in-person</p>
                <a href="{{ route('staff.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Register User
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-hospital"></i> Register New Hospital</h5>
            </div>
            <div class="card-body">
                <p>Register new hospitals to join the network</p>
                <a href="{{ route('staff.hospitals.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Register Hospital
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-file-check"></i> Verify Documents</h5>
            </div>
            <div class="card-body">
                <p>Review and verify uploaded documents</p>
                <a href="{{ route('staff.verify-documents') }}" class="btn btn-warning">
                    <i class="fas fa-check"></i> Verify Documents
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> View Reports</h5>
            </div>
            <div class="card-body">
                <p>Access performance and activity reports</p>
                <a href="{{ route('staff.reports') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> View Reports
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No recent activity</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection