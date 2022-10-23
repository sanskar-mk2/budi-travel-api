<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function agents(Request $request)
    {
        if (! $request->user()->tokenCan('auth_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // make sure the user has the user role
        if (! $request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'Invalid user role',
            ], 401);
        }

        $agents = \App\Models\User::role('agent')->get();
        $agents = $agents->load('profile');
        $agents = $agents->load('agentReviews');

        return response()->json([
            'message' => 'Successfully fetched agents',
            'agents' => $agents,
        ], 200);
    }
}
