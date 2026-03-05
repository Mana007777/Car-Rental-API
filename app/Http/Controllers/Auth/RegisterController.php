<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create($request->userData());

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return $this->success(
                'Registration successful',
                new UserResource($user),
                201,
                [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(
                'Registration failed',
                500,
                $e->getMessage()
            );
        }
    }
}