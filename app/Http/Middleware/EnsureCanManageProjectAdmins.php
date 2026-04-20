<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageProjectAdmins
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('project_admin')->user();

        if (! $admin) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if ($admin->role !== 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to manage admins.'
            ], 403);
        }

        return $next($request);
    }
}