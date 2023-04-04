<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptchaV3 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value
        ]);

        if (!$response->ok() || !$response->json()['success']) {
            $fail('The google recaptcha is required.');
            info($response->json());
        }
    }
}
