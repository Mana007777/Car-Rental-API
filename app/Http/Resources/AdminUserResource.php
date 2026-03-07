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

            'first_name' => $this->employee->first_name ?? $this->first_name,
            'last_name'  => $employee?->last_name ?? $this->last_name,
            'email' => $this->email,
            'phone_number' => $employee?->phone_number ?? $this->phone_number,

            'position'  => $employee?->position ?? null,
            'branch_id' => $employee?->branch_id ?? null,
            'hire_date' => $employee?->hire_date ?? null,
            'salary'    => $employee?->salary ?? null,

            'role' => $this->role,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
