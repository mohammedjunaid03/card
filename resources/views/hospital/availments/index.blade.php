@extends('layouts.dashboard')

@section('title', 'Patient Availments')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users"></i> Patient Availments</h2>
            <a href="{{ route('hospital.availments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Record New Availment
            </a>
        </div>
        
        @if($patients->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Total Availments</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($patient->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $patient->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $patient->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->mobile }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $patient->availments->count() }} availments
                                            </span>
                                        </td>
                                        <td>
                                            @if($patient->availments->count() > 0)
                                                {{ $patient->availments->first()->created_at->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">No visits</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('hospital.availments.show', $patient->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
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
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-users fa-5x text-muted"></i>
                </div>
                <h4 class="text-muted">No Patients Found</h4>
                <p class="text-muted">No patients have visited your hospital yet.</p>
                <a href="{{ route('hospital.availments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Record First Availment
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
