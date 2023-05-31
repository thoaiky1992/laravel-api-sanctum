<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Invalid Email.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password, [])) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Invalid Password.'
            ], 401);
        }

        $tokenResult = $user->createToken('authToken', ['USER'])->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function register(UserRequest $request)
    {

        $payload = $request->validated();

        $payload['password'] = Hash::make($payload['password']);

        $user = User::create($payload);

        $tokenResult = $user->createToken('authToken', ['USER'])->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ], 201);
    }
}
