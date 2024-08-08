<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BadWord implements ValidationRule
{
    protected array $badWords = ['fuck', 'shit','cunt','motherfucker', 'bitch', 'slut', 'asshole']; 

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->badWords as $badWord) {
            if (stripos($value, $badWord) !== false) {
                $fail("The {$attribute} contains a bad word: {$badWord}.");
                return;
            }
        }
    }
}
