<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\StatsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\ProfileController as UserProfile;
use App\Http\Controllers\User\HealthCardController;
use App\Http\Controllers\User\DiscountHistoryController;
use App\Http\Controllers\User\HospitalSearchController;
use App\Http\Controllers\User\NotificationController as UserNotification;
use App\Http\Controllers\User\SupportTicketController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Staff\UserManagementController as StaffUserManagement;
use App\Http\Controllers\Staff\HospitalManagementController as StaffHospitalManagement;
use App\Http\Controllers\Staff\DocumentVerificationController;
use App\Http\Controllers\Staff\ReportController as StaffReport;
use App\Http\Controllers\Staff\HealthCardApprovalController as StaffHealthCardApproval;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\AdminUserManagement;
use App\Http\Controllers\Admin\AdminHospitalManagement;
use App\Http\Controllers\Admin\StaffManagementController;
use App\Http\Controllers\Admin\CardApprovalController;
use App\Http\Controllers\Admin\AdminAnalytics;
use App\Http\Controllers\Admin\AdminReport;
use App\Http\Controllers\Admin\AdminNotification;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ServiceManagementController;


use App\Http\Controllers\Hospital\DashboardController as HospitalDashboard;
use App\Http\Controllers\Hospital\ProfileController as HospitalProfile;
use App\Http\Controllers\Hospital\ServiceController;
use App\Http\Controllers\Hospital\CardVerificationController;
use App\Http\Controllers\Hospital\PatientAvailmentController;
use App\Http\Controllers\Hospital\PatientController;
use App\Http\Controllers\Hospital\HospitalReport;
use App\Http\Controllers\Hospital\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/how-it-works', [HomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/hospital-network', [HomeController::class, 'hospitalNetwork'])->name('hospital-network');
Route::get('/faqs', [HomeController::class, 'faqs'])->name('faqs');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Partner Pages
Route::get('/register-hospital', [HomeController::class, 'registerHospital'])->name('register-hospital');
Route::get('/partner-benefits', [HomeController::class, 'partnerBenefits'])->name('partner-benefits');
Route::get('/terms-conditions', [HomeController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');

// Hospital Details (moved to be more specific)
Route::get('/hospital-details/{id}', [HomeController::class, 'hospitalDetails'])->name('hospital.details');

// API Routes for public stats
Route::get('/api/stats', [StatsController::class, 'index']);


// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout routes for all guards
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/user/logout', [LoginController::class, 'logout'])->name('user.logout');
Route::post('/hospital/logout', [LoginController::class, 'logout'])->name('hospital.logout');
Route::post('/staff/logout', [LoginController::class, 'logout'])->name('staff.logout');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Keep-alive route for session refresh
Route::get('/keep-alive', function() {
    request()->session()->regenerateToken();
    return response('', 200);
})->name('keep-alive');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify-otp', [RegisterController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/resend-registration-otp', [RegisterController::class, 'resendOtp'])->name('otp.resend');

// Password Reset
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/forgot-password/otp', [PasswordResetController::class, 'sendOtp'])->name('password.otp');
Route::get('/verify-password-otp', [PasswordResetController::class, 'showVerifyOtpForm'])->name('password.verify-otp');
Route::post('/verify-password-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verify-otp.submit');
Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resend-otp');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| User Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'audit_log'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserProfile::class, 'show'])->name('profile');
    Route::put('/profile', [UserProfile::class, 'update'])->name('profile.update');

    Route::get('/health-card', [HealthCardController::class, 'show'])->name('health-card');
    Route::get('/health-card/download', [HealthCardController::class, 'download'])->name('health-card.download');

    Route::get('/discount-history', [DiscountHistoryController::class, 'index'])->name('discount-history');
    Route::get('/hospitals', [HospitalSearchController::class, 'index'])->name('hospitals');
    Route::get('/hospitals/{id}', [HospitalSearchController::class, 'show'])->name('hospitals.show');

    Route::get('/notifications', [UserNotification::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [UserNotification::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [UserNotification::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::resource('support-tickets', SupportTicketController::class);
});

/*
|--------------------------------------------------------------------------
| Staff Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:staff', 'audit_log'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');

    // Health Card Approval Routes
    Route::get('/health-cards/pending', [StaffHealthCardApproval::class, 'index'])->name('health-cards.pending');
    Route::get('/health-cards/{healthCard}', [StaffHealthCardApproval::class, 'show'])->name('health-cards.show');
    Route::post('/health-cards/{healthCard}/approve', [StaffHealthCardApproval::class, 'approve'])->name('health-cards.approve');
    Route::post('/health-cards/{healthCard}/reject', [StaffHealthCardApproval::class, 'reject'])->name('health-cards.reject');
    Route::get('/health-cards', [StaffHealthCardApproval::class, 'all'])->name('health-cards.all');
    Route::get('/health-cards/search', [StaffHealthCardApproval::class, 'search'])->name('health-cards.search');
    Route::get('/health-cards/verify', [StaffHealthCardApproval::class, 'verify'])->name('health-cards.verify');

    Route::get('/users', [StaffUserManagement::class, 'index'])->name('users.index');
    Route::get('/users/create', [StaffUserManagement::class, 'create'])->name('users.create');
    Route::post('/users', [StaffUserManagement::class, 'store'])->name('users.store');
    Route::post('/users/{user}/generate-card', [StaffUserManagement::class, 'generateHealthCard'])->name('users.generate-card');

    Route::get('/hospitals/create', [StaffHospitalManagement::class, 'create'])->name('hospitals.create');
    Route::post('/hospitals', [StaffHospitalManagement::class, 'store'])->name('hospitals.store');

    Route::get('/verify-documents', [DocumentVerificationController::class, 'index'])->name('verify-documents');
    Route::post('/verify-documents/{id}', [DocumentVerificationController::class, 'verify'])->name('verify-documents.verify');

    Route::get('/reports', [StaffReport::class, 'index'])->name('reports');
    
    // Health Card Routes
    Route::get('/health-card/{user}/download', [StaffUserManagement::class, 'downloadHealthCard'])->name('health-card.download');
    Route::get('/health-card/{user}/print', [StaffUserManagement::class, 'printHealthCard'])->name('health-card.print');
});

/*
|--------------------------------------------------------------------------
| Admin Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'audit_log'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Health Card Approval Routes
    Route::get('/health-cards/pending', [StaffHealthCardApproval::class, 'index'])->name('health-cards.pending');
    Route::get('/health-cards/{healthCard}', [StaffHealthCardApproval::class, 'show'])->name('health-cards.show');
    Route::post('/health-cards/{healthCard}/approve', [StaffHealthCardApproval::class, 'approve'])->name('health-cards.approve');
    Route::post('/health-cards/{healthCard}/reject', [StaffHealthCardApproval::class, 'reject'])->name('health-cards.reject');
    Route::get('/health-cards', [StaffHealthCardApproval::class, 'all'])->name('health-cards.all');
    Route::get('/health-cards/search', [StaffHealthCardApproval::class, 'search'])->name('health-cards.search');
    Route::get('/health-cards/verify', [StaffHealthCardApproval::class, 'verify'])->name('health-cards.verify');

    Route::resource('users', AdminUserManagement::class);
    Route::put('/users/{id}/status', [AdminUserManagement::class, 'updateStatus'])->name('users.status');
    Route::post('/users/{id}/issue-card', [AdminUserManagement::class, 'issueHealthCard'])->name('users.issue-card');
    Route::post('/users/bulk-issue-cards', [AdminUserManagement::class, 'bulkIssueHealthCards'])->name('users.bulk-issue-cards');
    Route::post('/users/bulk-status', [AdminUserManagement::class, 'bulkUpdateStatus'])->name('users.bulk-status');
    
    Route::resource('hospitals', AdminHospitalManagement::class);
    Route::put('/hospitals/{id}/approve', [AdminHospitalManagement::class, 'approve'])->name('hospitals.approve');
    Route::put('/hospitals/{id}/reject', [AdminHospitalManagement::class, 'reject'])->name('hospitals.reject');
    
    // Contract Management Routes
    Route::get('/hospitals/{id}/contract', [AdminHospitalManagement::class, 'showContract'])->name('hospitals.contract');
    Route::post('/hospitals/{id}/contract', [AdminHospitalManagement::class, 'storeContract'])->name('hospitals.contract.store');
    Route::put('/hospitals/{id}/contract', [AdminHospitalManagement::class, 'updateContract'])->name('hospitals.contract.update');
    Route::post('/hospitals/{id}/contract/renew', [AdminHospitalManagement::class, 'renewContract'])->name('hospitals.contract.renew');
    Route::post('/hospitals/{id}/contract/terminate', [AdminHospitalManagement::class, 'terminateContract'])->name('hospitals.contract.terminate');

    Route::resource('staff', StaffManagementController::class);
    Route::put('/staff/{id}/status', [StaffManagementController::class, 'updateStatus'])->name('staff.status');
    Route::post('/staff/bulk-status', [StaffManagementController::class, 'bulkUpdateStatus'])->name('staff.bulk-status');

    Route::get('/card-approvals', [CardApprovalController::class, 'index'])->name('card-approvals');
    Route::post('/card-approvals/{id}/approve', [CardApprovalController::class, 'approve'])->name('card-approvals.approve');
    Route::post('/card-approvals/approve-all', [CardApprovalController::class, 'approveAll'])->name('card-approvals.approve-all');
    Route::put('/card-approvals/{id}/reject', [CardApprovalController::class, 'reject'])->name('card-approvals.reject');

    Route::get('/analytics', [AdminAnalytics::class, 'index'])->name('analytics');

    Route::get('/reports', [AdminReport::class, 'index'])->name('reports');
    Route::get('/reports/export', [AdminReport::class, 'export'])->name('reports.export');

    Route::get('/notifications', [AdminNotification::class, 'index'])->name('notifications');
    Route::post('/notifications/send', [AdminNotification::class, 'send'])->name('notifications.send');
    Route::post('/notifications/{id}/mark-read', [AdminNotification::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [AdminNotification::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [AdminNotification::class, 'destroy'])->name('notifications.destroy');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
    Route::post('/audit-logs/clear', [AuditLogController::class, 'clear'])->name('audit-logs.clear');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Services Management
    Route::get('/services', [ServiceManagementController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceManagementController::class, 'store'])->name('services.store');
    Route::put('/services/{id}', [ServiceManagementController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceManagementController::class, 'destroy'])->name('services.destroy');
});

/*
|--------------------------------------------------------------------------
| Hospital Module Routes - CORRECTED
|--------------------------------------------------------------------------
*/
// Hospital Routes (no authentication required)
Route::prefix('hospital')->name('hospital.')->group(function () {
    // Login routes
    Route::get('/login', function() {
        return redirect()->route('login', ['role' => 'hospital']);
    })->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    
    // Dashboard
    Route::get('/dashboard', [HospitalDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [HospitalProfile::class, 'show'])->name('profile');
    Route::put('/profile', [HospitalProfile::class, 'update'])->name('profile.update');

    // Services
    Route::resource('services', ServiceController::class);

    // Card Verification
    Route::get('/verify-card', [CardVerificationController::class, 'showVerificationForm'])->name('verify-card');
    Route::post('/verify-card', [CardVerificationController::class, 'verify'])->name('verify-card.submit');

    // Patient Availments
    Route::get('/patient-availments/verify-card', [PatientAvailmentController::class, 'verifyCard'])->name('patient-availments.verify-card');
    Route::post('/patient-availments/verify-card', [PatientAvailmentController::class, 'verifyCard'])->name('patient-availments.verify-card.submit');
    Route::post('/patient-availments/record', [PatientAvailmentController::class, 'recordAvailment'])->name('patient-availments.record');
    Route::resource('availments', PatientAvailmentController::class)->except(['edit', 'update', 'destroy']);

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');

    // Reports & Analytics
    Route::get('/reports', [HospitalReport::class, 'index'])->name('reports');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Logout
    Route::post('/logout', [LoginController::class, 'hospitalLogout'])->name('logout');
});