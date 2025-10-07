@extends('layouts.dashboard')

@section('title', 'Hospital Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Hospital Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Hospital
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" id="searchInput" class="form-control" 
                                   placeholder="Search by name, email, mobile..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" id="statusFilter" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="city" id="cityFilter" class="form-control">
                                <option value="">All Cities</option>
                                @foreach(\App\Models\Hospital::distinct()->pluck('city') as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="state" id="stateFilter" class="form-control">
                                <option value="">All States</option>
                                @foreach(\App\Models\Hospital::distinct()->pluck('state') as $state)
                                    <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="contract_status" id="contractStatusFilter" class="form-control">
                                <option value="">All Contract Status</option>
                                <option value="active" {{ request('contract_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('contract_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="terminated" {{ request('contract_status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                <option value="pending_renewal" {{ request('contract_status') == 'pending_renewal' ? 'selected' : '' }}>Pending Renewal</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100" title="Apply Filters">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Active Filters Display -->
                    @if(request()->hasAny(['search', 'status', 'city', 'state', 'contract_status']))
                        <div class="mt-3">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="text-muted">Active filters:</span>
                                @if(request('search'))
                                    <span class="badge bg-primary">
                                        Search: "{{ request('search') }}"
                                        <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('search')"></button>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="badge bg-info">
                                        Status: {{ ucfirst(request('status')) }}
                                        <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('status')"></button>
                                    </span>
                                @endif
                                @if(request('city'))
                                    <span class="badge bg-warning text-dark">
                                        City: {{ request('city') }}
                                        <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('city')"></button>
                                    </span>
                                @endif
                                @if(request('state'))
                                    <span class="badge bg-success">
                                        State: {{ request('state') }}
                                        <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('state')"></button>
                                    </span>
                                @endif
                                @if(request('contract_status'))
                                    <span class="badge bg-danger">
                                        Contract: {{ ucfirst(str_replace('_', ' ', request('contract_status'))) }}
                                        <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('contract_status')"></button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Hospitals ({{ $hospitals->total() }})</h5>
                    <div>
                        <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Hospital
                        </a>
                        <button onclick="exportHospitals()" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($hospitals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Hospital Details</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Contract Status</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hospitals as $hospital)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($hospital->logo_path)
                                                        <img src="{{ asset('storage/' . $hospital->logo_path) }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;"
                                                             alt="Logo">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-hospital text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $hospital->name }}</h6>
                                                        <small class="text-muted">License: {{ $hospital->license_number }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">Email:</small><br>
                                                    <strong>{{ $hospital->email }}</strong>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">Mobile:</small><br>
                                                    <strong>{{ $hospital->mobile }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">City:</small><br>
                                                    <strong>{{ $hospital->city }}</strong>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">State:</small><br>
                                                    <strong>{{ $hospital->state }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                @if($hospital->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($hospital->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($hospital->contract_start_date && $hospital->contract_end_date)
                                                    {!! $hospital->getContractStatusBadge() !!}
                                                @else
                                                    <span class="badge bg-secondary">No Contract</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $hospital->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.hospitals.show', $hospital->id) }}" 
                                                       class="btn btn-outline-primary btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" 
                                                       class="btn btn-outline-secondary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.hospitals.contract', $hospital->id) }}" 
                                                       class="btn btn-outline-info btn-sm" title="Contract Management">
                                                        <i class="fas fa-file-contract"></i>
                                                    </a>
                                                    @if($hospital->status === 'pending')
                                                        <form method="POST" action="{{ route('admin.hospitals.approve', $hospital->id) }}" 
                                                              style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success btn-sm" 
                                                                    title="Approve" onclick="return confirm('Approve this hospital?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.hospitals.reject', $hospital->id) }}" 
                                                              style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                    title="Reject" onclick="return confirm('Reject this hospital?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $hospitals->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-hospital fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hospitals found</h5>
                            <p class="text-muted">Get started by adding your first hospital.</p>
                            <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Hospital
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const cityFilter = document.getElementById('cityFilter');
    const stateFilter = document.getElementById('stateFilter');
    const contractStatusFilter = document.getElementById('contractStatusFilter');
    
    // Auto-submit form when filters change (with debounce for search)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2 || this.value.length === 0) {
                filterForm.submit();
            }
        }, 500);
    });
    
    // Auto-submit when dropdowns change
    [statusFilter, cityFilter, stateFilter, contractStatusFilter].forEach(filter => {
        filter.addEventListener('change', function() {
            filterForm.submit();
        });
    });
    
    // Remove individual filter
    window.removeFilter = function(filterName) {
        const input = document.querySelector(`[name="${filterName}"]`);
        if (input) {
            input.value = '';
            filterForm.submit();
        }
    };
});

// Export function
window.exportHospitals = function() {
    window.location.href = '{{ route("admin.reports.export") }}?type=hospitals';
};
</script>
@endsection
