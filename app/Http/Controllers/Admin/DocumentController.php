<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function update_terms_and_conditions(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $document = Document::where('title', 'Terms and Conditions')->first();
        $document->content = $request->content;
        $document->save();

        // return api response
        return response()->json([
            'message' => 'Term and Conditions updated successfully',
        ], 200);
    }

    public function update_privacy_policy(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $document = Document::where('title', 'Privacy Policy')->first();
        $document->content = $request->content;
        $document->save();

        // return api response
        return response()->json([
            'message' => 'Privacy Policy updated successfully',
        ], 200);
    }

    public function get_terms_and_conditions()
    {
        $document = Document::where('title', 'Terms and Conditions')->first();

        // return api response
        return response()->json([
            'message' => 'Term and Conditions fetched successfully',
            'data' => $document,
        ], 200);
    }

    public function get_privacy_policy()
    {
        $document = Document::where('title', 'Privacy Policy')->first();

        // return api response
        return response()->json([
            'message' => 'Privacy Policy fetched successfully',
            'data' => $document,
        ], 200);
    }
}
