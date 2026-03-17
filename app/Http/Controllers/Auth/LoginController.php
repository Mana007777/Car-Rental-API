<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action)
    {
        try {
            return response()->json(
                $action->execute(LoginData::fromRequest($request)),
                200
            );
        } catch (HttpException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}