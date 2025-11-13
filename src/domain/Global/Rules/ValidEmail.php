<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidEmail implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $response = Http::get("https://api.mailcheck.ai/email/{$value}");

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['disposable']) && $data['disposable']) {
                return false;
            }
        }

        return true;
    }

    public function message(): string
    {
        return 'The :attribute must be a valid, non-disposable email address.';
    }
}