<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterData;
use App\Models\User;

class RegisterAction
{
    public function execute(RegisterData $data): array
    {
        $user = User::create($data->attributes);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}