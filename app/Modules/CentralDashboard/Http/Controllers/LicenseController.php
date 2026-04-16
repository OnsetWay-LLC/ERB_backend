<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Requests\GenerateLicenseRequest;
use App\Modules\CentralDashboard\Http\Resources\LicenseResource;
use App\Modules\CentralDashboard\Repositories\LicenseRepository;
use App\Modules\CentralDashboard\Services\LicenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\CentralDashboard\Http\Requests\SendActivationTokenRequest;
use App\Modules\CentralDashboard\Http\Requests\VerifyActivationTokenRequest;
use App\Modules\CentralDashboard\Http\Requests\GenerateRenewalLicenseRequest;

class LicenseController extends Controller
{
    public function __construct(
        protected LicenseRepository $repository,
        protected LicenseService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $licenses = $this->repository->paginate($perPage);

        return response()->json([
            'message' => 'Licenses fetched successfully.',
            'data'    => LicenseResource::collection($licenses),
            'meta'    => [
                'current_page' => $licenses->currentPage(),
                'last_page'    => $licenses->lastPage(),
                'per_page'     => $licenses->perPage(),
                'total'        => $licenses->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $license = $this->repository->findById($id);

        return response()->json([
            'message' => 'License details fetched successfully.',
            'data'    => new LicenseResource($license),
        ]);
    }

   public function generate(GenerateLicenseRequest $request): JsonResponse
{
    $result = $this->service->generate($request->validated());

    return response()->json([
        'message' => 'Activation token generated successfully.',
        'data'    => [
            'license_id'       => $result['license']->id,
            'activation_token' => $result['plain_token'],
            'duration_type'    => $result['license']->duration_type,
            'status'           => $result['license']->status,
            'token_expires_at' => $result['license']->token_expires_at,
        ],
    ], 201);
}
public function sendToken(SendActivationTokenRequest $request): JsonResponse
{
    $license = $this->service->sendToken($request->validated());

    return response()->json([
        'message' => 'Activation token sent successfully.',
        'data'    => [
            'license_id' => $license->id,
            'email'      => $license->licenseRequest?->email,
            'status'     => $license->status,
            'sent_at'    => $license->sent_at,
        ],
    ]);
}
public function verifyToken(VerifyActivationTokenRequest $request): JsonResponse
{
    $license = $this->service->verifyToken($request->validated());

    return response()->json([
        'message' => 'Activation token verified successfully.',
        'data'    => [
            'license_id' => $license->id,
            'status'     => $license->status,
            'starts_at'  => $license->starts_at,
            'expires_at' => $license->expires_at,
            'email'      => $license->licenseRequest?->email,
        ],
    ]);
}
public function generateRenewal(GenerateRenewalLicenseRequest $request): JsonResponse
{
    $result = $this->service->generateRenewal($request->validated());

    return response()->json([
        'message' => 'Renewal activation token generated successfully.',
        'data'    => [
            'license_id' => $result['license']->id,
            'parent_license_id' => $result['license']->parent_license_id,
            'activation_token' => $result['plain_token'],
            'license_type' => $result['license']->license_type,
            'duration_type' => $result['license']->duration_type,
            'status' => $result['license']->status,
            'token_expires_at' => $result['license']->token_expires_at,
        ],
    ], 201);
}
}