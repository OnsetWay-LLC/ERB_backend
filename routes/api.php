<?php
use Illuminate\Support\Facades\Route;
use App\Modules\CentralDashboard\Http\Controllers\ProjectAdminController;


Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Backend is running',
    ]);
});
Route::post('/central-dashboard/setup/first-admin', [ProjectAdminController::class, 'storeFirstAdmin']);