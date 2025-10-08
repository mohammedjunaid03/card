@extends('layouts.dashboard')

@section('title', 'Staff Member Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0">Staff Member Details</h2>
                <div class="btn-group">
                    <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Staff Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Staff Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $staff->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $staff->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mobile:</strong></td>
                                            <td>{{ $staff->mobile }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Role:</strong></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ ucfirst(str_replace('_', ' ', $staff->role)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($staff->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Hospital:</strong></td>
                                            <td>{{ $staff->hospital->name ?? 'Not Assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $staff->created_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Last Updated:</strong></td>
                                            <td>{{ $staff->updated_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($staff->notes)
                                <div class="mt-3">
                                    <h6>Notes:</h6>
                                    <p class="text-muted">{{ $staff->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions and Status -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.staff.edit', $staff) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Staff Member
                                </a>
                                
                                <form method="POST" action="{{ route('admin.staff.status', $staff) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $staff->status === 'active' ? 'inactive' : 'active' }}">
                                    <button type="submit" class="btn btn-{{ $staff->status === 'active' ? 'warning' : 'success' }} w-100"
                                            onclick="return confirm('Are you sure you want to {{ $staff->status === 'active' ? 'deactivate' : 'activate' }} this staff member?')">
                                        <i class="fas fa-{{ $staff->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                        {{ $staff->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Hospital Information -->
                    @if($staff->hospital)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Assigned Hospital</h5>
                            </div>
                            <div class="card-body">
                                <h6>{{ $staff->hospital->name }}</h6>
                                <p class="text-muted mb-2">{{ $staff->hospital->location }}</p>
                                <p class="text-muted mb-0">{{ $staff->hospital->contact_number }}</p>
                                
                                <div class="mt-3">
                                    <a href="{{ route('admin.hospitals.show', $staff->hospital) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Hospital
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
