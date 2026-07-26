<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BrandResource;
use App\Interfaces\BrandServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly BrandServiceInterface $brandService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->successResponse(
            BrandResource::collection($this->brandService->list()),
            'Brands retrieved successfully',
        );
    }
}
