<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportReplyResource;
use App\Http\Resources\SupportResource;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function support_tickets(Request $request)
    {
        // fetch all support tickets, paginated
        $supports = \App\Models\Support::paginate(10);

        SupportResource::collection($supports);

        return response()->json([
            'supports' => $supports,
        ]);
    }

    public function support_ticket(Request $request, $id)
    {
        // fetch support ticket
        $support = \App\Models\Support::find($id);

        if (! $support) {
            return response()->json([
                'message' => 'Support ticket not found',
            ], 404);
        }

        $support->load([
            'replies' => function ($query) {
                $query->with('user');
            },

        ]);

        return response()->json([
            'support' => SupportResource::make($support),
        ]);
    }

    public function reply(Request $request)
    {
        $request->validate([
            'support_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $support = \App\Models\Support::find($request->support_id);

        if (! $support) {
            return response()->json([
                'message' => 'Support ticket not found',
            ], 404);
        }

        $support_reply = \App\Models\SupportReply::create([
            'support_id' => $request->support_id,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Successfully replied to support ticket',
            'support_reply' => SupportReplyResource::make($support_reply),
        ], 201);
    }
}
