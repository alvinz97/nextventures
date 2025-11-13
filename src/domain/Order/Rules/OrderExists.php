<?php

namespace Domain\Order\Rules;

use Domain\Order\Models\Order;
use Illuminate\Contracts\Validation\Rule;

class OrderExists implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Order::query()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'The selected order does not exist.';
    }
}
