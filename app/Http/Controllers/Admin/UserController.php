<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->load('profile');
        $user->load('roles');
        $user->load('userDetail');
        $user->load('agentStatus');

        return response()->json([
            'user' => $user,
        ]);
    }
}
