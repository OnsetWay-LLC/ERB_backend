<?php

namespace App\Modules\CentralDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CentralDashboard\Http\Requests\LoginProjectAdminRequest;
use App\Modules\CentralDashboard\Http\Resources\ProjectAdminResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectAdminAuthController extends Controller
{
    public function login(LoginProjectAdminRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('project_admin')->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $admin = Auth::guard('project_admin')->user();

        if (! $admin->is_active) {
            Auth::guard('project_admin')->logout();

            return response()->json([
                'message' => 'This admin account is inactive.'
            ], 403);
        }

        return response()->json([
            'message' => 'Login successful.',
            'data' => [
                'admin' => new ProjectAdminResource($admin),
                'token' => $token,
                'token_type' => 'bearer',
            ]
        ]);
    }

    public function me(): JsonResponse
    {
        $admin = Auth::guard('project_admin')->user();

        return response()->json([
            'message' => 'Admin profile fetched successfully.',
            'data' => new ProjectAdminResource($admin),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('project_admin')->logout();

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }
}