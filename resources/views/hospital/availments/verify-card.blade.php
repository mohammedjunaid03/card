@extends('layouts.dashboard')

@section('title', 'Verify Health Card')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-qrcode"></i> Verify Health Card
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('hospital.patient-availments.verify-card.submit') }}">
                    @csrf
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="card_number" class="form-label">Health Card Number *</label>
                                <input type="text" 
                                       name="card_number" 
                                       id="card_number" 
                                       class="form-control form-control-lg @error('card_number') is-invalid @enderror" 
                                       placeholder="Enter health card number"
                                       value="{{ old('card_number') }}" 
                                       required 
                                       autofocus>
                                @error('card_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search"></i> Verify Card
                                </button>
                                
                                <button type="button" class="btn btn-outline-primary" onclick="alert('QR Scanner feature - Integrate with jsQR or QuaggaJS library')">
                                    <i class="fas fa-camera"></i> Scan QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <div class="mt-5">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Instructions</h5>
                        <ul class="mb-0">
                            <li>Enter the health card number manually, or</li>
                            <li>Use the QR scanner to scan the patient's health card</li>
                            <li>Once verified, you can record the patient's availment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
