<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\CreatePaymentAction;
use App\DTOs\Payment\PaymentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Traits\ApiResponse;

class PaymentController extends Controller
{
    use ApiResponse;

    public function store(PaymentRequest $request, CreatePaymentAction $action)
    {
        $payment = $action->execute(PaymentData::fromRequest($request));

        return $this->success(
            new PaymentResource($payment),
            'Payment completed successfully. Waiting for manager approval.',
            201
        );
    }
}