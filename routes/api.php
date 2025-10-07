<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\HealthCardController;
use App\Http\Controllers\API\V1\HospitalController;
use App\Http\Controllers\API\V1\ServiceController;
use App\Http\Controllers\API\V1\AvailmentController;
use App\Http\Controllers\API\V1\AnalyticsController;
use App\Http\Controllers\Public\HomeController;

// ---------------------------
// API V1 Routes
// ---------------------------
Route::prefix('v1')->group(function () {

    // ---------------------------
    // Public Authentication Routes
    // ---------------------------
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

    // ---------------------------
    // Signed Public Download (optional)
    // ---------------------------
    Route::get('/health-card/download/{card_number}', [HealthCardController::class, 'download'])
        ->name('api.healthcard.download')
        ->middleware('signed');


    // ---------------------------
    // Protected Routes (Authenticated)
    // ---------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // --- Authentication ---
        Route::post('/logout', [AuthController::class, 'logout']);

        // --- User Profile ---
        Route::get('/user', [UserController::class, 'profile']);
        Route::put('/user', [UserController::class, 'updateProfile']);

        // --- Health Card ---
        Route::get('/health-card', [HealthCardController::class, 'show']);

        // --- Hospitals ---
        Route::get('/hospitals', [HospitalController::class, 'index']);
        Route::get('/hospitals/{id}', [HospitalController::class, 'show']);

        // --- Services ---
        Route::get('/services', [ServiceController::class, 'index']);

        // --- Availments ---
        Route::get('/availments', [AvailmentController::class, 'index']);

        // --- Analytics ---
        Route::get('/analytics', [AnalyticsController::class, 'userStats']);
    });
});
