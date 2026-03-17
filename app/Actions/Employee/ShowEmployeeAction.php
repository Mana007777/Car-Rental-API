<?php

namespace App\Actions\Employee;

use App\Exceptions\NotFoundException;
use App\Models\User;

class ShowEmployeeAction
{
    public function execute(int $id): User
    {
        $user = User::with('employee')->find($id);

        if (! $user) {
            throw new NotFoundException('Employee not found');
        }

        return $user;
    }
}