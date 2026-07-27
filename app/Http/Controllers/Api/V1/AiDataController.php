<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\AiDataServiceInterface;
use App\Models\Member;
use Illuminate\Http\JsonResponse;

class AiDataController extends Controller
{
    public function __construct(
        private readonly AiDataServiceInterface $aiDataService,
    ) {}

    public function profile(Member $member): JsonResponse
    {
        return response()->json($this->aiDataService->getMemberProfile($member));
    }

    public function candidates(): JsonResponse
    {
        return response()->json($this->aiDataService->getCandidateProducts());
    }
}
