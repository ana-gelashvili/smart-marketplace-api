<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginMemberRequest;
use App\Http\Requests\RegisterMemberRequest;
use App\Http\Resources\MemberResource;
use App\Interfaces\MemberAuthServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberAuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly MemberAuthServiceInterface $authService,
    ) {}

    public function register(RegisterMemberRequest $request): JsonResponse
    {
        $member = $this->authService->register($request->validated());
        $token = $this->authService->issueToken($member);

        return $this->successResponse([
            'member' => new MemberResource($member),
            'token' => $token,
        ], 'Registered successfully', 201);
    }

    public function login(LoginMemberRequest $request): JsonResponse
    {
        $member = $this->authService->verifyCredentials($request->validated());

        if ($member === null) {
            return $this->errorResponse('Invalid credentials', null, 401);
        }

        $token = $this->authService->issueToken($member);

        return $this->successResponse([
            'member' => new MemberResource($member),
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
        return $this->successResponse(new MemberResource($request->user()), 'Profile retrieved successfully');
    }
}
