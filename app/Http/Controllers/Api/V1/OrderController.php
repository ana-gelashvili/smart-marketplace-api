<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Interfaces\OrderServiceInterface;
use App\Traits\ApiResponse;
use App\Traits\ResolvesAuthenticatedMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse, ResolvesAuthenticatedMember;

    public function __construct(
        private readonly OrderServiceInterface $orderService,
    ) {}

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $order = $this->orderService->checkout($this->authenticatedMember($request), $request->validated());

        return $this->successResponse(new OrderResource($order), 'Order placed successfully', 201);
    }

    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->paginateForMember($this->authenticatedMember($request));

        return $this->successResponse([
            'orders' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ], 'Orders retrieved successfully');
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        $order = $this->orderService->findByUuidForMember($this->authenticatedMember($request), $uuid);

        return $this->successResponse(new OrderResource($order), 'Order retrieved successfully');
    }
}
