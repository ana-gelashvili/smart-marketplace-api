<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Http\Resources\Api\V1\CartResource;
use App\Interfaces\CartServiceInterface;
use App\Traits\ApiResponse;
use App\Traits\ResolvesAuthenticatedMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse, ResolvesAuthenticatedMember;

    public function __construct(
        private readonly CartServiceInterface $cartService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCartForMember($this->authenticatedMember($request));

        return $this->successResponse(new CartResource($cart), 'Cart retrieved successfully');
    }

    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $item = $this->cartService->addItem($this->authenticatedMember($request), $request->validated());

        return $this->successResponse(new CartItemResource($item), 'Item added to cart successfully', 201);
    }

    public function updateItem(UpdateCartItemRequest $request, int $id): JsonResponse
    {
        $item = $this->cartService->updateItem($this->authenticatedMember($request), $id, $request->validated());

        return $this->successResponse(new CartItemResource($item), 'Cart item updated successfully');
    }

    public function removeItem(Request $request, int $id): JsonResponse
    {
        $this->cartService->removeItem($this->authenticatedMember($request), $id);

        return $this->successResponse(null, 'Item removed from cart successfully');
    }
}
