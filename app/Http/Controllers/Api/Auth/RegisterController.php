<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use ApiResponder;

    public function register(RegisterRequest $request) : JsonResponse 
    {
        $validatedData = $request->validated();

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'password' => bcrypt($validatedData['password']),
            'email' => $validatedData['email'],
            'role_id' => Role::ADMIN
        ]);

        return $this->success(collect([
            'token' => $user->createToken('API Token')->plainTextToken
        ]), "Your account was created successfully", 201);
    }
}
