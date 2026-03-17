<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeFilterData;
use App\Models\User;

class ListEmployeesAction
{
    public function execute(EmployeeFilterData $filters)
    {
        return User::with('employee')
            ->when($filters->search, function ($query, $search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
    }
}