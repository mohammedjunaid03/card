@extends('layouts.dashboard')

@section('title', 'Record Service Availment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Record Service Availment</h5>
            </div>
            <div class="card-body">
                @if(isset($user))
                    <div class="alert alert-success mb-4">
                        <h6>Patient: {{ $user->name }}</h6>
                        <p class="mb-0">Age: {{ $user->age }} | Gender: {{ $user->gender }} | Blood: {{ $user->blood_group }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('hospital.availments.store') }}">
                    @csrf
                    
                    @if(isset($user))
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                    @else
                        <div class="mb-3">
                            <label class="form-label">Search Patient</label>
                            <input type="text" class="form-control" 
                                   placeholder="Enter card number or mobile" 
                                   id="patient-search">
                            <small class="text-muted">First verify the patient's health card</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Select Service *</label>
                        <select name="service_id" class="form-control @error('service_id') is-invalid @enderror" 
                                id="service-select" required>
                            <option value="">Choose a service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->service_id }}" 
                                        data-discount="{{ $service->discount_percentage }}">
                                    {{ $service->service->name }} - {{ $service->discount_percentage }}% OFF
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Original Amount (₹) *</label>
                        <input type="number" name="original_amount" 
                               class="form-control @error('original_amount') is-invalid @enderror" 
                               id="original-amount" 
                               step="0.01" min="0" required>
                        @error('original_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Discount Percentage</small>
                                    <h5 id="discount-percent">0%</h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Discount Amount</small>
                                    <h5 class="text-success" id="discount-amount">₹0.00</h5>
                                </div>
                                <div class="col-12 mt-2">
                                    <small class="text-muted">Final Amount</small>
                                    <h4 class="text-primary" id="final-amount">₹0.00</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Availment Date *</label>
                        <input type="date" name="availment_date" 
                               class="form-control @error('availment_date') is-invalid @enderror" 
                               value="{{ date('Y-m-d') }}" required>
                        @error('availment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Record Availment
                        </button>
                        <a href="{{ route('hospital.availments') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function calculateDiscount() {
        const originalAmount = parseFloat($('#original-amount').val()) || 0;
        const discountPercent = parseFloat($('#service-select option:selected').data('discount')) || 0;
        const discountAmount = (originalAmount * discountPercent) / 100;
        const finalAmount = originalAmount - discountAmount;
        
        $('#discount-percent').text(discountPercent + '%');
        $('#discount-amount').text('₹' + discountAmount.toFixed(2));
        $('#final-amount').text('₹' + finalAmount.toFixed(2));
    }
    
    $('#service-select, #original-amount').on('change keyup', calculateDiscount);
});
</script>
@endpush
