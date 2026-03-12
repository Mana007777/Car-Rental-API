<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Branch;
use App\Models\Discount;
use App\Models\Insurance;
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
        $cars = Car::with([
            'category',
            'branch',
            'insurance',
            'maintenances.employee',
            'discount',
        ])->get();

        return $this->success(
            'Cars retrieved successfully',
            CarResource::collection($cars)
        );
    }

    public function show($id)
    {
        $car = Car::with([
            'category',
            'branch',
            'insurance',
            'maintenances.employee',
            'discount',
        ])->findOrFail($id);

        $this->authorize('view', $car);

        return $this->success(
            'Car retrieved successfully',
            new CarResource($car)
        );
    }

    public function store(CarRequest $request)
    {
        $this->authorize('create', Car::class);

        return DB::transaction(function () use ($request) {
            $category = VehicleCategory::create($request->categoryData());
            $branch = Branch::create($request->branchData());
            $insurance = Insurance::create($request->insuranceData());

            $discount = null;
            if ($request->hasDiscountData()) {
                $discount = Discount::create([
                    'code' => $request->discountData()['code'],
                    'description' => $request->discountData()['description'] ?? null,
                    'discount_percentage' => $request->discountData()['discount_percentage'],
                    'start_date' => $request->discountData()['start_date'] ?? null,
                    'end_date' => $request->discountData()['end_date'] ?? null,
                    'active' => $request->discountData()['active'] ?? true,
                ]);
            }

            $car = Car::create([
                ...$request->carData(),
                'category_id' => $category->id,
                'branch_id' => $branch->id,
                'insurance_id' => $insurance->id,
                'discount_id' => $discount?->id,
            ]);

            $car->load([
                'category',
                'branch',
                'insurance',
                'discount',
            ]);

            return $this->success(
                'Car created successfully',
                new CarResource($car),
                201
            );
        });
    }

    public function update(CarRequest $request, $id)
    {
        $car = Car::with([
            'category',
            'branch',
            'insurance',
            'discount',
        ])->findOrFail($id);

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

            if ($car->insurance) {
                $car->insurance->update($request->insuranceData());
            } else {
                $insurance = Insurance::create($request->insuranceData());
                $car->update(['insurance_id' => $insurance->id]);
            }

            if ($request->hasDiscountData()) {
                if ($car->discount) {
                    $car->discount->update([
                        'code' => $request->discountData()['code'],
                        'description' => $request->discountData()['description'] ?? null,
                        'discount_percentage' => $request->discountData()['discount_percentage'],
                        'start_date' => $request->discountData()['start_date'] ?? null,
                        'end_date' => $request->discountData()['end_date'] ?? null,
                        'active' => $request->discountData()['active'] ?? true,
                    ]);
                } else {
                    $discount = Discount::create([
                        'code' => $request->discountData()['code'],
                        'description' => $request->discountData()['description'] ?? null,
                        'discount_percentage' => $request->discountData()['discount_percentage'],
                        'start_date' => $request->discountData()['start_date'] ?? null,
                        'end_date' => $request->discountData()['end_date'] ?? null,
                        'active' => $request->discountData()['active'] ?? true,
                    ]);

                    $car->update([
                        'discount_id' => $discount->id,
                    ]);
                }
            }

            $car->load([
                'category',
                'branch',
                'insurance',
                'discount',
            ]);

            return $this->success(
                'Car updated successfully',
                new CarResource($car)
            );
        });
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        $this->authorize('delete', $car);

        $car->delete();

        return $this->success(
            'Car deleted successfully',
            null
        );
    }
}