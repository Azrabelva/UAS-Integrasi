<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // GET /api/reviews
    public function index()
    {
        return response()->json(Review::all());
    }

    // POST /api/reviews
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|integer',
            'product_id' => 'required|integer',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string',
        ]);

        $review = Review::create($validated);

        return response()->json([
            'message' => 'Review berhasil ditambahkan!',
            'data' => $review,
        ], 201);
    }
}

