@extends('layouts.dashboard')

@section('title', 'Patients')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Patient List</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" 
                                   placeholder="Search by name, mobile..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Age/Gender</th>
                                    <th>Blood Group</th>
                                    <th>Mobile</th>
                                    <th>Total Visits</th>
                                    <th>Total Savings</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                    <tr>
                                        <td>
                                            <strong>{{ $patient->name }}</strong><br>
                                            <small class="text-muted">{{ $patient->email }}</small>
                                        </td>
                                        <td>{{ $patient->age }} / {{ $patient->gender }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                                        </td>
                                        <td>{{ $patient->mobile }}</td>
                                        <td>{{ $patient->availments_count }}</td>
                                        <td class="text-success">
                                            ₹{{ number_format($patient->availments_sum_discount_amount, 2) }}
                                        </td>
                                        <td>
                                            @if($patient->availments->first())
                                                {{ $patient->availments->first()->availment_date->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('hospital.patients.show', $patient->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $patients->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No patients found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection