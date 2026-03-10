<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;
use App\Models\VehicleCategory;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $cars = Car::with(['category', 'branch'])->get();

        return $this->success(
            CarResource::collection($cars),
            'Cars retrieved successfully'
        );
    }

    public function show($id)
    {
        $car = Car::with(['category', 'branch'])->find($id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        $this->authorize('view', $car);

        return $this->success(
            new CarResource($car),
            'Car retrieved successfully'
        );
    }

    public function store(CarRequest $request)
    {
        $this->authorize('create', Car::class);

        return DB::transaction(function () use ($request) {
            $category = VehicleCategory::create($request->categoryData());

            $branch = Branch::create($request->branchData());

            $car = Car::create([
                ...$request->carData(),
                'category_id' => $category->id,
                'branch_id' => $branch->id,
            ]);

            $car->load(['category', 'branch']);

            return $this->success(
                new CarResource($car),
                'Car created successfully',
                201
            );
        });
    }

    public function update(CarRequest $request, $id)
    {
        $car = Car::with(['category', 'branch'])->find($id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        $this->authorize('update', $car);

        return DB::transaction(function () use ($request, $car) {
            $car->update($request->carData());

            if ($car->category) {
                $car->category->update($request->categoryData());
            } else {
                $category = VehicleCategory::create($request->categoryData());
                $car->update(['category_id' => $category->id]);
            }

            if ($car->branch) {
                $car->branch->update($request->branchData());
            } else {
                $branch = Branch::create($request->branchData());
                $car->update(['branch_id' => $branch->id]);
            }

            $car->load(['category', 'branch']);

            return $this->success(
                new CarResource($car),
                'Car updated successfully'
            );
        });
    }

    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        $this->authorize('delete', $car);

        $car->delete();

        return $this->success(null, 'Car deleted successfully');
    }
}