<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\Api\V1\AdminResource;
use App\Interfaces\AdminAuthServiceInterface;
use App\Traits\ApiResponse;
use App\Traits\ResolvesAuthenticatedAdmin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    use ApiResponse, ResolvesAuthenticatedAdmin;

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

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($this->authenticatedAdmin($request));

        return $this->successResponse(null, 'Logged out successfully');
    }

    public function profile(Request $request): JsonResponse
    {
        return $this->successResponse(new AdminResource($request->user()), 'Profile retrieved successfully');
    }
}
