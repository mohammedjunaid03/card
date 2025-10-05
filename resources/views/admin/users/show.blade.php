@extends('layouts.dashboard')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->photo_path)
                    <img src="{{ asset('storage/' . $user->photo_path) }}" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;"
                         alt="Profile Photo">
                @else
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px; font-size: 3rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <span class="badge bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Actions</h6>
            </div>
            <div class="list-group list-group-flush">
                @if($user->status == 'active')
                    <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="blocked">
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-ban"></i> Block User
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="list-group-item list-group-item-action text-success">
                            <i class="fas fa-check"></i> Activate User
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Full Name</label>
                        <p class="mb-0"><strong>{{ $user->name }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Date of Birth</label>
                        <p class="mb-0"><strong>{{ $user->date_of_birth->format('d M Y') }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Age</label>
                        <p class="mb-0"><strong>{{ $user->age }} years</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Gender</label>
                        <p class="mb-0"><strong>{{ $user->gender }}</strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Blood Group</label>
                        <p class="mb-0">
                            <span class="badge bg-danger fs-6">{{ $user->blood_group }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted">Mobile</label>
                        <p class="mb-0"><strong>{{ $user->mobile }}</strong></p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted">Address</label>
                        <p class="mb-0"><strong>{{ $user->address }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($user->healthCard)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Health Card Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-2">
                                <label class="text-muted">Card Number</label>
                                <p class="mb-0"><strong class="fs-5">{{ $user->healthCard->card_number }}</strong></p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted">Issued Date</label>
                                <p class="mb-0">{{ $user->healthCard->issued_date->format('d M Y') }}</p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted">Expiry Date</label>
                                <p class="mb-0">{{ $user->healthCard->expiry_date->format('d M Y') }}</p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $user->healthCard->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->healthCard->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . $user->healthCard->qr_code_path) }}" 
                                 class="img-fluid" 
                                 style="max-width: 150px;"
                                 alt="QR Code">
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Service Availment History</h5>
            </div>
            <div class="card-body">
                @if($user->availments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Hospital</th>
                                    <th>Service</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->availments as $availment)
                                    <tr>
                                        <td>{{ $availment->availment_date->format('d M Y') }}</td>
                                        <td>{{ $availment->hospital->name }}</td>
                                        <td>{{ $availment->service->name }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $availment->discount_percentage }}%</span><br>
                                            <small class="text-success">₹{{ number_format($availment->discount_amount, 2) }}</small>
                                        </td>
                                        <td><strong>₹{{ number_format($availment->final_amount, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3">Total Savings</th>
                                    <th colspan="2" class="text-success">
                                        ₹{{ number_format($user->availments->sum('discount_amount'), 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">No service availments yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection