<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAiServiceKey
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = config('services.ai.key');

        if (blank($expectedKey) || ! hash_equals($expectedKey, (string) $request->header('X-AI-SERVICE-KEY'))) {
            return $this->errorResponse('Unauthorized', null, 401);
        }

        return $next($request);
    }
}
