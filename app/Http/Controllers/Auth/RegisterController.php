<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterAction;
use App\DTOs\Auth\RegisterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;

class RegisterController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request, RegisterAction $action)
    {
        $result = $action->execute(RegisterData::fromRequest($request));

        return $this->success(
            new UserResource($result['user']),
            'Registration successful',
            201,
            [
                'token' => $result['token'],
                'token_type' => 'Bearer'
            ]
        );
    }
}