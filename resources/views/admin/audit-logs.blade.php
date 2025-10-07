@extends('layouts.dashboard')

@section('title', 'Audit Logs')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" id="filterForm" class="row g-2">
                    <div class="col-md-2">
                        <select name="user_type" id="userTypeFilter" class="form-control form-control-sm">
                            <option value="">All Types</option>
                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>Users</option>
                            <option value="hospital" {{ request('user_type') == 'hospital' ? 'selected' : '' }}>Hospitals</option>
                            <option value="staff" {{ request('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="action_type" id="actionTypeFilter" class="form-control form-control-sm">
                            <option value="">All Actions</option>
                            <option value="GET" {{ request('action_type') == 'GET' ? 'selected' : '' }}>View</option>
                            <option value="POST" {{ request('action_type') == 'POST' ? 'selected' : '' }}>Create</option>
                            <option value="PUT" {{ request('action_type') == 'PUT' ? 'selected' : '' }}>Update</option>
                            <option value="DELETE" {{ request('action_type') == 'DELETE' ? 'selected' : '' }}>Delete</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" id="fromDateFilter" class="form-control form-control-sm" 
                               value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" id="toDateFilter" class="form-control form-control-sm" 
                               value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="search" id="searchFilter" class="form-control form-control-sm" 
                               placeholder="Search..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="clearFilters" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">Audit Logs ({{ $logs->total() }})</h6>
                <div>
                    <button id="exportBtn" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i>
                    </button>
                    <button id="clearOldLogsBtn" class="btn btn-warning btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 15%">Time</th>
                                    <th style="width: 10%">Type</th>
                                    <th style="width: 8%">ID</th>
                                    <th style="width: 8%">Method</th>
                                    <th style="width: 35%">Action</th>
                                    <th style="width: 12%">IP</th>
                                    <th style="width: 12%">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $log->created_at->format('d/m H:i') }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($log->user_type) {
                                                    'admin' => 'bg-danger',
                                                    'staff' => 'bg-warning text-dark',
                                                    'hospital' => 'bg-info',
                                                    'user' => 'bg-primary',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-sm {{ $badgeClass }}">{{ ucfirst($log->user_type) }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->user_id }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $actionParts = explode(' ', $log->action);
                                                $method = $actionParts[0] ?? '';
                                                $methodClass = match($method) {
                                                    'GET' => 'bg-info',
                                                    'POST' => 'bg-success',
                                                    'PUT' => 'bg-warning text-dark',
                                                    'DELETE' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-sm {{ $methodClass }}">{{ $method }}</span>
                                        </td>
                                        <td>
                                            <small class="text-dark">{{ $log->action }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            @if($log->old_values || $log->new_values)
                                                <button type="button" class="btn btn-xs btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#logModal{{ $log->id }}"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <!-- Log Details Modal -->
                                    @if($log->old_values || $log->new_values)
                                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Audit Log Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if($log->old_values)
                                                            <h6>Old Values:</h6>
                                                            <pre class="bg-light p-3 rounded">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                        @endif
                                                        
                                                        @if($log->new_values)
                                                            <h6>New Values:</h6>
                                                            <pre class="bg-light p-3 rounded">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                        @endif
                                                        
                                                        <h6>User Agent:</h6>
                                                        <p class="small text-muted">{{ $log->user_agent }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-2">
                        {{ $logs->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                        <h6 class="text-muted">No audit logs found</h6>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.badge-sm {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.2;
}

.table-sm td, .table-sm th {
    padding: 0.3rem;
}

.card-body {
    padding: 0.75rem;
}

.audit-log-row:hover {
    background-color: #f8f9fa;
}
</style>
@endsection

@section('scripts')
<script>
console.log('Audit logs script loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners...');
    // Filter functionality
    const filterForm = document.getElementById('filterForm');
    const userTypeFilter = document.getElementById('userTypeFilter');
    const actionTypeFilter = document.getElementById('actionTypeFilter');
    const fromDateFilter = document.getElementById('fromDateFilter');
    const toDateFilter = document.getElementById('toDateFilter');
    const searchFilter = document.getElementById('searchFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    console.log('Filter elements found:', {
        filterForm,
        userTypeFilter,
        actionTypeFilter,
        fromDateFilter,
        toDateFilter,
        searchFilter,
        clearFiltersBtn
    });
    
    // Export button functionality
    const exportBtn = document.getElementById('exportBtn');
    console.log('Export button found:', exportBtn);
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            console.log('Export button clicked');
            const params = new URLSearchParams(window.location.search);
            const exportUrl = '{{ route("admin.audit-logs.export") }}?' + params.toString();
            
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = exportUrl;
            link.download = 'audit_logs_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
    
    // Clear old logs button functionality
    const clearOldLogsBtn = document.getElementById('clearOldLogsBtn');
    console.log('Clear old logs button found:', clearOldLogsBtn);
    if (clearOldLogsBtn) {
        clearOldLogsBtn.addEventListener('click', function() {
            console.log('Clear old logs button clicked');
            if (confirm('Are you sure you want to clear logs older than 30 days? This action cannot be undone.')) {
                // Show loading state
                const button = this;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.audit-logs.clear") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Auto-submit form when filters change
    [userTypeFilter, actionTypeFilter, fromDateFilter, toDateFilter].forEach((filter, index) => {
        if (filter) {
            console.log(`Setting up change listener for filter ${index}`);
            filter.addEventListener('change', function() {
                console.log(`Filter ${index} changed, submitting form`);
                filterForm.submit();
            });
        } else {
            console.log(`Filter ${index} not found`);
        }
    });
    
    // Search with debounce
    let searchTimeout;
    if (searchFilter) {
        searchFilter.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    filterForm.submit();
                }
            }, 500);
        });
    }
    
    // Clear all filters
    console.log('Clear filters button found:', clearFiltersBtn);
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            console.log('Clear filters button clicked');
            userTypeFilter.value = '';
            actionTypeFilter.value = '';
            fromDateFilter.value = '';
            toDateFilter.value = '';
            searchFilter.value = '';
            
            // Redirect to clean URL without any parameters
            window.location.href = '{{ route("admin.audit-logs") }}';
        });
    }
    
    console.log('All event listeners set up successfully');
});
</script>
@endsection