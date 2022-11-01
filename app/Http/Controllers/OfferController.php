<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'thumbnail' => 'required|image',
        ]);

        $offer = new \App\Models\Offer();
        $offer->title = $request->title;
        $offer->body = $request->body;
        $offer->price = $request->price;
        $offer->created_by = auth()->id();
        $offer->thumbnail = $request->thumbnail->store('thumbnails', 'public');
        $offer->save();

        return response()->json([
            'message' => 'Offer created successfully',
            'offer' => $offer,
        ]);
    }

    public function index(Request $request)
    {
        if ($request->user()->hasRole('agent')) {
            $offers = $request->user()->offers;
        } else {
            if ($request->agent_id) {
                $request->validate([
                    'agent_id' => 'required|exists:users,id',
                ]);
                $agent = \App\Models\User::find($request->agent_id);
                if (! $agent->hasRole('agent')) {
                    return response()->json([
                        'message' => 'Invalid agent',
                    ], 400);
                }
                $offers = \App\Models\Offer::where('created_by', $request->agent_id)->get();
            } else {
                $offers = \App\Models\Offer::all();
            }
        }

        return response()->json([
            'message' => 'Successfully fetched offers',
            'offers' => $offers,
        ], 200);
    }
}
