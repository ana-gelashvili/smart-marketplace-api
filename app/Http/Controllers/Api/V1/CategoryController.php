<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Interfaces\CategoryServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
    ) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getNestedTree();

        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categories retrieved successfully',
        );
    }
}
