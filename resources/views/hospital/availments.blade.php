@extends('layouts.dashboard')

@section('title', 'Availments')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <a href="{{ route('hospital.availments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Record New Availment
        </a>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Service Availments</h5>
            </div>
            <div class="card-body">
                @if($availments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Patient</th>
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
                                        <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                        <td>
                                            <strong>{{ $availment->user->name }}</strong><br>
                                            <small class="text-muted">{{ $availment->user->mobile }}</small>
                                        </td>
                                        <td>{{ $availment->service->name }}</td>
                                        <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $availment->discount_percentage }}%
                                            </span><br>
                                            <small class="text-success">
                                                ₹{{ number_format($availment->discount_amount, 2) }}
                                            </small>
                                        </td>
                                        <td><strong>₹{{ number_format($availment->final_amount, 2) }}</strong></td>
                                        <td>
                                            @if($availment->notes)
                                                <button class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="{{ $availment->notes }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $availments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No availments recorded yet</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection