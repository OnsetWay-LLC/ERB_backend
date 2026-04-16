<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Resources\LicenseRequestDetailResource;
use App\Modules\CentralDashboard\Http\Resources\LicenseRequestResource;
use App\Modules\CentralDashboard\Repositories\LicenseRequestRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseRequestController extends Controller
{
    public function __construct(
        protected LicenseRequestRepository $repository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $licenseRequests = $this->repository->paginateWithLatestLicense($perPage);

        return response()->json([
            'message' => 'License requests fetched successfully.',
            'data'    => LicenseRequestResource::collection($licenseRequests),
            'meta'    => [
                'current_page' => $licenseRequests->currentPage(),
                'last_page'    => $licenseRequests->lastPage(),
                'per_page'     => $licenseRequests->perPage(),
                'total'        => $licenseRequests->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $licenseRequest = $this->repository->findById($id);

        return response()->json([
            'message' => 'License request details fetched successfully.',
            'data'    => new LicenseRequestDetailResource($licenseRequest),
        ]);
    }
}