<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentReviewController extends Controller
{
    public function create(Request $request)
    {
        if (! $request->user()->tokenCan('auth_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // check if the auth user has the user role
        if (! $request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'Invalid user role',
            ], 401);
        }

        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'agent_id' => 'required|integer|exists:users,id',
        ]);

        // make sure that every user can only review an agent once
        $agentReview = \App\Models\AgentReview::where('user_id', $request->user()->id)
            ->where('agent_id', $request->agent_id)
            ->first();
        if ($agentReview) {
            return response()->json([
                'message' => 'You have already reviewed this agent',
            ], 400);
        }

        $agent = \App\Models\User::where('id', $request->agent_id)->first();

        if (! $agent->hasRole('agent')) {
            return response()->json([
                'message' => 'Invalid agent id',
            ], 401);
        }

        $agentReview = new \App\Models\AgentReview();
        $agentReview->title = $request->title;
        $agentReview->body = $request->body;
        $agentReview->rating = $request->rating;
        $agentReview->agent_id = $request->agent_id;
        $agentReview->user_id = auth()->user()->id;
        $agentReview->save();

        return response()->json([
            'message' => 'Successfully created agent review',
            'agentReview' => $agentReview,
        ], 201);
    }

    public function agent(Request $request)
    {
        if (! $request->user()->tokenCan('auth_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // check if the auth user has the agent role
        if (! $request->user()->hasRole('agent')) {
            return response()->json([
                'message' => 'Invalid role',
            ], 401);
        }

        $agentReviews = $request->user()->agentReviews;

        return response()->json([
            'message' => 'Successfully fetched reviews for agent',
            'agentReviews' => $agentReviews,
        ], 200);
    }

    public function user(Request $request)
    {
        if (! $request->user()->tokenCan('auth_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // check if the auth user has the user role
        if (! $request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'Invalid role',
            ], 401);
        }

        $userReviews = $request->user()->userReviews;

        return response()->json([
            'message' => 'Successfully fetched reviews created by this user',
            'userReviews' => $userReviews,
        ], 200);
    }
}
