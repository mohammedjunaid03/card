@extends('layouts.dashboard')

@section('title', 'Record Patient Availment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Record Patient Availment</h4>
                <div class="page-title-right">
                    <a href="{{ route('hospital.patient-availments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Availments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $healthCard->user->name }}</p>
                            <p><strong>Email:</strong> {{ $healthCard->user->email }}</p>
                            <p><strong>Mobile:</strong> {{ $healthCard->user->mobile }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Card Number:</strong> {{ $healthCard->card_number }}</p>
                            <p><strong>Issued Date:</strong> {{ $healthCard->issued_date->format('M d, Y') }}</p>
                            <p><strong>Expiry Date:</strong> {{ $healthCard->expiry_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Service Availment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.patient-availments.record') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $healthCard->user->id }}">
                        
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-control @error('service_id') is-invalid @enderror" 
                                    id="service_id" name="service_id" required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" 
                                            data-price="{{ $service->price }}" 
                                            data-discount="{{ $service->discount_percentage }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - ₹{{ number_format($service->price, 2) }} 
                                        ({{ $service->discount_percentage }}% discount)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">Total Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('total_amount') is-invalid @enderror" 
                                           id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required readonly>
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Discount Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('discount_amount') is-invalid @enderror" 
                                           id="discount_amount" name="discount_amount" value="{{ old('discount_amount') }}" required readonly>
                                    @error('discount_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('hospital.patient-availments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Availment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Availment Summary</h5>
                </div>
                <div class="card-body">
                    <div id="summary">
                        <p class="text-muted">Select a service to see the summary</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('service_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price) || 0;
    const discountPercentage = parseFloat(selectedOption.dataset.discount) || 0;
    
    const discountAmount = (price * discountPercentage) / 100;
    const finalAmount = price - discountAmount;
    
    document.getElementById('total_amount').value = price.toFixed(2);
    document.getElementById('discount_amount').value = discountAmount.toFixed(2);
    
    const summaryDiv = document.getElementById('summary');
    summaryDiv.innerHTML = `
        <p><strong>Service:</strong> ${selectedOption.text}</p>
        <p><strong>Original Price:</strong> ₹${price.toFixed(2)}</p>
        <p><strong>Discount (${discountPercentage}%):</strong> ₹${discountAmount.toFixed(2)}</p>
        <hr>
        <p class="text-success"><strong>Final Amount:</strong> ₹${finalAmount.toFixed(2)}</p>
    `;
});
</script>
@endpush
@endsection
