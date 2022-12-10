<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
        ]);

        $messages = \App\Models\Message::between($request->user()->id, $request->receiver_id)->with('sender', 'receiver')->get();

        return response()->json([
            'message' => 'Successfully fetched messages',
            'messages' => $messages,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $message = \App\Models\Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Successfully sent message',
            'message' => $message,
        ], 200);
    }
}
