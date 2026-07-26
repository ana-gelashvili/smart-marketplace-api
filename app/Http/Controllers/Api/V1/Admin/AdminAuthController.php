<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\Api\V1\AdminResource;
use App\Interfaces\AdminAuthServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AdminAuthServiceInterface $authService,
    ) {}

    public function login(AdminLoginRequest $request): JsonResponse
    {
        $admin = $this->authService->verifyCredentials($request->validated());

        if ($admin === null) {
            return $this->errorResponse('Invalid credentials', null, 401);
        }

        $token = $this->authService->issueToken($admin);

        return $this->successResponse([
            'admin' => new AdminResource($admin),
            'token' => $token,
        ], 'Login successful');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->successResponse(null, 'Logged out successfully');
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse(new AdminResource($request->user()), 'Profile retrieved successfully');
    }
}
