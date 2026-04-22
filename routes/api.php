<?php
use Illuminate\Support\Facades\Route;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminController;
use App\Http\Controllers\HealthCheckController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Backend is running',
    ]);
});
Route::post('/central-dashboard/setup/first-admin', [ProjectAdminController::class, 'storeFirstAdmin']);

Route::get('/ping', [HealthCheckController::class, 'ping']);
 
//هيك الclient يختبر اذا الmaster server شغال او لا