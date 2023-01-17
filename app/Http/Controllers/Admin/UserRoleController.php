<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::role('user')->paginate(10);

        UserResource::collection($users);

        return response()->json([
            'message' => 'Successfully fetched users',
            'users' => $users,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string',
        ]);

        $users = User::role('user')
            ->where('name', 'like', "%{$request->search}%")
            ->orWhere('email', 'like', "%{$request->search}%")
            ->paginate(10);

        UserResource::collection($users);

        return response()->json([
            'message' => 'Successfully fetched users',
            'users' => $users,
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $users = User::role('user')
            ->whereBetween('created_at', [$request->from, $request->to])
            ->paginate(10);

        UserResource::collection($users);

        return response()->json([
            'message' => 'Successfully fetched users',
            'users' => $users,
        ]);
    }
}
