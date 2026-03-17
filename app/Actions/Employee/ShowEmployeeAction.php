<?php

namespace App\Actions\Employee;

use App\Models\User;

class ShowEmployeeAction
{
    public function execute(int $id): User
    {
        return User::with('employee')->findOrFail($id);
    }
}