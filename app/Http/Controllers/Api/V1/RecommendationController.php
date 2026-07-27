<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\AiServiceInterface;
use App\Traits\ApiResponse;
use App\Traits\ResolvesAuthenticatedMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    use ApiResponse, ResolvesAuthenticatedMember;

    public function __construct(
        private readonly AiServiceInterface $aiService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $member = $this->authenticatedMember($request);

        $recommendations = $this->aiService->getRecommendations($member->id);

        return $this->successResponse($recommendations, 'Recommendations retrieved successfully');
    }
}
