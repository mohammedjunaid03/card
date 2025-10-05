@extends('layouts.dashboard')

@section('title', 'Verify Health Card')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-qrcode"></i> Verify Health Card</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Scan the patient's QR code or enter their card number manually
                </div>
                
                <form method="POST" action="{{ route('hospital.verify-card.submit') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label">Health Card Number</label>
                        <input type="text" name="card_number" 
                               class="form-control form-control-lg @error('card_number') is-invalid @enderror" 
                               placeholder="Enter card number" 
                               required autofocus>
                        @error('card_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Verify Card
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-outline-secondary" onclick="initQRScanner()">
                        <i class="fas fa-camera"></i> Scan QR Code
                    </button>
                </div>
                
                <div id="qr-scanner" class="mt-4" style="display: none;">
                    <video id="qr-video" width="100%" style="max-width: 400px; margin: 0 auto; display: block;"></video>
                </div>
            </div>
        </div>
        
        @if(session('card_verified'))
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Card Verified Successfully</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ session('user')->name }}</h4>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted">Age</small>
                                    <p class="mb-2"><strong>{{ session('user')->age }} years</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Gender</small>
                                    <p class="mb-2"><strong>{{ session('user')->gender }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Blood Group</small>
                                    <p class="mb-2"><strong>{{ session('user')->blood_group }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Mobile</small>
                                    <p class="mb-2"><strong>{{ session('user')->mobile }}</strong></p>
                                </div>
                            </div>
                            
                            <a href="{{ route('hospital.availments.create', ['user_id' => session('user')->id]) }}" 
                               class="btn btn-success mt-3">
                                <i class="fas fa-plus"></i> Record Service Availment
                            </a>
                        </div>
                        <div class="col-md-4 text-center">
                            @if(session('user')->photo_path)
                                <img src="{{ asset('storage/' . session('user')->photo_path) }}" 
                                     class="img-thumbnail" 
                                     style="width: 150px; height: 150px; object-fit: cover;"
                                     alt="Photo">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function initQRScanner() {
    document.getElementById('qr-scanner').style.display = 'block';
    // Add QR scanner library integration here
    alert('QR Scanner feature - Integrate with jsQR or QuaggaJS library');
}
</script>
@endpush