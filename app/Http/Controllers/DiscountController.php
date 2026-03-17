<?php

namespace App\Http\Controllers;

use App\Actions\Discount\CreateDiscountAction;
use App\Actions\Discount\DeleteDiscountAction;
use App\Actions\Discount\ListDiscountsAction;
use App\Actions\Discount\ShowDiscountAction;
use App\Actions\Discount\UpdateDiscountAction;
use App\DTOs\Discount\DiscountData;
use App\Http\Requests\DiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DiscountController extends Controller
{
    use ApiResponse;

    public function index(ListDiscountsAction $action)
    {
        return $this->success(
            DiscountResource::collection($action->execute()),
            'Discounts retrieved successfully'
        );
    }

    public function store(DiscountRequest $request, CreateDiscountAction $action)
    {
        $discount = $action->execute(DiscountData::fromRequest($request));

        return $this->success(
            new DiscountResource($discount),
            'Discount created successfully',
            201
        );
    }

    public function show(int $id, ShowDiscountAction $action)
    {
        try {
            return $this->success(
                new DiscountResource($action->execute($id)),
                'Discount retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Discount not found', 404);
        }
    }

    public function update(UpdateDiscountRequest $request, int $id, UpdateDiscountAction $action)
    {
        try {
            $discount = $action->execute($id, DiscountData::fromRequest($request));

            return $this->success(
                new DiscountResource($discount),
                'Discount updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Discount not found', 404);
        }
    }

    public function destroy(int $id, DeleteDiscountAction $action)
    {
        try {
            $action->execute($id);

            return $this->success(null, 'Discount deleted successfully');
        } catch (ModelNotFoundException) {
            return $this->error('Discount not found', 404);
        }
    }
}