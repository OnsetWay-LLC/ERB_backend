<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CentralDashboard\Http\Controllers\LicenseRequestController;
use App\Modules\CentralDashboard\Http\Controllers\LicenseController;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminAuthController;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminController;
use App\Modules\CentralDashboard\Http\Controllers\ClientInstallationController;
use Illuminate\Support\Facades\Schedule;

Schedule::command('licenses:send-renewal-reminders')->dailyAt('10:00'); // Schedule the command to run daily at 10 AM

Route::prefix('central-dashboard/auth')->group(function () {
    Route::post('/login', [ProjectAdminAuthController::class, 'login']);

    Route::middleware(['auth:project_admin'])->group(function () {
        Route::get('/me', [ProjectAdminAuthController::class, 'me']);
        Route::post('/logout', [ProjectAdminAuthController::class, 'logout']);
    });
});

Route::prefix('central-dashboard')
    ->middleware(['auth:project_admin'])
    ->group(function () {
        Route::get('/license-requests', [LicenseRequestController::class, 'index']);
        Route::get('/license-requests/{id}', [LicenseRequestController::class, 'show']);

        Route::post('/licenses/generate', [LicenseController::class, 'generate']);
        Route::get('/licenses', [LicenseController::class, 'index']);
        Route::get('/licenses/{id}', [LicenseController::class, 'show']);
        Route::post('/licenses/send-token', [LicenseController::class, 'sendToken']);
        Route::get('/admins', [ProjectAdminController::class, 'index']);
        Route::post('/admins/store', [ProjectAdminController::class, 'store']);
        Route::get('/admins/{id}', [ProjectAdminController::class, 'show']);
        Route::put('/admins/{id}', [ProjectAdminController::class, 'update']);
        Route::delete('/admins/{id}', [ProjectAdminController::class, 'destroy']);
        Route::get('/client-installations', [ClientInstallationController::class, 'index']);
        Route::post('/client-installations', [ClientInstallationController::class, 'store']);
        Route::get('/client-installations/{id}', [ClientInstallationController::class, 'show']);
        Route::put('/client-installations/{id}', [ClientInstallationController::class, 'update']);
        Route::post('/licenses/generate-renewal', [LicenseController::class, 'generateRenewal']);//تجديد الاشتراك
    });

    Route::prefix('central-dashboard')->group(function () {
    Route::post('/licenses/verify-token', [LicenseController::class, 'verifyToken']);


});

