<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        $profile = $profile->load('user');
        $profile = $profile->load('user.misc');

        return response()->json([
            'message' => 'Successfully fetched profile',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'instagram_handle' => 'nullable|string',
            'tiktok_handle' => 'nullable|string',
            'facebook_handle' => 'nullable|string',
            'name' => 'nullable|string',
        ]);

        $user = $request->user();

        if ($request->name) {
            $user->update([
                'name' => $request->name,
            ]);
        }

        $profile = $user->profile;
        $profile->update([
            'instagram_handle' => $request->instagram_handle ?? $profile->instagram_handle,
            'tiktok_handle' => $request->tiktok_handle ?? $profile->tiktok_handle,
            'facebook_handle' => $request->facebook_handle ?? $profile->facebook_handle,
        ]);

        if ($request->profile_picture) {
            $image_path = $request->file('profile_picture')->store('pfps', 'public');
            $user = $request->user();
            $profile->update([
                'profile_picture' => $image_path,
            ]);
        }

        return response()->json([
            'message' => 'Successfully updated profile',
            'user' => UserResource::make($user),
        ], 200);
    }
}
