<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    // Allow basic recipe filtering
    public function index(Request $request)
    {
        $recipes = Recipe::query()
            ->with(['ingredients', 'reviews', 'category', 'author:id,name'])
            ->filters($request)
            ->get();

        $recipes->each(function ($recipe) {
            $recipe->rating = $recipe->rating();
            unset($recipe->reviews);
        });

        return response()->json([
            'status' => 'success',
            'recipes' => $recipes
        ]);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'instructions', 'reviews', 'category', 'author:id,name']);

        return response()->json([
            'status' => 'success',
            'recipes' => [$recipe]
        ]);
    }

    public function store(RecipeRequest $request)
    {
        $recipe = Recipe::create([
            'name' => $request->name,
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $request->image,
            'preparation_time' => $request->preparation_time,
            'cooking_time' => $request->cooking_time,
            'servings' => $request->servings,
            'is_published' => $request->is_published,
        ]);

        foreach ($request->instructions as $instruction) {
            $recipe->instructions()->create($instruction);
        }

        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'recipe' => $recipe
        ], 201);
    }

    public function update(RecipeRequest $request, Recipe $recipe)
    {
        $recipe->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $request->image,
            'preparation_time' => $request->preparation_time,
            'cooking_time' => $request->cooking_time,
            'servings' => $request->servings,
            'is_published' => $request->is_published,
        ]);

        $recipe->instructions()->delete();
        foreach ($request->instructions as $instruction) {
            $recipe->instructions()->create($instruction);
        }

        $recipe->ingredients()->delete();
        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe updated successfully'
        ]);
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe deleted successfully'
        ]);
    }
}
