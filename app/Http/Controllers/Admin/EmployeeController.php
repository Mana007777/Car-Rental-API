<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Employee\CreateEmployeeAction;
use App\Actions\Employee\DeleteEmployeeAction;
use App\Actions\Employee\ListEmployeesAction;
use App\Actions\Employee\ShowEmployeeAction;
use App\Actions\Employee\UpdateEmployeeAction;
use App\DTOs\Employee\EmployeeData;
use App\DTOs\Employee\EmployeeFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request, ListEmployeesAction $action)
    {
        $this->authorize('viewAny', User::class);

        $users = $action->execute(EmployeeFilterData::fromRequest($request));

        return $this->success(
            AdminUserResource::collection($users),
            'Users retrieved successfully'
        );
    }

    public function store(StoreEmployeeRequest $request, CreateEmployeeAction $action)
{
    $result = $action->execute(EmployeeData::fromRequest($request), $request);

    return $this->success(
        [
            'employee' => new AdminUserResource($result['user']),
        ],
        'Employee created successfully',
        201,
        [
            'token' => $result['token'],
            'token_type' => 'Bearer'
        ]
    );
}

    public function show(int $id, ShowEmployeeAction $action)
    {
        return $this->success(
            new AdminUserResource($action->execute($id)),
            'Employee retrieved successfully'
        );
    }

    public function update(UpdateEmployeeRequest $request, int $id, UpdateEmployeeAction $action)
    {
        return $this->success(
            new AdminUserResource($action->execute($id, EmployeeData::fromRequest($request, $id))),
            'Employee updated successfully'
        );
    }

    public function destroy(int $id, DeleteEmployeeAction $action)
    {
        $action->execute($id);

        return $this->success(
            null,
            'Employee deleted successfully'
        );
    }
}