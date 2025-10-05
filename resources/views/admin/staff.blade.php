@extends('layouts.dashboard')

@section('title', 'Staff Management')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add New Staff</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.staff.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')                        <h5 class="text-muted">No services added yet</h5>
                        <p>Add your first service from the form on the left</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
 @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mobile *</label>
                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                               maxlength="10" required>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Add Staff
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Staff Members ({{ $staff->total() }})</h5>
            </div>
            <div class="card-body">
                @if($staff->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff as $member)
                                    <tr>
                                        <td><strong>{{ $member->name }}</strong></td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->mobile }}</td>
                                        <td>
                                            <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($member->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $member->created_at->format('d M Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal{{ $member->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form method="POST" action="{{ route('admin.staff.destroy', $member->id) }}" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Delete this staff member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $member->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.staff.update', $member->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Staff Member</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control" 
                                                                   value="{{ $member->name }}" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Email</label>
                                                            <input type="email" name="email" class="form-control" 
                                                                   value="{{ $member->email }}" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Mobile</label>
                                                            <input type="text" name="mobile" class="form-control" 
                                                                   value="{{ $member->mobile }}" required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-control">
                                                                <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">New Password (leave blank to keep current)</label>
                                                            <input type="password" name="password" class="form-control">
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
                    
                    <div class="mt-3">
                        {{ $staff->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No staff members yet</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection