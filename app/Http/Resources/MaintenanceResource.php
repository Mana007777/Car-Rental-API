<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'car_id' => $this->car_id,
            'maintenance_date' => $this->maintenance_date,
            'next_due_date' => $this->next_due_date,
            'maintenance_type' => $this->maintenance_type,
            'description' => $this->description,
            'cost' => $this->cost,
            'performed_by' => $this->performed_by,
            'employee' => $this->whenLoaded('employee'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}