<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'user' => UserResource::make($user),
        ]);
    }

    public function update_profile(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'instagram_handle' => 'nullable',
            'tiktok_handle' => 'nullable',
            'facebook_handle' => 'nullable',
        ]);

        $profile = $user->profile;

        $image_path = $request->file('profile_picture')->store('pfps', 'public');

        $profile->update([
            'profile_picture' => $image_path,
            'instagram_handle' => $request->instagram_handle,
            'tiktok_handle' => $request->tiktok_handle,
            'facebook_handle' => $request->facebook_handle,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function update_password(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }
}
