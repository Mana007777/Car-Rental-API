<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\CreatePaymentAction;
use App\DTOs\Payment\PaymentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentController extends Controller
{
    use ApiResponse;

    public function store(PaymentRequest $request, CreatePaymentAction $action)
    {
        try {
            $payment = $action->execute(PaymentData::fromRequest($request));

            return $this->success(
                new PaymentResource($payment),
                'Payment completed successfully. Waiting for manager approval.',
                201
            );
        } catch (ModelNotFoundException) {
            return $this->error('Reservation not found', 404);
        } catch (HttpException $e) {
            return $this->error($e->getMessage(), $e->getStatusCode());
        }
    }
}