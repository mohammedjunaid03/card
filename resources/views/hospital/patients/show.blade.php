@extends('layouts.dashboard')

@section('title', 'Patient Details')

@section('content')
<div class="row">
    <!-- Patient Information Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user"></i> Patient Information
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                        {{ substr($patient->name, 0, 1) }}
                    </div>
                    <h4 class="mt-3 mb-1">{{ $patient->name }}</h4>
                    <p class="text-muted mb-0">Patient ID: #{{ $patient->id }}</p>
                </div>
                
                <div class="row">
                    <div class="col-12 mb-3">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $patient->email }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Mobile:</strong><br>
                        <span class="text-muted">{{ $patient->mobile }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Date of Birth:</strong><br>
                        <span class="text-muted">{{ $patient->date_of_birth->format('d M Y') }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Age:</strong><br>
                        <span class="text-muted">{{ $patient->age }} years</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Gender:</strong><br>
                        <span class="text-muted">{{ $patient->gender }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Blood Group:</strong><br>
                        <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Address:</strong><br>
                        <span class="text-muted">{{ $patient->address }}</span>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $patient->status === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($patient->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('hospital.availments.create', ['patient_id' => $patient->id]) }}" 
                       class="btn btn-success">
                        <i class="fas fa-plus"></i> Record New Availment
                    </a>
                    <a href="{{ route('hospital.patients.index') }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Patients
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Patient Availments -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt"></i> Medical History
                    </h5>
                    <span class="badge bg-primary">{{ $availments->count() }} Visits</span>
                </div>
            </div>
            <div class="card-body">
                @if($availments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th>Original Amount</th>
                                    <th>Discount</th>
                                    <th>Final Amount</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availments as $availment)
                                    <tr>
                                        <td>
                                            <strong>{{ $availment->availment_date->format('d M Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $availment->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $availment->service->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $availment->service->category }}</small>
                                        </td>
                                        <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $availment->discount_percentage }}%
                                            </span>
                                            <br>
                                            <small class="text-muted">₹{{ number_format($availment->discount_amount, 2) }}</small>
                                        </td>
                                        <td>
                                            <strong>₹{{ number_format($availment->final_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($availment->notes)
                                                <span class="text-muted">{{ Str::limit($availment->notes, 50) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-receipt fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Medical History</h5>
                        <p class="text-muted">This patient hasn't received any services yet.</p>
                        <a href="{{ route('hospital.availments.create', ['patient_id' => $patient->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Record First Service
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Patient Statistics -->
        @if($availments->count() > 0)
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ $availments->count() }}</h3>
                        <p class="mb-0">Total Visits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">₹{{ number_format($availments->sum('discount_amount'), 2) }}</h3>
                        <p class="mb-0">Total Discount</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">₹{{ number_format($availments->sum('final_amount'), 2) }}</h3>
                        <p class="mb-0">Total Amount</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
    font-weight: bold;
}
</style>
@endsection