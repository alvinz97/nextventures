<?php

namespace Domain\Global\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailValidationRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        return preg_match('/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/', $value);
    }

    public function message(): string
    {
        return 'Invalid Format.';
    }
}
