<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentReviewController extends Controller
{
    public function create(Request $request)
    {
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

    public function index(Request $request)
    {
        if ($request->id) {
            $request->validate([
                'id' => 'required|integer|exists:users,id',
            ]);
            $user = \App\Models\User::find($request->id);
            if ($user->hasRole('agent')) {
                $reviews = $user->agentReviews;
            } elseif ($user->hasRole('user')) {
                $reviews = $user->userReviews;
            } else {
                return response()->json([
                    'message' => 'Invalid user',
                ], 400);
            }
        } else {
            $reviews = \App\Models\AgentReview::all();
        }

        return response()->json([
            'message' => 'Successfully fetched agent reviews',
            'reviews' => $reviews,
        ], 200);
    }

    public function me(Request $request)
    {
        if ($request->user()->hasRole('user')) {
            $userReviews = $request->user()->userReviews;
        } elseif ($request->user()->hasRole('agent')) {
            $userReviews = $request->user()->agentReviews;
        } else {
            return response()->json([
                'message' => 'Invalid role',
            ], 401);
        }

        return response()->json([
            'message' => 'Successfully fetched reviews related to this user/agent',
            'reviews' => $userReviews,
        ], 200);
    }
}
