<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use ApiResponder;

    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success(collect([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]));
    }

    public function logout() : JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->success(collect([]), 'You have been logged out successfully.');
    }
}
