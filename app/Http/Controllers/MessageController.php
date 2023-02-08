<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
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
            'messages' => MessageResource::collection($messages),
        ], 200);
    }

    public function index_users(Request $request)
    {
        $messages = \App\Models\Message::where('receiver_id', $request->user()->id)
            ->orWhere('sender_id', $request->user()->id)
            ->orderByDesc('created_at')->get()
            ->unique(function ($item) use ($request) {
                return $item->sender_id == $request->user()->id ? $item->receiver_id : $item->sender_id;
            });
        MessageResource::collection($messages);

        return response()->json([
            'message' => 'Successfully fetched messages',
            'messages' => MessageResource::collection($messages),
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
            'message' => MessageResource::make($message),
        ], 200);
    }
}
