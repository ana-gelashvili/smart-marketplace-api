<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\Api\V1\BrandResource;
use App\Interfaces\BrandServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BrandAdminController extends Controller
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

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $brand = $this->brandService->create($request->validated());

        return $this->successResponse(new BrandResource($brand), 'Brand created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $brand = $this->brandService->findById($id);

        return $this->successResponse(new BrandResource($brand), 'Brand retrieved successfully');
    }

    public function update(UpdateBrandRequest $request, int $id): JsonResponse
    {
        $brand = $this->brandService->findById($id);
        $brand = $this->brandService->update($brand, $request->validated());

        return $this->successResponse(new BrandResource($brand), 'Brand updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $brand = $this->brandService->findById($id);
        $this->brandService->delete($brand);

        return $this->successResponse(null, 'Brand deleted successfully');
    }
}
