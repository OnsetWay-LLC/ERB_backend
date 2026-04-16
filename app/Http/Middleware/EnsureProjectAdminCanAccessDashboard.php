<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectAdminCanAccessDashboard
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('project_admin')->user();

        if (! $admin) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

       

        return $next($request);
    }
}