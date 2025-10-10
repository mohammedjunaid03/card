@extends('layouts.dashboard')

@section('title', 'Patient Availment Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user"></i> Patient Details</h2>
            <a href="{{ route('hospital.availments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Availments
            </a>
        </div>
        
        @if($patient)
            <div class="row">
                <!-- Patient Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Patient Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                                    {{ substr($patient->name, 0, 1) }}
                                </div>
                            </div>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $patient->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $patient->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mobile:</strong></td>
                                    <td>{{ $patient->mobile }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Age:</strong></td>
                                    <td>{{ $patient->age ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ ucfirst($patient->gender ?? 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blood Group:</strong></td>
                                    <td>{{ $patient->blood_group ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Availments History -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-history"></i> Availment History</h5>
                        </div>
                        <div class="card-body">
                            @if($availments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Service</th>
                                                <th>Original Amount</th>
                                                <th>Discount</th>
                                                <th>Final Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($availments as $availment)
                                                <tr>
                                                    <td>{{ $availment->availment_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <strong>{{ $availment->service->name ?? 'N/A' }}</strong>
                                                        @if($availment->service)
                                                            <br>
                                                            <small class="text-muted">{{ $availment->service->description }}</small>
                                                        @endif
                                                    </td>
                                                    <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-warning">
                                                            {{ $availment->discount_percentage }}%
                                                        </span>
                                                        <br>
                                                        <small>₹{{ number_format($availment->discount_amount, 2) }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>₹{{ number_format($availment->final_amount, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $availment->status === 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($availment->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Summary -->
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5 class="text-primary">{{ $availments->count() }}</h5>
                                                <small class="text-muted">Total Visits</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5 class="text-success">₹{{ number_format($availments->sum('discount_amount'), 2) }}</h5>
                                                <small class="text-muted">Total Discount Given</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5 class="text-info">₹{{ number_format($availments->sum('final_amount'), 2) }}</h5>
                                                <small class="text-muted">Total Revenue</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Availments Found</h5>
                                    <p class="text-muted">This patient hasn't availed any services yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Patient not found.
            </div>
        @endif
    </div>
</div>
@endsection
