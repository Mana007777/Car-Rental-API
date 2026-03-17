<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action)
    {
        return response()->json(
            $action->execute(LoginData::fromRequest($request)),
            200
        );
    }
}