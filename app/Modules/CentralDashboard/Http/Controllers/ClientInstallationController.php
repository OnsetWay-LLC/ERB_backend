<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\CentralDashboard\Services\ClientInstallationService;
use App\Modules\CentralDashboard\Http\Resources\ClientInstallationResource;
use App\Modules\CentralDashboard\Http\Requests\StoreClientInstallationRequest;
use App\Modules\CentralDashboard\Http\Requests\UpdateClientInstallationRequest;

class ClientInstallationController extends Controller
{
    public function __construct(
        protected ClientInstallationService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $installations = $this->service->getAll($request->only([
            'search',
            'device_type',
            'installation_status',
            'license_request_id',
            'per_page',
        ]));

        return response()->json([
            'message' => 'Client installations retrieved successfully.',
            'data' => ClientInstallationResource::collection($installations),
            'meta' => [
                'current_page' => $installations->currentPage(),
                'last_page' => $installations->lastPage(),
                'per_page' => $installations->perPage(),
                'total' => $installations->total(),
            ],
        ]);
    }

    public function store(StoreClientInstallationRequest $request): JsonResponse
    {
        $installation = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Client installation created successfully.',
            'data' => new ClientInstallationResource($installation),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $installation = $this->service->getById($id);

        return response()->json([
            'message' => 'Client installation retrieved successfully.',
            'data' => new ClientInstallationResource($installation),
        ]);
    }

    public function update(UpdateClientInstallationRequest $request, int $id): JsonResponse
    {
        $installation = $this->service->update($id, $request->validated());

        return response()->json([
            'message' => 'Client installation updated successfully.',
            'data' => new ClientInstallationResource($installation),
        ]);
    }
}