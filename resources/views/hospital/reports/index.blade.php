
@extends('layouts.dashboard')

@section('title', 'Hospital Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Hospital Reports & Analytics
                </h5>
            </div>
            <div class="card-body">
                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{ $totalAvailments }}</h3>
                                <p class="mb-0">Total Availments</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1">₹{{ number_format($totalDiscount, 2) }}</h3>
                                <p class="mb-0">Total Discount Given</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1">₹{{ number_format($totalRevenue, 2) }}</h3>
                                <p class="mb-0">Total Revenue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-1">{{ $serviceStats->count() }}</h3>
                                <p class="mb-0">Active Services</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Performance -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Service Performance</h6>
                            </div>
                            <div class="card-body">
                                @if($serviceStats->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Service</th>
                                                    <th>Availments</th>
                                                    <th>Discount Given</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($serviceStats as $service)
                                                    <tr>
                                                        <td>{{ $service->name }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $service->availments_count }}</span>
                                                        </td>
                                                        <td>₹{{ number_format($service->availments_sum_discount_amount ?? 0, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">No service data available</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Monthly Statistics ({{ date('Y') }})</h6>
                            </div>
                            <div class="card-body">
                                @if($monthlyStats->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Availments</th>
                                                    <th>Discount Given</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($monthlyStats as $stat)
                                                    <tr>
                                                        <td>{{ date('F', mktime(0, 0, 0, $stat->month, 1)) }}</td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $stat->count }}</span>
                                                        </td>
                                                        <td>₹{{ number_format($stat->total_discount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">No monthly data available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Export Reports</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf"></i> Export to PDF
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                                        <i class="fas fa-file-excel"></i> Export to Excel
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="printReport()">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Implementation for PDF export
    alert('PDF export functionality will be implemented');
}

function exportToExcel() {
    // Implementation for Excel export
    alert('Excel export functionality will be implemented');
}

function printReport() {
    window.print();
}
</script>
@endsection
