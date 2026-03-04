<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
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

            $user = User::create([
                'name'     => $request->first_name . ' ' . $request->last_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            $employee = Employee::create([
                'user_id'      => $user->id,
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'position'     => ucfirst($request->role),
                'branch_id'    => $request->branch_id,
                'hire_date'    => now(),
                'salary'       => $request->salary,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully',
                'data' => [
                    'user' => $user,
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