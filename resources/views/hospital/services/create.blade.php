@extends('layouts.dashboard')

@section('title', 'Add New Service')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Add New Service</h4>
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
                    <form method="POST" action="{{ route('hospital.services.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required>
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
                                           id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" required>
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
                                <option value="consultation" {{ old('category') === 'consultation' ? 'selected' : '' }}>Consultation</option>
                                <option value="diagnostic" {{ old('category') === 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                                <option value="treatment" {{ old('category') === 'treatment' ? 'selected' : '' }}>Treatment</option>
                                <option value="surgery" {{ old('category') === 'surgery' ? 'selected' : '' }}>Surgery</option>
                                <option value="emergency" {{ old('category') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('hospital.services.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Provide clear service descriptions</li>
                        <li><i class="fas fa-check text-success me-2"></i> Set competitive pricing</li>
                        <li><i class="fas fa-check text-success me-2"></i> Offer reasonable discounts</li>
                        <li><i class="fas fa-check text-success me-2"></i> Categorize services properly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
