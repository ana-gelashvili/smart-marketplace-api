<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Interfaces\ProductServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProductAdminController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ProductServiceInterface $productService,
    ) {}

    public function index(): JsonResponse
    {
        $products = $this->productService->paginateForAdmin();

        return $this->successResponse([
            'products' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ], 'Products retrieved successfully');
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return $this->successResponse(new ProductResource($product), 'Product created successfully', 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        return $this->successResponse(new ProductResource($product), 'Product retrieved successfully');
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        $product = $this->productService->update($product, $request->validated());

        return $this->successResponse(new ProductResource($product), 'Product updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        $this->productService->delete($product);

        return $this->successResponse(null, 'Product deleted successfully');
    }
}
