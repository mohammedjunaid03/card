@extends('layouts.dashboard')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" 
                               value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" 
                               value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-control">
                            <option value="">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->service_id }}" 
                                        {{ request('service_id') == $service->service_id ? 'selected' : '' }}>
                                    {{ $service->service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('hospital.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Patients</h6>
                <h3>{{ $summary['total_patients'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Availments</h6>
                <h3>{{ $summary['total_availments'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Revenue</h6>
                <h3>₹{{ number_format($summary['total_revenue'] ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Total Discount</h6>
                <h3>₹{{ number_format($summary['total_discount'] ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>
    
    <!-- Detailed Report -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detailed Report</h5>
                <button onclick="exportReport()" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
            </div>
            <div class="card-body">
                @if(isset($availments) && $availments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Original</th>
                                    <th>Discount %</th>
                                    <th>Discount ₹</th>
                                    <th>Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availments as $availment)
                                    <tr>
                                        <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                        <td>{{ $availment->user->name }}</td>
                                        <td>{{ $availment->service->name }}</td>
                                        <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                        <td>{{ $availment->discount_percentage }}%</td>
                                        <td>₹{{ number_format($availment->discount_amount, 2) }}</td>
                                        <td>₹{{ number_format($availment->final_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3">TOTAL</th>
                                    <th>₹{{ number_format($availments->sum('original_amount'), 2) }}</th>
                                    <th>-</th>
                                    <th>₹{{ number_format($availments->sum('discount_amount'), 2) }}</th>
                                    <th>₹{{ number_format($availments->sum('final_amount'), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">No data available for selected filters</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportReport() {
    window.location.href = '{{ route("hospital.reports") }}?export=excel&' + new URLSearchParams(window.location.search).toString();
}
</script>
@endpush