<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
        ]);

        $misc = new \App\Models\Misc();
        $misc->user_id = auth()->user()->id;
        $misc->address = $request->address;
        $misc->tagline = $request->tagline;
        $misc->save();

        return response()->json([
            'message' => 'Misc created successfully',
            'misc' => $misc,
        ], 201);
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
        ]);

        $misc = \App\Models\Misc::where('user_id', auth()->user()->id)->first();
        if (! $misc) {
            $misc = new \App\Models\Misc();
            $misc->user_id = auth()->user()->id;
        }
        $misc->address = $request->address;
        $misc->tagline = $request->tagline;
        $misc->save();

        return response()->json([
            'message' => 'Misc upserted successfully',
            'user' => UserResource::make($misc->user),
        ], 201);
    }
}
