<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Requests\ChangeClientInstallationStatusRequest;
use App\Modules\CentralDashboard\Http\Requests\StoreClientInstallationRequest;
use App\Modules\CentralDashboard\Http\Requests\UpdateClientInstallationRequest;
use App\Modules\CentralDashboard\Http\Resources\ClientInstallationResource;
use App\Modules\CentralDashboard\Repositories\ClientInstallationRepository;
use App\Modules\CentralDashboard\Services\ClientInstallationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientInstallationController extends Controller
{
    public function __construct(
        protected ClientInstallationRepository $repository,
        protected ClientInstallationService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $installations = $this->repository->paginate($perPage);

        return response()->json([
            'message' => 'Client installations fetched successfully.',
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
            'data' => new ClientInstallationResource(
                $installation->load(['licenseRequest', 'activeLicense'])
            ),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $installation = $this->repository->findById($id);

        return response()->json([
            'message' => 'Client installation fetched successfully.',
            'data' => new ClientInstallationResource($installation),
        ]);
    }

    public function update(UpdateClientInstallationRequest $request, int $id): JsonResponse
    {
        $installation = $this->repository->findById($id);
        $installation = $this->service->update($installation, $request->validated());

        return response()->json([
            'message' => 'Client installation updated successfully.',
            'data' => new ClientInstallationResource($installation),
        ]);
    }

   
}