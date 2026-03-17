<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeeAction
{
    public function execute(int $id, EmployeeData $data): User
    {
        $user = User::with('employee')->findOrFail($id);

        $userData = $data->user;
        $employeeData = $data->employee;

        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            Employee::create($employeeData);
        }

        return $user->fresh('employee');
    }
}