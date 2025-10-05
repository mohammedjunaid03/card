@extends('layouts.dashboard')

@section('title', 'Manage Services')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add New Service</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital.services.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Select Service *</label>
                        <select name="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                            <option value="">Choose a service</option>
                            @foreach($availableServices as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }} ({{ $service->category }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Discount Percentage * (%)</label>
                        <input type="number" name="discount_percentage" 
                               class="form-control @error('discount_percentage') is-invalid @enderror" 
                               min="0" max="100" step="0.01" required>
                        @error('discount_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Services</h5>
            </div>
            <div class="card-body">
                @if($services->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Service Name</th>
                                    <th>Category</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td><strong>{{ $service->service->name }}</strong></td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $service->service->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $service->discount_percentage }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if($service->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $service->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form method="POST" action="{{ route('hospital.services.destroy', $service->id) }}" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Remove this service?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('hospital.services.update', $service->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Service</label>
                                                            <input type="text" class="form-control" 
                                                                   value="{{ $service->service->name }}" disabled>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Discount Percentage (%)</label>
                                                            <input type="number" name="discount_percentage" 
                                                                   class="form-control" 
                                                                   value="{{ $service->discount_percentage }}" 
                                                                   min="0" max="100" step="0.01" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" class="form-control" rows="3">{{ $service->description }}</textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="is_active" 
                                                                       class="form-check-input" 
                                                                       value="1" 
                                                                       {{ $service->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label">Active</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-stethoscope fa-4x text-muted mb-3"></i>
               <h5 class="text-muted">No services added yet</h5>
                        <p>Add your first service from the form on the left</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection