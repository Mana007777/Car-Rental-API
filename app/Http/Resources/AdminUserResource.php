<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $employee = $this->employee;

        $data = [
            'id'           => $this->id,
            'first_name'   => $employee?->first_name ?? $this->first_name,
            'last_name'    => $employee?->last_name ?? $this->last_name,
            'email'        => $this->email,
            'phone_number' => $employee?->phone_number ?? $this->phone_number,
            'role'         => $this->role ?? 'customer',
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];

        if ($employee) {
            $data['position']  = $employee->position;
            $data['branch_id'] = $employee->branch_id;
            $data['hire_date'] = $employee->hire_date;
            $data['salary']    = $employee->salary;
        }

        return $data;
    }
}