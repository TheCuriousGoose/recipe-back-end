<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Here you can add authorization logic, for simplicity, we allow all requests
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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

    /**
     * Customize the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The recipe name is required.',
            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category is invalid.',
            'description.required' => 'The description is required.',
            'preparation_time.required' => 'The preparation time is required.',
            'preparation_time.integer' => 'The preparation time must be an integer.',
            'cooking_time.required' => 'The cooking time is required.',
            'cooking_time.integer' => 'The cooking time must be an integer.',
            'servings.required' => 'The number of servings is required.',
            'servings.integer' => 'The number of servings must be an integer.',
            'instructions.required' => 'The instructions are required.',
            'instructions.array' => 'The instructions must be an array.',
            'instructions.*.step.required' => 'Each instruction step is required.',
            'instructions.*.title.required' => 'Each instruction title is required.',
            'instructions.*.description.required' => 'Each instruction description is required.',
            'ingredients.required' => 'The ingredients are required.',
            'ingredients.array' => 'The ingredients must be an array.',
            'ingredients.*.name.required' => 'Each ingredient name is required.',
            'ingredients.*.unit_id.exists' => 'The selected unit is invalid.',
        ];
    }
}
