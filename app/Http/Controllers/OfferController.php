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
            'image' => 'array',
            'image.*' => 'image',
        ]);

        $offer = new \App\Models\Offer();
        $offer->title = $request->title;
        $offer->body = $request->body;
        $offer->price = $request->price;
        $offer->created_by = auth()->id();
        $offer->thumbnail = $request->thumbnail->store('thumbnails', 'public');
        $offer->save();

        if ($request->hasFile('image')) {
            foreach ($request->image as $image) {
                $offer->offerImages()->create([
                    'image' => $image->store('offer_images', 'public'),
                ]);
            }
        }

        return response()->json([
            'message' => 'Offer created successfully',
            'offer' => $offer->load('offerImages'),
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
                $offers = \App\Models\Offer::paginate(10);
            }
        }

        return response()->json([
            'message' => 'Successfully fetched offers',
            'offers' => $offers,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $offer = \App\Models\Offer::findOrFail($id);

        return response()->json([
            'message' => 'Successfully fetched offer',
            'offer' => $offer->load('offerImages'),
        ], 200);
    }
}
