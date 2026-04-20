<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use App\Modules\CentralDashboard\Http\Controllers\LicenseController;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminController;
use App\Modules\CentralDashboard\Http\Controllers\LicenseRequestController;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminAuthController;
use App\Modules\CentralDashboard\Http\Controllers\ClientInstallationController;
use App\Modules\CentralDashboard\Http\Controllers\LicenseLogController;
use App\Http\Middleware\EnsureProjectAdminCanAccessDashboard;
use App\Http\Middleware\EnsureCanManageProjectAdmins;

// Schedule
Schedule::command('licenses:send-renewal-reminders')->dailyAt('10:00');

// Auth routes
Route::prefix('central-dashboard/auth')->group(function () {
    Route::post('/login', [ProjectAdminAuthController::class, 'login']);

    Route::middleware(['auth:project_admin'])->group(function () {
        Route::get('/me', [ProjectAdminAuthController::class, 'me']);
        Route::post('/logout', [ProjectAdminAuthController::class, 'logout']);
    });
});

// Public routes
Route::prefix('central-dashboard')->group(function () {
    Route::post('/licenses/verify-token', [LicenseController::class, 'verifyToken']);
});

// Protected routes
Route::prefix('central-dashboard')->middleware(['auth:project_admin'])->group(function () {

   Route::prefix('/license-requests')->group(function () { // License Requests
        Route::get('/', [LicenseRequestController::class, 'index']);
        Route::post('/store', [LicenseRequestController::class, 'store']);
        Route::get('/{id}', [LicenseRequestController::class, 'show']);
        Route::put('/{id}', [LicenseRequestController::class, 'update']);
        Route::delete('/{id}', [LicenseRequestController::class, 'destroy']);
    });

    // Licenses
    Route::post('/licenses/generate', [LicenseController::class, 'generate']);
    Route::get('/licenses', [LicenseController::class, 'index']);
    Route::get('/licenses/{id}', [LicenseController::class, 'show']);
    Route::post('/licenses/send-token', [LicenseController::class, 'sendToken']);
    Route::post('/licenses/generate-renewal', [LicenseController::class, 'generateRenewal']);
    // License Logs
    Route::get('/license-logs', [LicenseLogController::class, 'index']);
    Route::get('/license-logs/{id}', [LicenseLogController::class, 'show']);

   // Project Admins
Route::middleware('project_admin.manage')->group(function () {
    // Project Admins
    Route::get('/admins', [ProjectAdminController::class, 'index']);
    Route::post('/admins/store', [ProjectAdminController::class, 'store']);
    Route::get('/admins/{id}', [ProjectAdminController::class, 'show']);
    Route::put('/admins/{id}', [ProjectAdminController::class, 'update']);
    Route::delete('/admins/{id}', [ProjectAdminController::class, 'destroy']);
});
    // Client Installations
    Route::get('/client-installations', [ClientInstallationController::class, 'index']);
    Route::post('/client-installations', [ClientInstallationController::class, 'store']);
    Route::get('/client-installations/{id}', [ClientInstallationController::class, 'show']);
    Route::put('/client-installations/{id}', [ClientInstallationController::class, 'update']);

    Route::prefix('/client-requests')->group(function () {
    Route::get('/', [LicenseRequestController::class, 'index']);
    Route::post('/store', [LicenseRequestController::class, 'store']);
    Route::get('/{id}', [LicenseRequestController::class, 'show']);
    Route::put('/{id}', [LicenseRequestController::class, 'update']);
    Route::delete('/{id}', [LicenseRequestController::class, 'destroy']);
});
});
