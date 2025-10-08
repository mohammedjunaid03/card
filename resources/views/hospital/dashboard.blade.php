@extends('layouts.dashboard')

@section('title', 'Hospital Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3>Welcome, {{ $hospital->name }}!</h3>
                        <p class="mb-0">{{ $hospital->city }}, {{ $hospital->state }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-light text-primary fs-6">
                            Status: {{ ucfirst($hospital->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Patients</h6>
                        <h3 class="mb-0">{{ $analytics['total_patients'] }}</h3>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Availments</h6>
                        <h3 class="mb-0">{{ $analytics['total_availments'] }}</h3>
                    </div>
                    <i class="fas fa-receipt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Discount Given</h6>
                        <h3 class="mb-0">₹{{ number_format($analytics['total_discount_given'], 2) }}</h3>
                    </div>
                    <i class="fas fa-tags fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Services</h6>
                        <h3 class="mb-0">{{ $hospital->services->count() }}</h3>
                    </div>
                    <i class="fas fa-stethoscope fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('hospital.verify-card') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-qrcode text-primary"></i> Verify Health Card
                </a>
                <a href="{{ route('hospital.availments.create') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-plus text-success"></i> Record New Availment
                </a>
                <a href="{{ route('hospital.services.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-cog text-info"></i> Manage Services
                </a>
                <a href="{{ route('hospital.reports') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-bar text-warning"></i> View Reports
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Patients -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Patients</h5>
            </div>
            <div class="card-body">
                @if($recentPatients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPatients as $availment)
                                    <tr>
                                        <td>
                                            <strong>{{ $availment->user->name }}</strong><br>
                                            <small class="text-muted">{{ $availment->user->blood_group }}</small>
                                        </td>
                                        <td>{{ $availment->service->name }}</td>
                                        <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $availment->discount_percentage }}%
                                            </span>
                                        </td>
                                        <td>₹{{ number_format($availment->final_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">No recent patients</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Service-wise Performance -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Service-wise Performance</h5>
            </div>
            <div class="card-body">
                @if($analytics['service_wise_data']->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Availments</th>
                                    <th>Total Discount</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['service_wise_data'] as $data)
                                    <tr>
                                        <td>{{ $data->service->name }}</td>
                                        <td>{{ $data->count }}</td>
                                        <td>₹{{ number_format($data->total_discount, 2) }}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" 
                                                     style="width: {{ ($data->count / $analytics['total_availments']) * 100 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">No data available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection