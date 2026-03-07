<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $employee = $this->employee;

        return [
            'id' => $this->id,

            'first_name' => $this->employee->first_name ?? null,
            'last_name'  => $employee?->last_name,
            'email' => $this->email,
            'phone_number' => $employee?->phone_number ?? $this->phone_number,

            'position'  => $employee?->position,
            'branch_id' => $employee?->branch_id,
            'hire_date' => $employee?->hire_date,
            'salary'    => $employee?->salary,

            'role' => $this->role,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
