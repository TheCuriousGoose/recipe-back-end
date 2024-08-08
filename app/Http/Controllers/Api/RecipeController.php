<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecipeRequest;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Unit;

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
            'message' => 'Recipes retrieved successfully',
            'recipes' => $recipes
        ]);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'instructions', 'reviews', 'category', 'author:id,name']);

        $recipe->rating = $recipe->rating();

        $units = Unit::select(['id', 'name', 'notation'])->get();

        foreach ($recipe->ingredients as $ingredient => $values) {
            $recipe->ingredients[$ingredient]->unit = $units->find($values->pivot->unit_id);
            $recipe->ingredients[$ingredient]->amount = $values->pivot->amount;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe retrieved successfully',
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

        foreach ($request->ingredients as $ingredientData) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => $ingredientData['name']],
            );

            $recipe->ingredients()->attach($ingredient->id, [
                'amount' => $ingredientData['amount'],
                'unit_id' => $ingredientData['unit_id']
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe created successfully',
            'recipes' => [$recipe]
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
        foreach ($request->ingredients as $ingredientData) {
            $ingredient = Ingredient::firstOrCreate(
                ['name' => $ingredientData['name']],
            );

            $recipe->ingredients()->attach($ingredient->id, [
                'amount' => $ingredientData['amount'],
                'unit_id' => $ingredientData['unit_id']
            ]);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Recipe updated successfully',
            'recipes' => [$recipe]
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
