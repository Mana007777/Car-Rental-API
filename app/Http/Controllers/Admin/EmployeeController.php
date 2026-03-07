<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('employee')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return $this->success(
            'Users retrieved successfully',
            AdminUserResource::collection($users)
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::create($request->userData());

        Employee::create(
            $request->employeeData($user->id)
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            'Employee created successfully',
            [
                'employee' => new AdminUserResource($user),
            ],
            201,
            [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        );
    }

    public function show($id)
    {
        $user = User::with('employee')->findOrFail($id);

        return $this->success(
            'Employee retrieved successfully',
            new AdminUserResource($user)
        );
    }
}