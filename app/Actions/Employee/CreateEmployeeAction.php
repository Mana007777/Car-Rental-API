<?php

namespace App\Actions\Employee;

use App\DTOs\Employee\EmployeeData;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateEmployeeAction
{
    public function execute(EmployeeData $data, $request): array
    {
        return DB::transaction(function () use ($data, $request) {
            $user = User::create($data->user);

            $employee = Employee::create(
                $request->employeeData($user->id)
            );

            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user,
                'employee' => $employee,
                'token' => $token,
            ];
        });
    }
}