@extends('layouts.dashboard')

@section('title', 'Hospital Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Hospital Services</h4>
                <div class="page-title-right">
                    <a href="{{ route('hospital.services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Service
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Discount %</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ Str::limit($service->description, 50) }}</td>
                                            <td>₹{{ number_format($service->price, 2) }}</td>
                                            <td>{{ $service->discount_percentage }}%</td>
                                            <td>
                                                <span class="badge bg-{{ $service->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($service->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('hospital.services.show', $service->id) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('hospital.services.edit', $service->id) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('hospital.services.destroy', $service->id) }}" 
                                                          style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this service?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $services->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Services Found</h5>
                            <p class="text-muted">You haven't added any services yet.</p>
                            <a href="{{ route('hospital.services.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Your First Service
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
