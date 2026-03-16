<?php

namespace App\DTOs\Maintenance;

use App\Http\Requests\MaintenanceRequest;

class MaintenanceData
{
    public function __construct(
        public readonly string $maintenance_date,
        public readonly ?string $next_due_date,
        public readonly string $maintenance_type,
        public readonly ?string $description,
        public readonly float $cost,
        public readonly ?int $performed_by,
    ) {}

    public static function fromRequest(MaintenanceRequest $request): self
    {
        return new self(
            maintenance_date: $request->maintenance_date,
            next_due_date: $request->next_due_date,
            maintenance_type: $request->maintenance_type,
            description: $request->description,
            cost: (float) ($request->cost ?? 0),
            performed_by: $request->performed_by,
        );
    }

    public function toArray(): array
    {
        return [
            'maintenance_date' => $this->maintenance_date,
            'next_due_date' => $this->next_due_date,
            'maintenance_type' => $this->maintenance_type,
            'description' => $this->description,
            'cost' => $this->cost,
            'performed_by' => $this->performed_by,
        ];
    }
}