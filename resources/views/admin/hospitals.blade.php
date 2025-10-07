@extends('layouts.dashboard')

@section('title', 'Hospital Management')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search hospitals..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="city" class="form-control" 
                               placeholder="City" 
                               value="{{ request('city') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hospitals ({{ $hospitals->total() }})</h5>
                <a href="{{ route('admin.hospitals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Hospital
                </a>
            </div>
            <div class="card-body">
                @if($hospitals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Hospital</th>
                                    <th>Location</th>
                                    <th>Contact</th>
                                    <th>Services</th>
                                    <th>Status</th>
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
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: contain;"
                                                         alt="Logo">
                                                @endif
                                                <strong>{{ $hospital->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $hospital->city }}, {{ $hospital->state }}<br>
                                            <small class="text-muted">{{ $hospital->pincode }}</small>
                                        </td>
                                        <td>
                                            {{ $hospital->email }}<br>
                                            <small class="text-muted">{{ $hospital->mobile }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $hospital->services->count() }} Services</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger',
                                                    'blocked' => 'secondary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$hospital->status] }}">
                                                {{ ucfirst($hospital->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $hospital->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewModal{{ $hospital->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                @if($hospital->status == 'pending')
                                                    <form method="POST" action="{{ route('admin.hospitals.approve', $hospital->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form method="POST" action="{{ route('admin.hospitals.reject', $hospital->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal{{ $hospital->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $hospital->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted">Address</label>
                                                            <p>{{ $hospital->address }}</p>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted">Contact</label>
                                                            <p>{{ $hospital->mobile }}<br>{{ $hospital->email }}</p>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="text-muted">Services</label>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @foreach($hospital->services as $service)
                                                                    <span class="badge bg-success">
                                                                        {{ $service->name }} - {{ $service->pivot->discount_percentage }}%
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="text-muted">Registration Doc</label>
                                                            @if($hospital->registration_doc)
                                                                <p><a href="{{ asset('storage/' . $hospital->registration_doc) }}" target="_blank">View</a></p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="text-muted">PAN</label>
                                                            @if($hospital->pan_doc)
                                                                <p><a href="{{ asset('storage/' . $hospital->pan_doc) }}" target="_blank">View</a></p>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="text-muted">GST</label>
                                                            @if($hospital->gst_doc)
                                                                <p><a href="{{ asset('storage/' . $hospital->gst_doc) }}" target="_blank">View</a></p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $hospitals->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-hospital fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hospitals found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection