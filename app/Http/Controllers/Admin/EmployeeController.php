<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    use ApiResponse;
    public function store(StoreEmployeeRequest $request)
    {
        DB::beginTransaction();

        try {

            $user = User::create($request->userData());

            $token = $user->createToken('auth_token')->plainTextToken;

            $employee = Employee::create(
                $request->employeeData($user->id)
            );

            DB::commit();

            return $this->success(
                'Employee created successfully',
                [
                    'user' => new AdminUserResource($user),
                    'employee' => $employee
                ],
                201,
                [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                'Employee creation failed',
                500,
                $e->getMessage()
            );
        }
    }
}
