<?php

namespace App\Actions\Car;

use App\Actions\AuditLog\CreateAuditLogAction;
use App\DTOs\Car\CarData;
use App\Models\Branch;
use App\Models\Car;
use App\Models\Discount;
use App\Models\Insurance;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;

class CreateCarAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLogAction
    ) {}

    public function execute(CarData $data): Car
    {
        return DB::transaction(function () use ($data) {
            $category = VehicleCategory::create($data->category);
            $branch = Branch::create($data->branch);
            $insurance = Insurance::create($data->insurance);

            $discount = null;
            if ($data->discount) {
                $discount = Discount::create($data->discount);
            }

            $car = Car::create([
                ...$data->car,
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

            $this->createAuditLogAction->execute(
                action: 'created',
                tableName: 'cars',
                recordId: $car->id,
                description: 'Car created',
                newValues: $car->toArray()
            );

            return $car;
        });
    }
}