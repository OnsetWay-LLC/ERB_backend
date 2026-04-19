<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Requests\StoreProjectAdminRequest;
use App\Modules\CentralDashboard\Http\Resources\ProjectAdminResource;
use App\Modules\CentralDashboard\Repositories\ProjectAdminRepository;
use App\Modules\CentralDashboard\Services\ProjectAdminService;
use App\Modules\CentralDashboard\Http\Requests\UpdateProjectAdminRequest;
use App\Modules\CentralDashboard\Http\Requests\StoreFirstProjectAdminRequest;
use App\Models\ProjectAdmin;
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
    
   public function storeFirstAdmin(StoreFirstProjectAdminRequest $request): JsonResponse
{
    if (ProjectAdmin::count() > 0) {
        return response()->json([
            'message' => 'The first admin has already been created.',
        ], 403);
    }

    $admin = ProjectAdmin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
        'role' => $request->role,
        'is_active' => true,
    ]);

    return response()->json([
        'message' => 'First super admin created successfully.',
        'data' => [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'is_active' => $admin->is_active,
            'created_at' => $admin->created_at?->format('Y-m-d H:i:s'),
        ],
    ], 201);
}
   
}