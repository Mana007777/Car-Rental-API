<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateEmployeeAction
{
    public function execute(EmployeeData $data)
    {
        DB::beginTransaction();

        $user = User::create($data->user);

        $employeeData = $data->employee;
        $employeeData['user_id'] = $user->id;

        Employee::create($employeeData);

        DB::commit();

        return [
            'user' => $user,
            'token' => auth()->login($user),
        ];
    }
}
