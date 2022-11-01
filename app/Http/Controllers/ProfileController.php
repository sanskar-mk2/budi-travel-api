<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        return response()->json([
            'message' => 'Successfully fetched profile',
            'profile' => $profile,
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'instagram_handle' => 'nullable|string',
            'tiktok_handle' => 'nullable|string',
            'facebook_handle' => 'nullable|string',
        ]);

        $image_path = $request->file('profile_picture')->store('pfps', 'public');
        $user = $request->user();
        $profile = $user->profile;
        $profile->update([
            'profile_picture' => $image_path,
            'instagram_handle' => $request->instagram_handle,
            'tiktok_handle' => $request->tiktok_handle,
            'facebook_handle' => $request->facebook_handle,
        ]);

        return response()->json([
            'message' => 'Successfully updated profile',
            'profile' => $profile,
        ], 200);
    }
}
