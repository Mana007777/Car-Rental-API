<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
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

        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => [
                'user' => new AdminUserResource($user),
                'employee' => $employee
            ]
        ], 201);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Employee creation failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
}