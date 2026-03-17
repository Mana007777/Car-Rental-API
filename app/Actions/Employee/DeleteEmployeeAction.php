<?php

namespace App\Actions\Employee;

use App\Exceptions\NotFoundException;
use App\Models\User;

class DeleteEmployeeAction
{
    public function execute(int $id): void
    {
        $user = User::with('employee')->find($id);

        if (! $user) {
            throw new NotFoundException('Employee not found');
        }

        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();
    }
}