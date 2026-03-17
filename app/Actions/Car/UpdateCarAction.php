<?php

namespace App\Actions\Car;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Car\CarData;
use App\Exceptions\NotFoundException;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;

class UpdateCarAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(int $id, CarData $data): Car
    {
        $car = Car::with([
            'category',
            'branch',
            'insurance',
            'discount',
        ])->find($id);

        if (! $car) {
            throw new NotFoundException('Car not found');
        }

        return DB::transaction(function () use ($car, $data) {
            $oldValues = $car->toArray();

            $car->update($data->car);

            if ($car->category) {
                $car->category->update($data->category);
            } else {
                $category = VehicleCategory::create($data->category);
                $car->update(['category_id' => $category->id]);
            }

            if ($car->branch) {
                $car->branch->update($data->branch);
            } else {
                $branch = Branch::create($data->branch);
                $car->update(['branch_id' => $branch->id]);
            }

            if ($car->insurance) {
                $car->insurance->update($data->insurance);
            } else {
                $insurance = Insurance::create($data->insurance);
                $car->update(['insurance_id' => $insurance->id]);
            }

            if ($data->discount) {
                if ($car->discount) {
                    $car->discount->update($data->discount);
                } else {
                    $discount = Discount::create($data->discount);
                    $car->update(['discount_id' => $discount->id]);
                }
            }

            $car->load([
                'category',
                'branch',
                'insurance',
                'discount',
            ]);

            $this->createAuditLogAction->execute(
                action: 'updated',
                tableName: 'cars',
                recordId: $car->id,
                description: 'Car updated',
                oldValues: $oldValues,
                newValues: $car->fresh()->toArray()
            );

            return $car;
        });
    }
}