<?php

namespace App\Http\Controllers;

use App\Actions\Car\CreateCarAction;
use App\Actions\Car\DeleteCarAction;
use App\Actions\Car\ListCarsAction;
use App\Actions\Car\ShowCarAction;
use App\Actions\Car\UpdateCarAction;
use App\DTOs\Car\CarData;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Traits\ApiResponse;

class CarController extends Controller
{
    use ApiResponse;

    public function index(ListCarsAction $action)
    {
        return $this->success(
            CarResource::collection($action->execute()),
            'Cars retrieved successfully'
        );
    }

    public function show(int $id, ShowCarAction $action)
    {
        $car = $action->execute($id);
        $this->authorize('view', $car);

        return $this->success(
            new CarResource($car),
            'Car retrieved successfully'
        );
    }

    public function store(CarRequest $request, CreateCarAction $action)
    {
        $this->authorize('create', Car::class);

        $car = $action->execute(CarData::fromRequest($request));

        return $this->success(
            new CarResource($car),
            'Car created successfully',
            201
        );
    }

    public function update(CarRequest $request, int $id, UpdateCarAction $action)
    {
        $car = $action->execute($id, CarData::fromRequest($request));
        $this->authorize('update', $car);

        return $this->success(
            new CarResource($car),
            'Car updated successfully'
        );
    }

    public function destroy(int $id, DeleteCarAction $action)
    {
        $car = app(ShowCarAction::class)->execute($id);
        $this->authorize('delete', $car);

        $action->execute($id);

        return $this->success(
            null,
            'Car deleted successfully'
        );
    }
}
