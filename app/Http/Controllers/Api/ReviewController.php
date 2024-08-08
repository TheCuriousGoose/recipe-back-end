<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function show(Recipe $recipe)
    {
        $recipe->load('reviews.author:id,name');

        return response()->json([
            'status' => 'success',
            'message' => 'Reviews retrieved successfully.',
            'reviews' => $recipe->reviews
        ]);
    }

    public function store(ReviewRequest $request, Recipe $recipe)
    {
        $review = $request->user()->reviews()->create([
            'recipe_id' => $recipe->id,
            'title' => $request->title,
            'description' => $request->description,
            'rating' => $request->rating,
            'is_approved' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review created successfully.',
            'reviews' => [$review]
        ]);
    }

    public function update(ReviewRequest $request, Recipe $recipe, Review $review)
    {
        $review->update([
            'title' => $request->title,
            'description' => $request->description,
            'rating' => $request->rating,
            'is_approved' => true
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully.',
            'reviews' => [$review]
        ]);
    }

    public function destroy(Recipe $recipe, Review $review)
    {
        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully.'
        ]);
    }
}
