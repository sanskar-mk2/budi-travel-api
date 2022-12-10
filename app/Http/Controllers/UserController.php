<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function agents(Request $request)
    {
        if ($request->user()->hasRole('agent')) {
            return response()->json([
                'message' => 'Invalid authenticated user role',
            ], 401);
        }

        $agents = \App\Models\User::role('agent')
            ->with('profile')
            ->with('agentReviews')
            ->paginate(10);

        return response()->json([
            'message' => 'Successfully fetched agents',
            'agents' => $agents,
        ], 200);
    }

    public function featured_agents(Request $request)
    {
        if ($request->user()->hasRole('agent')) {
            return response()->json([
                'message' => 'Invalid authenticated user role',
            ], 401);
        }

        // get top 10 agents with most offers
        $agents = \App\Models\User::role('agent')->withCount('offers')->orderBy('offers_count', 'desc')->take(10)->get();
        $agents = $agents->load('profile');
        $agents = $agents->load('agentReviews');

        return response()->json([
            'message' => 'Successfully fetched featured agents',
            'agents' => $agents,
        ], 200);
    }

    public function search_agent(Request $request)
    {
        if ($request->user()->hasRole('agent')) {
            return response()->json([
                'message' => 'Invalid authenticated user role',
            ], 401);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $agents = \App\Models\User::role('agent')->where('name', 'like', '%' . $request->name . '%')->get();
        $agents = $agents->load('profile');
        $agents = $agents->load('agentReviews');

        return response()->json([
            'message' => 'Successfully fetched agents',
            'agents' => $agents,
        ], 200);
    }

    public function users(Request $request)
    {
        if ($request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'Invalid authenticated user role',
            ], 401);
        }

        $users = \App\Models\User::role('user')
            ->with('profile')
            ->with('userReviews')
            ->paginate(10);

        return response()->json([
            'message' => 'Successfully fetched users',
            'users' => $users,
        ], 200);
    }
}
