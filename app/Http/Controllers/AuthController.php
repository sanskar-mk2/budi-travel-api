<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'role' => 'required|string|in:user,agent',
        ]);

        $user = new \App\Models\User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->save();

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Successfully created user!',
            'token' => $user->createToken('auth_token', ['auth_token'])->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'role' => 'required|string|in:user,agent',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (
            !$user ||
            !$user->hasRole($request->role) ||
            !Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $user->createToken('auth_token', ['auth_token'])->plainTextToken,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('roles'));
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'message' => 'Reset token received',
            'token' => $user->createToken('reset_token', ['reset_token'])->plainTextToken,
        ], 200);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
        ]);

        if (!$request->user()->tokenCan('reset_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        $request->user()->tokens()->delete();

        $request->user()->password = bcrypt($request->password);
        $request->user()->save();

        return response()->json([
            'message' => 'Password Reset Successfully, Please login again',
        ], 200);
    }

    public function logout_everywhere(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out everywhere',
        ], 200);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
        ]);

        $request->user()->tokens()->delete();

        $request->user()->password = bcrypt($request->password);
        $request->user()->save();

        return response()->json([
            'message' => 'Password Changed, login with new password',
        ], 200);
    }

    public function change_email(Request $request)
    {
        // if (! $request->user()->tokenCan('auth_token')) {
        //     return response()->json([
        //         'message' => 'Invalid token',
        //     ], 401);
        // }

        $request->validate([
            'email' => 'required|string|email|unique:users',
        ]);

        $request->user()->tokens()->delete();

        $request->user()->email = $request->email;
        $request->user()->email_verified_at = null;
        $request->user()->save();

        return response()->json([
            'message' => 'Email Changed, login with new email',
        ], 200);
    }
}
