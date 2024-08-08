<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RecipeRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'preparation_time' => 'required|integer|min:0',
            'cooking_time' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'is_published' => 'boolean',
            'instructions' => 'required|array',
            'instructions.*.step' => 'required|integer|min:1',
            'instructions.*.title' => 'required|string|max:255',
            'instructions.*.description' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.amount' => 'nullable|string',
            'ingredients.*.unit_id' => 'nullable|exists:units,id',
        ];
    }
}
