@extends('layouts.dashboard')

@section('title', 'Staff Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Staff Reports</h5>
            </div>
            <div class="card-body">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Patients</h5>
                                <h3 class="mb-0">{{ $summary['total_patients'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Availments</h5>
                                <h3 class="mb-0">{{ $summary['total_availments'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Discount Given</h5>
                                <h3 class="mb-0">₹{{ number_format($summary['total_discount'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title">Active Hospitals</h5>
                                <h3 class="mb-0">{{ $summary['total_hospitals'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Filter Reports</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('staff.reports') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" 
                                               value="{{ request('from_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" 
                                               value="{{ request('to_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="hospital_id" class="form-label">Hospital</label>
                                        <select class="form-control" id="hospital_id" name="hospital_id">
                                            <option value="">All Hospitals</option>
                                            @foreach($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}" 
                                                        {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                                    {{ $hospital->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Availments Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Patient Availments</h6>
                    </div>
                    <div class="card-body">
                        @if($availments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Hospital</th>
                                            <th>Service</th>
                                            <th>Availment Date</th>
                                            <th>Discount Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($availments as $availment)
                                            <tr>
                                                <td>{{ $availment->user->name ?? 'N/A' }}</td>
                                                <td>{{ $availment->hospital->name ?? 'N/A' }}</td>
                                                <td>{{ $availment->service->name ?? 'N/A' }}</td>
                                                <td>{{ $availment->availment_date ? \Carbon\Carbon::parse($availment->availment_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>₹{{ number_format($availment->discount_amount ?? 0, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $availment->status === 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($availment->status ?? 'pending') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $availments->links() }}
                        @else
                            <div class="alert alert-info">No availments found for the selected criteria.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
