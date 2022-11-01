<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (
            ! $user ||
            ! $user->hasRole(['staff', 'admin']) ||
            ! Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $user->createToken('auth_token', ['auth_token_admin'])->plainTextToken,
            'user' => $user,
        ], 200);
    }
}
