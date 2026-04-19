<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LicenseRequest;
use App\Modules\CentralDashboard\Http\Requests\StoreLicenseRequestRequest;
use App\Modules\CentralDashboard\Http\Requests\UpdateLicenseRequestRequest;
use App\Modules\CentralDashboard\Http\Resources\LicenseRequestResource;
use App\Modules\CentralDashboard\Services\LicenseRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseRequestController extends Controller
{
    public function __construct(
        protected LicenseRequestService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $licenseRequests = $this->service->getAll($request->only([
            'search',
            'status',
            'per_page',
        ]));

        return response()->json([
            'message' => 'Client requests retrieved successfully.',
            'data' => LicenseRequestResource::collection($licenseRequests),
            'meta' => [
                'current_page' => $licenseRequests->currentPage(),
                'last_page' => $licenseRequests->lastPage(),
                'per_page' => $licenseRequests->perPage(),
                'total' => $licenseRequests->total(),
            ],
        ]);
    }

    public function store(StoreLicenseRequestRequest $request): JsonResponse
    {
        $licenseRequest = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Client request created successfully.',
            'data' => new LicenseRequestResource($licenseRequest),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $licenseRequest = $this->service->getById($id);

        return response()->json([
            'message' => 'Client request retrieved successfully.',
            'data' => new LicenseRequestResource($licenseRequest),
        ]);
    }

    public function update(UpdateLicenseRequestRequest $request, int $id): JsonResponse
    {
        $licenseRequest = $this->service->update($id, $request->validated());

        return response()->json([
            'message' => 'Client request updated successfully.',
            'data' => new LicenseRequestResource($licenseRequest),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Client request deleted successfully.',
        ]);
    }
}