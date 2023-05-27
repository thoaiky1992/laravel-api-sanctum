<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Unauthorized'
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            throw new \Exception('Error in Login');
        }

        $tokenResult = $user->createToken('authToken', ['user', 'admin'])->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);
    }

    public function loginWithEmail(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
        ]);

        $user = User::where('email', $request->email)->first();

        auth()->loginUsingId($user->id);

        $tokenResult = $user->createToken('authToken', ['user', 'admin'])->plainTextToken;

        return response()->json([
            'status_code' => 200,
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
        ]);

    }
}
