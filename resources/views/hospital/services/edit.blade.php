@extends('layouts.dashboard')

@section('title', 'Edit Service')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Service</h4>
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
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.services.update', $service->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $service->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $service->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_percentage" class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="100" 
                                           class="form-control @error('discount_percentage') is-invalid @enderror" 
                                           id="discount_percentage" name="discount_percentage" 
                                           value="{{ old('discount_percentage', $service->discount_percentage) }}" required>
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="consultation" {{ old('category', $service->category) === 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="diagnostic" {{ old('category', $service->category) === 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                                <option value="treatment" {{ old('category', $service->category) === 'treatment' ? 'selected' : '' }}>Treatment</option>
                                <option value="surgery" {{ old('category', $service->category) === 'surgery' ? 'selected' : '' }}>Surgery</option>
                                <option value="emergency" {{ old('category', $service->category) === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="other" {{ old('category', $service->category) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('hospital.services.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Current Service Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Created:</strong> {{ $service->created_at->format('M d, Y') }}</p>
                    <p><strong>Last Updated:</strong> {{ $service->updated_at->format('M d, Y') }}</p>
                    <p><strong>Total Availments:</strong> {{ $service->patientAvailments()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
