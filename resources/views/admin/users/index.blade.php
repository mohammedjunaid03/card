@extends('layouts.dashboard')

@section('title', 'User Management')

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
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="blood_group" id="bloodGroupFilter" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="card_status" id="cardStatusFilter" class="form-control">
                            <option value="">All Card Status</option>
                            <option value="issued" {{ request('card_status') == 'issued' ? 'selected' : '' }}>Card Issued</option>
                            <option value="not_issued" {{ request('card_status') == 'not_issued' ? 'selected' : '' }}>No Card</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" id="searchBtn" class="btn btn-primary w-100" title="Apply Filters">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary w-100" title="Clear All Filters">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users ({{ $users->total() }})</h5>
                <button id="exportUsersBtn" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export
                </button>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="bulkIssueCards" disabled>
                                    <i class="fas fa-id-card"></i> Issue Health Cards
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="bulkActivate" disabled>
                                    <i class="fas fa-check"></i> Activate Selected
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="bulkDeactivate" disabled>
                                    <i class="fas fa-ban"></i> Deactivate Selected
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted" id="selectedCount">0 users selected</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>User Details</th>
                                    <th>Contact</th>
                                    <th>Age/Gender/Blood</th>
                                    <th>Card Status</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->photo_path)
                                                    <img src="{{ asset('storage/' . $user->photo_path) }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;"
                                                         alt="Photo">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->email }}<br>
                                            <small class="text-muted">{{ $user->mobile }}</small>
                                        </td>
                                        <td>
                                            {{ $user->age }} / {{ $user->gender }}<br>
                                            <span class="badge bg-danger">{{ $user->blood_group }}</span>
                                        </td>
                                        <td>
                                            @if($user->healthCard)
                                                <span class="badge bg-success">Issued</span><br>
                                                <small>{{ $user->healthCard->card_number }}</small>
                                            @else
                                                <span class="badge bg-warning">Not Issued</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($user->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Blocked</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-warning dropdown-toggle" 
                                                        data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($user->status == 'active')
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="blocked">
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-ban"></i> Block User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="active">
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check"></i> Activate User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    @if(!$user->healthCard)
                                                        <li>
                                                            <form method="POST" action="{{ route('admin.users.issue-card', $user->id) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-primary" 
                                                                        onclick="return confirm('Are you sure you want to issue a health card for this user?')">
                                                                    <i class="fas fa-id-card"></i> Issue Health Card
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ route('admin.users.show', $user->id) }}" class="dropdown-item text-info">
                                                                <i class="fas fa-eye"></i> View Health Card
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" 
                                                              onsubmit="return confirm('Delete this user?')">
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
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
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
    console.log('User Management script loading...');
    
    const selectAllCheckbox = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const bulkButtons = document.querySelectorAll('#bulkIssueCards, #bulkActivate, #bulkDeactivate');
    const selectedCount = document.getElementById('selectedCount');
    
    console.log('User Management elements found:', {
        selectAllCheckbox,
        userCheckboxes: userCheckboxes.length,
        bulkButtons: bulkButtons.length,
        selectedCount
    });
    
    // Filter functionality
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const bloodGroupFilter = document.getElementById('bloodGroupFilter');
    const cardStatusFilter = document.getElementById('cardStatusFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const searchBtn = document.getElementById('searchBtn');
    
    console.log('Filter elements found:', {
        filterForm,
        searchInput,
        statusFilter,
        bloodGroupFilter,
        cardStatusFilter,
        clearFiltersBtn,
        searchBtn
    });
    
    // Auto-submit form when filters change (with debounce for search)
    let searchTimeout;
    if (searchInput) {
        console.log('Setting up search input listener');
        searchInput.addEventListener('input', function() {
            console.log('Search input changed:', this.value);
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    console.log('Submitting form due to search change');
                    filterForm.submit();
                }
            }, 500);
        });
    } else {
        console.log('Search input not found');
    }
    
    // Auto-submit when dropdowns change
    [statusFilter, bloodGroupFilter, cardStatusFilter].forEach((filter, index) => {
        if (filter) {
            console.log(`Setting up dropdown listener for filter ${index}`);
            filter.addEventListener('change', function() {
                console.log(`Filter ${index} changed, submitting form`);
                filterForm.submit();
            });
        } else {
            console.log(`Filter ${index} not found`);
        }
    });
    
    // Clear all filters
    if (clearFiltersBtn) {
        console.log('Setting up clear filters button listener');
        clearFiltersBtn.addEventListener('click', function() {
            console.log('Clear filters button clicked');
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (bloodGroupFilter) bloodGroupFilter.value = '';
            if (cardStatusFilter) cardStatusFilter.value = '';
            
            // Redirect to clean URL without any parameters
            window.location.href = '{{ route("admin.users.index") }}';
        });
    } else {
        console.log('Clear filters button not found');
    }
    
    // Search button functionality
    if (searchBtn) {
        console.log('Setting up search button listener');
        searchBtn.addEventListener('click', function(e) {
            console.log('Search button clicked');
            e.preventDefault(); // Prevent default form submission
            filterForm.submit();
        });
    } else {
        console.log('Search button not found');
    }

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    // Individual checkbox change
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
        });
    });

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = `${count} user${count !== 1 ? 's' : ''} selected`;
        
        bulkButtons.forEach(button => {
            button.disabled = count === 0;
        });

        // Update select all checkbox state
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === userCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Bulk Issue Health Cards
    document.getElementById('bulkIssueCards').addEventListener('click', function() {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedUsers.length === 0) {
            alert('Please select users to issue health cards.');
            return;
        }

        if (confirm(`Are you sure you want to issue health cards for ${selectedUsers.length} user(s)?`)) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.users.bulk-issue-cards") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            selectedUsers.forEach(userId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = userId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });

    // Bulk Activate Users
    document.getElementById('bulkActivate').addEventListener('click', function() {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedUsers.length === 0) {
            alert('Please select users to activate.');
            return;
        }

        if (confirm(`Are you sure you want to activate ${selectedUsers.length} user(s)?`)) {
            bulkUpdateStatus(selectedUsers, 'active');
        }
    });

    // Bulk Deactivate Users
    document.getElementById('bulkDeactivate').addEventListener('click', function() {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
            .map(cb => cb.value);
        
        if (selectedUsers.length === 0) {
            alert('Please select users to deactivate.');
            return;
        }

        if (confirm(`Are you sure you want to deactivate ${selectedUsers.length} user(s)?`)) {
            bulkUpdateStatus(selectedUsers, 'inactive');
        }
    });

    function bulkUpdateStatus(userIds, status) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.users.bulk-status") }}';
        
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

        userIds.forEach(userId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = userId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Export button functionality
    const exportUsersBtn = document.getElementById('exportUsersBtn');
    console.log('Export button found:', exportUsersBtn);
    if (exportUsersBtn) {
        exportUsersBtn.addEventListener('click', function() {
            console.log('Export button clicked');
            window.location.href = '{{ route("admin.reports.export") }}?type=users';
        });
    }
    
    console.log('All User Management event listeners set up successfully');
});
</script>
@endsection
