<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function get_terms_and_conditions(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Successfully fetched terms and conditions',
            't_and_c' => new DocumentResource(\App\Models\Document::where('title', 'Terms and Conditions')->first()),
            'status' => $user->userDetail->terms_accepted,
        ], 200);
    }

    public function post_terms_and_conditions(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;
        $user_detail->update([
            'terms_accepted' => true,
        ]);

        return response()->json([
            'message' => 'Successfully updated terms and conditions',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function get_privacy_policy(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Successfully fetched privacy policy',
            'privacy_policy' => new DocumentResource(\App\Models\Document::where('title', 'Privacy Policy')->first()),
            'status' => $user->userDetail->privacy_accepted,
        ], 200);
    }

    public function post_privacy_policy(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;
        $user_detail->update([
            'privacy_accepted' => true,
        ]);

        return response()->json([
            'message' => 'Successfully updated privacy policy',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function get_onboarding(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;

        return response()->json([
            'message' => 'Successfully fetched onboarding status',
            'onboarding_status' => $user_detail->onboarded,
        ], 200);
    }

    public function post_onboarding(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;
        $user_detail->update([
            'onboarded' => true,
        ]);

        return response()->json([
            'message' => 'Successfully updated onboarding status',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function enable_push_notifications(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;
        $user_detail->update([
            'push_notifications' => true,
        ]);

        return response()->json([
            'message' => 'Successfully enabled push notifications',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function disable_push_notifications(Request $request)
    {
        $user = $request->user();
        $user_detail = $user->userDetail;
        $user_detail->update([
            'push_notifications' => false,
        ]);

        return response()->json([
            'message' => 'Successfully disabled push notifications',
            'user' => UserResource::make($user),
        ], 200);
    }

    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Successfully fetched user details',
            'user_detail' => new UserDetailResource($user->userDetail),
        ], 200);
    }
}
