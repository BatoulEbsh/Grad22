<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user instanceof User)
            if ($user
                ->roles()
                ->where('roles.id','=',1)
                ->exists()) {
                return $next($request);
            }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
}
