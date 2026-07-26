<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePaymentRequest;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Interfaces\PaymentServiceInterface;
use App\Traits\ApiResponse;
use App\Traits\ResolvesAuthenticatedMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse, ResolvesAuthenticatedMember;

    public function __construct(
        private readonly PaymentServiceInterface $paymentService,
    ) {}

    public function store(CreatePaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->createForMember($this->authenticatedMember($request), $request->validated());

        return $this->successResponse(new PaymentResource($payment), 'Payment created successfully', 201);
    }

    public function indexForOrder(Request $request, string $uuid): JsonResponse
    {
        $payments = $this->paymentService->listForOrder($this->authenticatedMember($request), $uuid);

        return $this->successResponse(PaymentResource::collection($payments), 'Payments retrieved successfully');
    }

    public function success(Request $request, int $id): JsonResponse
    {
        $payment = $this->paymentService->markSuccessful($this->authenticatedMember($request), $id);

        return $this->successResponse(new PaymentResource($payment), 'Payment marked as successful');
    }

    public function failed(Request $request, int $id): JsonResponse
    {
        $payment = $this->paymentService->markFailed($this->authenticatedMember($request), $id);

        return $this->successResponse(new PaymentResource($payment), 'Payment marked as failed');
    }
}
