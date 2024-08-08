<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'category_id',
        'image',
        'preparation_time',
        'cooking_time',
        'servings',
        'is_published',
    ];

    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')->withPivot(['unit_id', 'amount']);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->whereIsApproved(true);
    }

    public function rating(): float|null
    {
        return $this->reviews()->avg('rating');
    }

    public function scopeFilters($query, Request $request)
    {
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $query->orWhere('description', 'like', '%' . $request->search . '%');
            $query->orWhereHas('ingredients', function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('sort')) {
            if ($request->sort === 'rating') {
                $query->orderBy('rating', $request->get('order', 'desc'));
            } elseif ($request->sort === 'date') {
                $query->orderBy('created_at', $request->get('order', 'desc'));
            }
        }

        $query->where('is_published', true);

        return $query;
    }
}
