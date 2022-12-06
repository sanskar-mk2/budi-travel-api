<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function get_terms_and_conditions(Request $request)
    {
        $user = $request->user();


        return response()->json([
            'message' => 'Successfully fetched terms and conditions',
            't_and_c' => "Terms and conditions go here",
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
            'user_detail' => $user_detail,
        ], 200);
    }

    public function get_privacy_policy(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'message' => 'Successfully fetched privacy policy',
            'privacy_policy' => "Privacy policy goes here",
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
            'user_detail' => $user_detail,
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
            'user_detail' => $user_detail,
        ], 200);
    }
}
