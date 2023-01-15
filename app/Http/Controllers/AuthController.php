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
            'device_id' => 'nullable|string',
        ]);

        $user = new \App\Models\User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->save();

        $user->assignRole($request->role);

        if ($request->device_id) {
            $device = new \App\Models\Device([
                'device_id' => $request->device_id,
            ]);

            $user->device()->save($device);
        }

        $device_id = $user->device->device_id;
        $user_res = new \App\Http\Resources\UserResource($user);

        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user_res,
            'token' => $user->createToken('auth_token', ['auth_token'])->plainTextToken,
            'device_id' => $device_id,
            'push_notifications' => true,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'role' => 'required|string|in:user,agent',
            'device_id' => 'nullable|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (
            ! $user ||
            ! $user->hasRole($request->role) ||
            ! Hash::check($request->password, $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        if ($request->device_id) {
            $device = \App\Models\Device::where('user_id', $user->id)->first();
            if ($device) {
                $device->device_id = $request->device_id;
                $device->save();
            } else {
                $device = new \App\Models\Device([
                    'user_id' => $user->id,
                    'device_id' => $request->device_id,
                ]);
                $device->save();
            }
        }

        $user = $user->load('profile');
        $device_id = $user->device->device_id;
        $user_res = new \App\Http\Resources\UserResource($user);

        return response()->json([
            'message' => 'Successfully logged in',
            'user' => $user_res,
            'token' => $user->createToken('auth_token', ['auth_token'])->plainTextToken,
            'push_notifications' => $user->userDetail->push_notifications,
            'device_id' => $device_id,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // delete device
        $device = \App\Models\Device::where('user_id', $request->user()->id)->first();
        if ($device) {
            $device->delete();
        }

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function user(Request $request)
    {
        return new \App\Http\Resources\UserResource($request->user());
    }

    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (! $user) {
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

        if (! $request->user()->tokenCan('reset_token')) {
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

        $device = \App\Models\Device::where('user_id', $request->user()->id)->first();
        if ($device) {
            $device->delete();
        }

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
