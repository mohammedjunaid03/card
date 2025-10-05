<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\ProfileController as UserProfile;
use App\Http\Controllers\User\HealthCardController;
use App\Http\Controllers\User\DiscountHistoryController;
use App\Http\Controllers\User\HospitalSearchController;
use App\Http\Controllers\User\NotificationController as UserNotification;
use App\Http\Controllers\User\SupportTicketController;

use App\Http\Controllers\Hospital\DashboardController as HospitalDashboard;
use App\Http\Controllers\Hospital\ProfileController as HospitalProfile;
use App\Http\Controllers\Hospital\ServiceController;
use App\Http\Controllers\Hospital\CardVerificationController;
use App\Http\Controllers\Hospital\AvailmentController;
use App\Http\Controllers\Hospital\PatientController;
use App\Http\Controllers\Hospital\ReportController as HospitalReport;
use App\Http\Controllers\Hospital\AnalyticsController;

use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Staff\UserManagementController as StaffUserManagement;
use App\Http\Controllers\Staff\HospitalManagementController as StaffHospitalManagement;
use App\Http\Controllers\Staff\DocumentVerificationController;
use App\Http\Controllers\Staff\ReportController as StaffReport;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserManagementController as AdminUserManagement;
use App\Http\Controllers\Admin\HospitalManagementController as AdminHospitalManagement;
use App\Http\Controllers\Admin\StaffManagementController;
use App\Http\Controllers\Admin\CardApprovalController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalytics;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\NotificationController as AdminNotification;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ServiceManagementController;

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

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify-otp', [RegisterController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('otp.verify.submit');

// Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| User Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'user', 'audit_log'])->prefix('user')->name('user.')->group(function () {
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
| Hospital Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:hospital', 'hospital', 'audit_log'])->prefix('hospital')->name('hospital.')->group(function () {
    Route::get('/dashboard', [HospitalDashboard::class, 'index'])->name('dashboard');
    Route::get('/profile', [HospitalProfile::class, 'show'])->name('profile');
    Route::put('/profile', [HospitalProfile::class, 'update'])->name('profile.update');

    Route::resource('services', ServiceController::class);

    Route::get('/verify-card', [CardVerificationController::class, 'showVerificationForm'])->name('verify-card');
    Route::post('/verify-card', [CardVerificationController::class, 'verify'])->name('verify-card.submit');

    Route::resource('availments', AvailmentController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');

    Route::get('/reports', [HospitalReport::class, 'index'])->name('reports');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

/*
|--------------------------------------------------------------------------
| Staff Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:staff', 'staff', 'audit_log'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');

    Route::get('/users/create', [StaffUserManagement::class, 'create'])->name('users.create');
    Route::post('/users', [StaffUserManagement::class, 'store'])->name('users.store');

    Route::get('/hospitals/create', [StaffHospitalManagement::class, 'create'])->name('hospitals.create');
    Route::post('/hospitals', [StaffHospitalManagement::class, 'store'])->name('hospitals.store');

    Route::get('/verify-documents', [DocumentVerificationController::class, 'index'])->name('verify-documents');
    Route::post('/verify-documents/{id}', [DocumentVerificationController::class, 'verify'])->name('verify-documents.verify');

    Route::get('/reports', [StaffReport::class, 'index'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Admin Module Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'admin', 'audit_log'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserManagement::class);
    Route::resource('hospitals', AdminHospitalManagement::class);
    Route::put('/hospitals/{id}/approve', [AdminHospitalManagement::class, 'approve'])->name('hospitals.approve');
    Route::put('/hospitals/{id}/reject', [AdminHospitalManagement::class, 'reject'])->name('hospitals.reject');

    Route::resource('staff', StaffManagementController::class);

    Route::get('/card-approvals', [CardApprovalController::class, 'index'])->name('card-approvals');
    Route::post('/card-approvals/{id}/approve', [CardApprovalController::class, 'approve'])->name('card-approvals.approve');

    Route::get('/analytics', [AdminAnalytics::class, 'index'])->name('analytics');

    Route::get('/reports', [AdminReport::class, 'index'])->name('reports');
    Route::get('/reports/export', [AdminReport::class, 'export'])->name('reports.export');

    Route::get('/notifications', [AdminNotification::class, 'index'])->name('notifications');
    Route::post('/notifications', [AdminNotification::class, 'send'])->name('notifications.send');

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Services Management
    Route::get('/services', [ServiceManagementController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceManagementController::class, 'store'])->name('services.store');
    Route::put('/services/{id}', [ServiceManagementController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceManagementController::class, 'destroy'])->name('services.destroy');
});
