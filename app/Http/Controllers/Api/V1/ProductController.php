<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProductResource;
use App\Interfaces\ProductServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ProductServiceInterface $productService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->paginate($request->query());

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

    public function show(string $slug): JsonResponse
    {
        $product = $this->productService->findBySlug($slug);

        return $this->successResponse(new ProductResource($product), 'Product retrieved successfully');
    }
}
