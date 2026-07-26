<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Reject any token that wasn't issued for the admin guard, even if it
     * otherwise authenticates via admin-api's user provider (e.g. a Member
     * JWT whose subject id happens to collide with a User id).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (JWTAuth::parseToken()->getPayload()->get('guard') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'errors' => null,
            ], 401);
        }

        return $next($request);
    }
}
