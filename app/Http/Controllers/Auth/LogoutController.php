<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use ApiResponse;

    public function logout(Request $request, LogoutAction $action)
    {
        $action->execute($request->user());

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ], 200);
    }
}