@extends('layouts.dashboard')

@section('title', 'Patients')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Patients
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('hospital.availments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Record New Availment
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Blood Group</th>
                                    <th>Total Visits</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">#{{ $patient->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($patient->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $patient->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $patient->gender }}, {{ $patient->age }} years</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->mobile }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $patient->availments->count() }}</span>
                                        </td>
                                        <td>
                                            @if($patient->availments->count() > 0)
                                                {{ $patient->availments->first()->created_at->format('d M Y') }}
                                                <br>
                                                <small class="text-muted">{{ $patient->availments->first()->created_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">No visits</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hospital.patients.show', $patient->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hospital.availments.create', ['patient_id' => $patient->id]) }}" 
                                                   class="btn btn-outline-success btn-sm" 
                                                   title="Record Availment">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $patients->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-users fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Patients Found</h5>
                        <p class="text-muted">No patients have visited your hospital yet.</p>
                        <a href="{{ route('hospital.availments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Record First Availment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Patient Statistics Cards -->
@if($patients->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Patients</h6>
                        <h3 class="mb-0">{{ $patients->total() }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Active Patients</h6>
                        <h3 class="mb-0">{{ $patients->where('status', 'active')->count() }}</h3>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Recent Visits</h6>
                        <h3 class="mb-0">{{ $patients->filter(function($patient) { return $patient->availments->where('created_at', '>=', now()->subDays(7))->count() > 0; })->count() }}</h3>
                    </div>
                    <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Visits</h6>
                        <h3 class="mb-0">{{ $patients->sum(function($patient) { return $patient->availments->count(); }) }}</h3>
                    </div>
                    <i class="fas fa-hospital fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: bold;
}
</style>
@endsection