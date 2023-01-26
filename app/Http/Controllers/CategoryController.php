<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        $cats = CategoryResource::collection($categories);

        return response()->json([
            'message' => 'Successfully fetched categories',
            'categories' => $cats,
        ], 200);
    }

    public function create_interests(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|integer',
        ]);

        $user = $request->user();

        $user->categories()->sync($request->category_ids);

        $categories = $user->categories;

        $cats = new CategoryCollection($categories);

        return response()->json([
            'message' => 'Successfully created interests',
            'interests' => $cats,
        ], 200);
    }

    public function my_interests(Request $request)
    {
        $user = $request->user();

        $categories = $user->categories;

        $cats = new CategoryCollection($categories);

        return response()->json([
            'message' => 'Successfully fetched interests',
            'interests' => $cats,
        ], 200);
    }
}
