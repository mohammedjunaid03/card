@extends('layouts.dashboard')

@section('title', 'Patient Availments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Patient Availments</h4>
                <div class="page-title-right">
                    <a href="{{ route('hospital.patient-availments.verify-card') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Record New Availment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($availments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Discount</th>
                                        <th>Final Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availments as $availment)
                                        <tr>
                                            <td>{{ $availment->user->name }}</td>
                                            <td>{{ $availment->service->name ?? 'N/A' }}</td>
                                            <td>{{ $availment->availment_date->format('M d, Y') }}</td>
                                            <td>₹{{ number_format($availment->total_amount, 2) }}</td>
                                            <td>₹{{ number_format($availment->discount_amount, 2) }}</td>
                                            <td class="text-success">
                                                <strong>₹{{ number_format($availment->total_amount - $availment->discount_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $availments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Availments Found</h5>
                            <p class="text-muted">No patients have availed services yet.</p>
                            <a href="{{ route('hospital.patient-availments.verify-card') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Record First Availment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
