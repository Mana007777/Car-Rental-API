<?php

namespace App\Actions\Employee;

use App\Models\User;

class DeleteEmployeeAction
{
    public function execute(int $id): void
    {
        $user = User::with('employee')->findOrFail($id);

        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();
    }
}