<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Resources\LicenseLogResource;
use App\Modules\CentralDashboard\Services\LicenseLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseLogController extends Controller
{
    public function __construct(
        protected LicenseLogService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $logs = $this->service->getAll($request->only([
            'license_id',
            'action',
            'per_page',
        ]));

        return response()->json([
            'message' => 'License logs fetched successfully.',
            'data' => LicenseLogResource::collection($logs),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $log = $this->service->getById($id);

        return response()->json([
            'message' => 'License log fetched successfully.',
            'data' => new LicenseLogResource($log),
        ]);
    }
}