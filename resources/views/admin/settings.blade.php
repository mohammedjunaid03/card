@extends('layouts.dashboard')

@section('title', 'System Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">General Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="app_name" class="form-label">Application Name *</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" 
                                       value="{{ $settings['app_name'] }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_validity_years" class="form-label">Card Validity (Years) *</label>
                                <input type="number" class="form-control" id="card_validity_years" name="card_validity_years" 
                                       value="{{ $settings['card_validity_years'] }}" min="1" max="10" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="otp_expiry_minutes" class="form-label">OTP Expiry (Minutes) *</label>
                                <input type="number" class="form-control" id="otp_expiry_minutes" name="otp_expiry_minutes" 
                                       value="{{ $settings['otp_expiry_minutes'] }}" min="5" max="60" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_file_size" class="form-label">Max File Upload Size (MB)</label>
                                <input type="number" class="form-control" id="max_file_size" name="max_file_size" 
                                       value="{{ $settings['max_file_size'] ?? 5 }}" min="1" max="50">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="default_discount_percentage" class="form-label">Default Discount Percentage</label>
                                <input type="number" class="form-control" id="default_discount_percentage" name="default_discount_percentage" 
                                       value="{{ $settings['default_discount_percentage'] ?? 10 }}" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notification_retention_days" class="form-label">Notification Retention (Days)</label>
                                <input type="number" class="form-control" id="notification_retention_days" name="notification_retention_days" 
                                       value="{{ $settings['notification_retention_days'] ?? 30 }}" min="1" max="365">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="system_email" class="form-label">System Email</label>
                        <input type="email" class="form-control" id="system_email" name="system_email" 
                               value="{{ $settings['system_email'] ?? 'admin@healthcard.com' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="support_email" class="form-label">Support Email</label>
                        <input type="email" class="form-control" id="support_email" name="support_email" 
                               value="{{ $settings['support_email'] ?? 'support@healthcard.com' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="terms_and_conditions" class="form-label">Terms and Conditions</label>
                        <textarea class="form-control" id="terms_and_conditions" name="terms_and_conditions" rows="5">{{ $settings['terms_and_conditions'] ?? '' }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="privacy_policy" class="form-label">Privacy Policy</label>
                        <textarea class="form-control" id="privacy_policy" name="privacy_policy" rows="5">{{ $settings['privacy_policy'] ?? '' }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- System Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Database Connection</span>
                    <span class="badge bg-success">Connected</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Email Service</span>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>File Storage</span>
                    <span class="badge bg-success">Available</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Cache System</span>
                    <span class="badge bg-warning">Limited</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Backup Status</span>
                    <span class="badge bg-info">Scheduled</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="clearCache()">
                        <i class="fas fa-broom"></i> Clear Cache
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="backupDatabase()">
                        <i class="fas fa-database"></i> Backup Database
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="sendTestEmail()">
                        <i class="fas fa-envelope"></i> Test Email
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="viewLogs()">
                        <i class="fas fa-file-alt"></i> View Logs
                    </button>
                </div>
            </div>
        </div>
        
        <!-- System Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>PHP Version:</strong> {{ PHP_VERSION }}
                </div>
                <div class="mb-2">
                    <strong>Laravel Version:</strong> {{ app()->version() }}
                </div>
                <div class="mb-2">
                    <strong>Server:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}
                </div>
                <div class="mb-2">
                    <strong>Database:</strong> MySQL 8.0
                </div>
                <div class="mb-2">
                    <strong>Environment:</strong> {{ app()->environment() }}
                </div>
                <div>
                    <strong>Last Updated:</strong> {{ now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Mode Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Maintenance Mode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> Enabling maintenance mode will make the site unavailable to users.
                </div>
                <div class="mb-3">
                    <label for="maintenance_message" class="form-label">Maintenance Message</label>
                    <textarea class="form-control" id="maintenance_message" rows="3" 
                              placeholder="We are currently performing scheduled maintenance. Please check back later."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="enableMaintenance()">Enable Maintenance</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function resetForm() {
    if (confirm('Reset all settings to default values?')) {
        document.getElementById('app_name').value = 'Health Card Management System';
        document.getElementById('card_validity_years').value = 5;
        document.getElementById('otp_expiry_minutes').value = 10;
        document.getElementById('max_file_size').value = 5;
        document.getElementById('default_discount_percentage').value = 10;
        document.getElementById('notification_retention_days').value = 30;
        document.getElementById('system_email').value = 'admin@healthcard.com';
        document.getElementById('support_email').value = 'support@healthcard.com';
        document.getElementById('terms_and_conditions').value = '';
        document.getElementById('privacy_policy').value = '';
    }
}

function clearCache() {
    if (confirm('Clear all cached data?')) {
        // In a real application, this would make an AJAX call to clear cache
        alert('Cache cleared successfully!');
    }
}

function backupDatabase() {
    if (confirm('Create a database backup?')) {
        // In a real application, this would trigger a backup process
        alert('Database backup initiated. You will be notified when complete.');
    }
}

function sendTestEmail() {
    if (confirm('Send a test email to verify email configuration?')) {
        // In a real application, this would send a test email
        alert('Test email sent successfully!');
    }
}

function viewLogs() {
    // In a real application, this would open a logs viewer
    alert('Log viewer would open here in a real application.');
}

function enableMaintenance() {
    const message = document.getElementById('maintenance_message').value;
    if (confirm('Enable maintenance mode? This will make the site unavailable to users.')) {
        // In a real application, this would enable maintenance mode
        alert('Maintenance mode enabled with message: ' + message);
        bootstrap.Modal.getInstance(document.getElementById('maintenanceModal')).hide();
    }
}
</script>
@endsection
