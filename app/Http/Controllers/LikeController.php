<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function index()
    {
        $likes = Like::all();
        return response()->json($likes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $existingLike = Like::where('user_id', $validated['user_id'])
                            ->where('product_id', $validated['product_id'])
                            ->first();

        if ($existingLike) {
            return response()->json([
                'message' => 'Like already exists.'
            ], 409); 
        }

        $like = Like::create($validated);

        return response()->json([
            'message' => 'Like created successfully.',
            'like' => $like
        ], 201);
    }

    public function show(Like $like)
    {
        return response()->json($like);
    }

    public function update(Request $request, Like $like)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $like->update($validated);

        return response()->json([
            'message' => 'Like updated successfully.',
            'like' => $like
        ]);
    }

    public function destroy(Like $like)
    {
        $like->delete();

        return response()->json([
            'message' => 'Like deleted successfully.'
        ]);
    }
}
