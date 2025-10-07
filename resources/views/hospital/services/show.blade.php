@extends('layouts.dashboard')

@section('title', 'Service Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Service Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('hospital.services.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Services
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $service->name }}</h5>
                    <span class="badge bg-{{ $service->status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($service->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Service Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $service->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>{{ ucfirst($service->category ?? 'Not specified') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Price:</strong></td>
                                    <td>₹{{ number_format($service->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td>{{ $service->discount_percentage }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Final Price:</strong></td>
                                    <td class="text-success">
                                        <strong>₹{{ number_format($service->price - ($service->price * $service->discount_percentage / 100), 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Description</h6>
                            <p>{{ $service->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('hospital.services.edit', $service->id) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit Service
                        </a>
                        <form method="POST" action="{{ route('hospital.services.destroy', $service->id) }}" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this service?')">
                                <i class="fas fa-trash"></i> Delete Service
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary">{{ $service->patientAvailments()->count() }}</h3>
                        <p class="text-muted">Total Availments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
