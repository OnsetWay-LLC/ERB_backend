<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Requests\StoreProjectAdminRequest;
use App\Modules\CentralDashboard\Http\Resources\ProjectAdminResource;
use App\Modules\CentralDashboard\Repositories\ProjectAdminRepository;
use App\Modules\CentralDashboard\Services\ProjectAdminService;
use App\Modules\CentralDashboard\Http\Requests\UpdateProjectAdminRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//to add admins
class ProjectAdminController extends Controller
{
    public function __construct(
        protected ProjectAdminRepository $repository,
        protected ProjectAdminService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 10);

        $admins = $this->repository->paginate($perPage);

        return response()->json([
            'message' => 'Project admins fetched successfully.',
            'data' => ProjectAdminResource::collection($admins),
            'meta' => [
                'current_page' => $admins->currentPage(),
                'last_page' => $admins->lastPage(),
                'per_page' => $admins->perPage(),
                'total' => $admins->total(),
            ],
        ]);
    }

    public function store(StoreProjectAdminRequest $request): JsonResponse
    {
        $admin = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Project admin created successfully.',
            'data' => new ProjectAdminResource($admin),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $admin = $this->repository->findById($id);

        return response()->json([
            'message' => 'Project admin fetched successfully.',
            'data' => new ProjectAdminResource($admin),
        ]);
    }
   public function update(UpdateProjectAdminRequest $request, int $id): JsonResponse
{
    $admin = $this->repository->findById($id);

    $data = $request->validated();

    if (!empty($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    } else {
        unset($data['password']);
    }

    $admin->update($data);

    return response()->json([
        'message' => 'Project admin updated successfully.',
        'data' => new ProjectAdminResource($admin->fresh()),
    ]);
}
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Project admin deleted successfully.',
        ]);
    }
   
}