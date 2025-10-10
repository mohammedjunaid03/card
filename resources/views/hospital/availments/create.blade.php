@extends('layouts.dashboard')

@section('title', 'Record New Availment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle"></i> Record New Availment
                </h4>
            </div>
            <div class="card-body">
                @if(isset($user))
                    <!-- User found, show availment form -->
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Health Card Verified!</strong> 
                        Patient: {{ $user->name }} ({{ $user->email }})
                    </div>
                    
                    <form method="POST" action="{{ route('hospital.availments.store') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">Service *</label>
                                    <select name="service_id" id="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" 
                                                    data-discount="{{ $service->discount_percentage }}"
                                                    data-price="{{ $service->price }}">
                                                {{ $service->name }} ({{ $service->discount_percentage }}% discount)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="availment_date" class="form-label">Availment Date *</label>
                                    <input type="date" 
                                           name="availment_date" 
                                           id="availment_date" 
                                           class="form-control @error('availment_date') is-invalid @enderror" 
                                           value="{{ old('availment_date', date('Y-m-d')) }}" 
                                           required>
                                    @error('availment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="original_amount" class="form-label">Original Amount (₹) *</label>
                                    <input type="number" 
                                           name="original_amount" 
                                           id="original_amount" 
                                           class="form-control @error('original_amount') is-invalid @enderror" 
                                           value="{{ old('original_amount') }}" 
                                           step="0.01" 
                                           min="0" 
                                           required>
                                    @error('original_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_percentage" class="form-label">Discount Percentage (%) *</label>
                                    <input type="number" 
                                           name="discount_percentage" 
                                           id="discount_percentage" 
                                           class="form-control @error('discount_percentage') is-invalid @enderror" 
                                           value="{{ old('discount_percentage') }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.01" 
                                           required>
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Calculated Amounts</label>
                                    <div class="form-control-plaintext">
                                        <div><strong>Discount:</strong> ₹<span id="discount_amount">0.00</span></div>
                                        <div><strong>Final Amount:</strong> ₹<span id="final_amount">0.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hospital.availments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Availments
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Availment
                            </button>
                        </div>
                    </form>
                @else
                    <!-- No user found, show card verification form -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Verify Health Card First</strong> 
                        Please verify the patient's health card before recording an availment.
                    </div>
                    
                    <div class="text-center">
                        <a href="{{ route('hospital.patient-availments.verify-card') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-qrcode"></i> Verify Health Card
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($user))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const originalAmountInput = document.getElementById('original_amount');
    const discountPercentageInput = document.getElementById('discount_percentage');
    const discountAmountSpan = document.getElementById('discount_amount');
    const finalAmountSpan = document.getElementById('final_amount');
    
    function calculateAmounts() {
        const originalAmount = parseFloat(originalAmountInput.value) || 0;
        const discountPercentage = parseFloat(discountPercentageInput.value) || 0;
        
        const discountAmount = (originalAmount * discountPercentage) / 100;
        const finalAmount = originalAmount - discountAmount;
        
        discountAmountSpan.textContent = discountAmount.toFixed(2);
        finalAmountSpan.textContent = finalAmount.toFixed(2);
    }
    
    // Auto-fill discount percentage when service is selected
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const discount = selectedOption.getAttribute('data-discount');
            const price = selectedOption.getAttribute('data-price');
            
            discountPercentageInput.value = discount;
            originalAmountInput.value = price;
            calculateAmounts();
        }
    });
    
    // Calculate amounts when inputs change
    originalAmountInput.addEventListener('input', calculateAmounts);
    discountPercentageInput.addEventListener('input', calculateAmounts);
    
    // Initial calculation
    calculateAmounts();
});
</script>
@endif
@endsection
