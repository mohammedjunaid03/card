
@extends('layouts.dashboard')

@section('title', 'Verify Health Card')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Verify Health Card</h4>
                <div class="page-title-right">
                    <a href="{{ route('hospital.patient-availments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Availments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Enter Health Card Number</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hospital.patient-availments.verify-card') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Health Card Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                   id="card_number" name="card_number" value="{{ old('card_number') }}" 
                                   placeholder="Enter health card number" required>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Verify Card
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
