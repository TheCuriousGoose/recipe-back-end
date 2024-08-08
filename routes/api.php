<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public routes
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/recipes/{recipe}/reviews', [ReviewController::class, 'show'])->name('reviews.show');

// Protected routes
Route::middleware('auth.api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/recipes/{recipe}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/recipes/{recipe}/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
