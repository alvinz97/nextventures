<?php

namespace Domain\Refund\Rules;

use Domain\Refund\Models\Refund;
use Illuminate\Contracts\Validation\Rule;

class RefundExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Refund::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected refund request does not exist.';
    }
}
