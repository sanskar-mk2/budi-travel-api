<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
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
            'document' => DocumentResource::make($document),
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
            'document' => DocumentResource::make($document),
        ], 200);
    }

    public function get_terms_and_conditions()
    {
        $document = Document::where('title', 'Terms and Conditions')->first();

        // return api response
        return response()->json([
            'message' => 'Term and Conditions fetched successfully',
            'document' => DocumentResource::make($document),
        ], 200);
    }

    public function get_privacy_policy()
    {
        $document = Document::where('title', 'Privacy Policy')->first();

        // return api response
        return response()->json([
            'message' => 'Privacy Policy fetched successfully',
            'document' => DocumentResource::make($document),
        ], 200);
    }
}
