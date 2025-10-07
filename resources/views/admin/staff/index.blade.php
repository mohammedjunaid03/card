@extends('layouts.dashboard')

@section('title', 'Staff Management')

@section('content')
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
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="role" id="roleFilter" class="form-control">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="hospital_id" id="hospitalFilter" class="form-control">
                            <option value="">All Hospitals</option>
                            @foreach(\App\Models\Hospital::where('status', 'approved')->get() as $hospital)
                                <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                    {{ $hospital->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100" title="Apply Filters">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100" title="Clear All Filters">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </form>
                
                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'status', 'role', 'hospital_id']))
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
                            @if(request('role'))
                                <span class="badge bg-warning text-dark">
                                    Role: {{ ucfirst(request('role')) }}
                                    <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('role')"></button>
                                </span>
                            @endif
                            @if(request('hospital_id'))
                                <span class="badge bg-success">
                                    Hospital: {{ \App\Models\Hospital::find(request('hospital_id'))->name ?? 'Unknown' }}
                                    <button type="button" class="btn-close btn-close-white ms-1" onclick="removeFilter('hospital_id')"></button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Staff Members ({{ $staff->total() }})</h5>
                <div>
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Staff
                    </a>
                    <button onclick="exportStaff()" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($staff->count() > 0)
                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-success btn-sm" id="bulkActivate" disabled>
                                    <i class="fas fa-check"></i> Activate Selected
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="bulkDeactivate" disabled>
                                    <i class="fas fa-ban"></i> Deactivate Selected
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted" id="selectedCount">0 staff selected</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Staff Details</th>
                                    <th>Contact</th>
                                    <th>Role & Hospital</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff as $staffMember)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_staff[]" value="{{ $staffMember->id }}" class="form-check-input staff-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    {{ substr($staffMember->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $staffMember->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $staffMember->email }}<br>
                                            <small class="text-muted">{{ $staffMember->mobile }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst($staffMember->role) }}</span><br>
                                            <small class="text-muted">{{ $staffMember->hospital->name ?? 'No Hospital' }}</small>
                                        </td>
                                        <td>
                                            @if($staffMember->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($staffMember->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $staffMember->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.staff.show', $staffMember->id) }}" 
                                                   class="btn btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-warning dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($staffMember->status == 'active')
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.staff.status', $staffMember->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="inactive">
                                                                <button type="submit" class="dropdown-item text-warning">
                                                                    <i class="fas fa-ban"></i> Deactivate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.staff.status', $staffMember->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check"></i> Activate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a href="{{ route('admin.staff.edit', $staffMember->id) }}" class="dropdown-item">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.staff.destroy', $staffMember->id) }}" 
                                                              onsubmit="return confirm('Delete this staff member?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $staff->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No staff members found</h5>
                        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Staff Member
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
    const bulkButtons = document.querySelectorAll('#bulkActivate, #bulkDeactivate');
    const selectedCount = document.getElementById('selectedCount');
    
    // Filter functionality
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const roleFilter = document.getElementById('roleFilter');
    const hospitalFilter = document.getElementById('hospitalFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
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
    [statusFilter, roleFilter, hospitalFilter].forEach(filter => {
        filter.addEventListener('change', function() {
            filterForm.submit();
        });
    });
    
    // Clear all filters
    clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        roleFilter.value = '';
        hospitalFilter.value = '';
        filterForm.submit();
    });
    
    // Remove individual filter
    window.removeFilter = function(filterName) {
        const input = document.querySelector(`[name="${filterName}"]`);
        if (input) {
            input.value = '';
            filterForm.submit();
        }
    };

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        staffCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox change
    staffCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
        });
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = `${count} staff member${count !== 1 ? 's' : ''} selected`;
        
        bulkButtons.forEach(button => {
            button.disabled = count === 0;
        });

        // Update select all checkbox state
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === staffCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Bulk Activate Staff
    document.getElementById('bulkActivate').addEventListener('click', function() {
        const selectedStaff = Array.from(document.querySelectorAll('.staff-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedStaff.length === 0) {
            alert('Please select staff members to activate.');
            return;
        }

        if (confirm(`Are you sure you want to activate ${selectedStaff.length} staff member(s)?`)) {
            bulkUpdateStatus(selectedStaff, 'active');
        }
    });

    // Bulk Deactivate Staff
    document.getElementById('bulkDeactivate').addEventListener('click', function() {
        const selectedStaff = Array.from(document.querySelectorAll('.staff-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedStaff.length === 0) {
            alert('Please select staff members to deactivate.');
            return;
        }

        if (confirm(`Are you sure you want to deactivate ${selectedStaff.length} staff member(s)?`)) {
            bulkUpdateStatus(selectedStaff, 'inactive');
        }
    });

    function bulkUpdateStatus(staffIds, status) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.staff.bulk-status") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        staffIds.forEach(staffId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'staff_ids[]';
            input.value = staffId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Export function
    window.exportStaff = function() {
        window.location.href = '{{ route("admin.reports.export") }}?type=staff';
    };
});
</script>
@endsection
