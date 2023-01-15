<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupportResource;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function contact_us(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = $request->user();

        $support = $user->supports()->create([
            'topic' => $request->topic,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Successfully created support ticket',
            'support' => SupportResource::make($support),
        ], 200);
    }

    public function support_tickets(Request $request)
    {
        $user = $request->user();

        $supports = $user->supports()->get();

        return response()->json([
            'message' => 'Successfully fetched support tickets',
            'supports' => SupportResource::collection($supports),
        ], 200);
    }

    public function support_ticket(Request $request, $id)
    {
        $user = $request->user();

        $support = $user->supports()->where('id', $id)->first();

        if (! $support) {
            return response()->json([
                'message' => 'Support ticket not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched support ticket',
            'support' => SupportResource::make($support),
        ], 200);
    }
}
