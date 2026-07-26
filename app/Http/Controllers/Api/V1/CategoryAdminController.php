<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Interfaces\CategoryServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryAdminController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
    ) {}

    public function index(): JsonResponse
    {
        return $this->successResponse(
            CategoryResource::collection($this->categoryService->getNestedTree()),
            'Categories retrieved successfully',
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return $this->successResponse(new CategoryResource($category), 'Category created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->findById($id);

        return $this->successResponse(new CategoryResource($category), 'Category retrieved successfully');
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryService->findById($id);
        $category = $this->categoryService->update($category, $request->validated());

        return $this->successResponse(new CategoryResource($category), 'Category updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryService->findById($id);
        $this->categoryService->delete($category);

        return $this->successResponse(null, 'Category deleted successfully');
    }
}
