<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\StoreEmployeeRequest;

class CreateEmployeeAction
{
    public function execute(EmployeeData $data, StoreEmployeeRequest $request): array
    {
        return DB::transaction(function () use ($data, $request) {
            $user = User::create($data->user);

            $employee = Employee::create(
                $request->employeeData($user->id)
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'employee' => $employee,
                'token' => $token,
            ];
        });
    }
}