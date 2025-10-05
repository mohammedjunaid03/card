@extends('layouts.dashboard')

@section('title', 'Register New User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Register New User (In-Person)</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Use this form to register users who visit in-person
                </div>
                
                <form method="POST" action="{{ route('staff.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Same form fields as regular user registration -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth *</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   value="{{ old('date_of_birth') }}" required id="dob">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Age *</label>
                            <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" 
                                   value="{{ old('age') }}" required id="age">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender *</label>
                            <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Blood Group *</label>
                            <select name="blood_group" class="form-control @error('blood_group') is-invalid @enderror" required>
                                <option value="">Select Blood Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}">{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Address *</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                      rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                   value="{{ old('mobile') }}" required maxlength="10">
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Card *</label>
                            <input type="file" name="aadhaar" class="form-control @error('aadhaar') is-invalid @enderror" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('aadhaar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Photo (Optional)</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" 
                                   accept=".jpg,.jpeg,.png">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Register User
                        </button>
                        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary btn-lg">Cancel</a>
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
    $('#dob').on('change', function() {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        
        $('#age').val(age);
    });
});
</script>
@endpush                    <i class="fas fa-users text-primary"></i> Manage Users
                </a>
                <a href="{{ route('admin.hospitals.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-hospital text-success"></i> Manage Hospitals
                </a>
                <a href="{{ route('admin.staff.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-tie text-info"></i> Manage Staff
                </a>
                <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-bar text-warning"></i> Generate Reports
                </a>
                <a href="{{ route('admin.notifications') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-bell text-danger"></i> Send Notifications
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($trends['users']->pluck('month')) !!},
        datasets: [{
            label: 'Users',
            data: {!! json_encode($trends['users']->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Cards',
            data: {!! json_encode($trends['cards']->pluck('count')) !!},
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Pending', 'Blocked'],
        datasets: [{
            data: [{{ $stats['active_users'] }}, {{ $stats['total_users'] - $stats['active_users'] }}, 0],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endpush

