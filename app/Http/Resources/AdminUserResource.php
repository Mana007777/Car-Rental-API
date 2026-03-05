<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->employee->id,
            'first_name'   => $this->employee->first_name,
            'last_name'    => $this->employee->last_name,
            'email'        => $this->employee->email,
            'phone_number' => $this->employee->phone_number,
            'position'     => $this->employee->position,
            'branch_id'    => $this->employee->branch_id,
            'hire_date'    => $this->employee->hire_date,
            'salary'       => $this->employee->salary,
            'created_at'   => $this->employee->created_at,
            'updated_at'   => $this->employee->updated_at,
        ];
    }
}
