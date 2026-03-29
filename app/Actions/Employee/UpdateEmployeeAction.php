<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Exceptions\NotFoundException;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeeAction
{
    public function execute(int $id, EmployeeData $data): User
    {
        $user = User::with('employee')->find($id);

        if (! $user) {
            throw new NotFoundException('Employee not found');
        }

        $userData = $data->user;
        $employeeData = $data->employee;

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            $user->employee()->create($employeeData);
        }

        return $user->fresh('employee');
    }
}