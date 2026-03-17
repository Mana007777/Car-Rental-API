<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Models\Employee;
use App\Models\User;

class CreateEmployeeAction
{
    public function execute(EmployeeData $data): array
    {
        $user = User::create($data->user);

        Employee::create($data->employee);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('employee'),
            'token' => $token,
        ];
    }
}