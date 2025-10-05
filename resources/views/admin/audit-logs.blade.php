@extends('layouts.dashboard')

@section('title', 'Audit Logs')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <select name="user_type" class="form-control">
                            <option value="">All User Types</option>
                            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="hospital" {{ request('user_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                            <option value="staff" {{ request('user_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from_date" class="form-control" 
                               placeholder="From Date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to_date" class="form-control" 
                               placeholder="To Date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Audit Logs ({{ $logs->total() }})</h5>
            </div>
            <div class="card-body">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User Type</th>
                                    <th>User ID</th>
                                    <th>Action</th>
                                    <th>Model</th>
                                    <th>IP Address</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($log->user_type) }}</span>
                                        </td>
                                        <td>{{ $log->user_id }}</td>
                                        <td><code>{{ $log->action }}</code></td>
                                        <td>
                                            @if($log->model_type)
                                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>
                                            @if($log->old_values || $log->new_values)
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#logModal{{ $log->id }}">
                                                    <i class="fas fa-info-circle"></i> View
                                                </button>
                                            @else
                                                -
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
                    
                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No audit logs found</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection