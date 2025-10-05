@extends('layouts.dashboard')

@section('title', 'Discount History')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-0">Total Savings: ₹{{ number_format($totalSavings, 2) }}</h3>
                        <p class="mb-0">You've saved money on {{ $availments->total() }} medical services</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-piggy-bank fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Discount History</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('user.discount-history') }}" class="row g-2">
                            <div class="col-md-4">
                                <input type="date" name="from_date" class="form-control form-control-sm" 
                                       placeholder="From Date" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="to_date" class="form-control form-control-sm" 
                                       placeholder="To Date" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('user.discount-history') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($availments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Hospital</th>
                                    <th>Service</th>
                                    <th>Original Amount</th>
                                    <th>Discount %</th>
                                    <th>Discount Amount</th>
                                    <th>Final Amount</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availments as $availment)
                                    <tr>
                                        <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                        <td>
                                            <strong>{{ $availment->hospital->name }}</strong><br>
                                            <small class="text-muted">{{ $availment->hospital->city }}</small>
                                        </td>
                                        <td>{{ $availment->service->name }}</td>
                                        <td>₹{{ number_format($availment->original_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $availment->discount_percentage }}%</span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">
                                                ₹{{ number_format($availment->discount_amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>₹{{ number_format($availment->final_amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($availment->notes)
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="tooltip" title="{{ $availment->notes }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Savings:</strong></td>
                                    <td colspan="3">
                                        <strong class="text-success fs-5">
                                            ₹{{ number_format($totalSavings, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $availments->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No discount history found</h5>
                        <p>Start using your health card at partner hospitals to see your savings here</p>
                        <a href="{{ route('user.hospitals') }}" class="btn btn-primary">
                            <i class="fas fa-hospital"></i> Find Hospitals
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush