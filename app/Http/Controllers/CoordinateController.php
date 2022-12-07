<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CoordinateController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = $request->user();

        $coordinates = $user->coordinates()->firstOrNew();

        $coordinates->latitude = $request->latitude;
        $coordinates->longitude = $request->longitude;

        $coordinates->save();

        return response()->json([
            'message' => 'Successfully updated coordinates',
            'coordinates' => $coordinates,
        ], 200);
    }

    public function nearby_agents(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->whereHas('coordinates', function ($query) use ($request) {
            $query->where('latitude', '>', $request->latitude - 0.5)
                ->where('latitude', '<', $request->latitude + 0.5)
                ->where('longitude', '>', $request->longitude - 0.5)
                ->where('longitude', '<', $request->longitude + 0.5);
        })->get();

        return response()->json([
            'message' => 'Successfully fetched nearby agents',
            'users' => $users->load('coordinates'),
        ], 200);
    }
}
