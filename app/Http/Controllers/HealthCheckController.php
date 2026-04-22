<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    public function ping(): JsonResponse
    {
        try {
              // اختبار الاتصال بالداتابيز
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'ok',
                'message' => 'Master server and database are reachable',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}