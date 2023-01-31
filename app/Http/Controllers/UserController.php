<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
            ->when($request->from, function ($query, $from) {
                return $query->where('created_at', '>=', $from);
            })->when($request->to, function ($query, $to) {
                return $query->where('created_at', '<=', $to);
            })->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);
        UserResource::collection($agents);

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

        return response()->json([
            'message' => 'Successfully fetched featured agents',
            'agents' => UserResource::collection($agents),
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

        return response()->json([
            'message' => 'Successfully fetched agents',
            'agents' => UserResource::collection($agents),
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
            ->when($request->from, function ($query, $from) {
                return $query->where('created_at', '>=', $from);
            })->when($request->to, function ($query, $to) {
                return $query->where('created_at', '<=', $to);
            })->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);
        UserResource::collection($users);

        return response()->json([
            'message' => 'Successfully fetched users',
            'users' => $users,
        ], 200);
    }

    public function users_created_count_by_month(Request $request)
    {
        $users = \App\Models\User::role('user')->selectRaw('count(*) as count, MONTH(created_at) as month')->groupBy('month')->get();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $count = [];
        foreach ($months as $key => $month) {
            $count[$key] = 0;
            if ($users->where('month', $key + 1)->first()) {
                $count[$key] = $users->where('month', $key + 1)->first()->count;
            }
        }

        return response()->json([
            'message' => 'Successfully fetched users created count by month',
            'months' => $months,
            'count' => $count,
        ], 200);
    }

    public function agents_created_count_by_month(Request $request)
    {
        $agents = \App\Models\User::role('agent')->selectRaw('count(*) as count, MONTH(created_at) as month')->groupBy('month')->get();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $count = [];
        foreach ($months as $key => $month) {
            $count[$key] = 0;
            if ($agents->where('month', $key + 1)->first()) {
                $count[$key] = $agents->where('month', $key + 1)->first()->count;
            }
        }

        return response()->json([
            'message' => 'Successfully fetched agents created count by month',
            'months' => $months,
            'count' => $count,
        ], 200);
    }
}
