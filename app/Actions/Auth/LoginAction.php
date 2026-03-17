<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginData;
use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function execute(LoginData $data): array
    {
        $user = User::where('email', $data->email)->first();

        if (! $user || ! Hash::check($data->password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials.');
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => [
                'user' => $user,
            ],
        ];
    }
}