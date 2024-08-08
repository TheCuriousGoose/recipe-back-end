<?php

namespace App\Http\Requests;

use App\Rules\BadWord;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', new BadWord],
            'description' => ['nullable', 'string', new BadWord],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
}
